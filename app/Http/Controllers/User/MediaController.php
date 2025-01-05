<?php

namespace App\Http\Controllers\User;

use App\Medias;
use App\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class MediaController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    // All user media
    public function media(Request $request)
    {
        $active_plan = json_decode(Auth::user()->plan_details);

        if ($active_plan != null) {
            if ($request->ajax()) {
                $media = Medias::where('user_id', Auth::user()->user_id)
                    ->orderBy('id', 'desc')
                    ->paginate(8);

                // Format the created_at date
                $media->getCollection()->transform(function ($item, $key) {
                    $item->formatted_created_at = Carbon::parse($item->created_at)->format('d-m-Y h:i A');
                    $item->base_url = asset('');
                    return $item;
                });

                return response()->json(['media' => $media]);
            }

            $settings = Setting::where('status', 1)->first();

            return view('user.pages.media.index', compact('settings'));
        } else {
            return redirect()->route('user.plans');
        }
    }

    // Add media
    public function addMedia()
    {
        // Queries
        $settings = Setting::where('status', 1)->first();

        return view('user.pages.media.add', compact('settings'));
    }

    // Upload media
    public function uploadMedia(Request $request)
    {
        // Parameters
        $image = $request->file('file');

        // Unique ID
        $uniqid = uniqid();

        // Upload image
        if ($image->extension() == "jpeg" || $image->extension() == "png" || $image->extension() == "jpg" || $image->extension() == "gif" || $image->extension() == "svg") {
            $imageName = Auth::user()->user_id . '-' . $uniqid . '.' . $image->extension();
            $media_url = "images/" . Auth::user()->user_id . '-' . $uniqid . '.' . $image->extension();
            $image->move(public_path('images'), $imageName);

            // Save
            $card = new Medias();
            $card->media_id = $uniqid;
            $card->user_id = Auth::user()->user_id;
            $card->media_name = $image->getClientOriginalName();
            $card->media_url = $media_url;
            $card->save();
        }

        return response()->json(['status' => 'success', 'message' => trans('Image has been successfully uploaded.')]);
    }

    public function deleteMedia(Request $request)
    {
        // Queries
        $media_data = Medias::where('user_id', Auth::user()->user_id)->where('media_id', $request->query('id'))->first();

        // Check media
        if ($media_data != null) {

            // Delete media image
            Medias::where('user_id', Auth::user()->user_id)->where('media_id', $request->query('id'))->delete();

            return redirect()->route('user.media')->with('success', trans('Removed!'));
        }
    }

    public function multipleImages(Request $request)
    {
        // Parameters
        $image = $request->file('file');

        // Unique ID
        $uniqid = uniqid();

        // Upload image
        if ($image->extension() == "jpeg" || $image->extension() == "png" || $image->extension() == "jpg" || $image->extension() == "gif" || $image->extension() == "svg") {
            $imageName = Auth::user()->user_id . '-' . $uniqid . '.' . $image->extension();
            $media_url = "images/" . Auth::user()->user_id . '-' . $uniqid . '.' . $image->extension();
            $image->move(public_path('images'), $imageName);
        }

        // Save
        $card = new Medias();
        $card->media_id = $uniqid;
        $card->user_id = Auth::user()->user_id;
        $card->media_name = $image->getClientOriginalName();
        $card->media_url = $media_url;
        $card->save();

        return response()->json(['image_url' => $imageName]);
    }

    // vCard and Store media upload
    public function getMediaData(Request $request)
    {
        if ($request->ajax()) {
            $media = Medias::where('user_id', Auth::user()->user_id)
                ->orderBy('id', 'desc');

            return DataTables::of($media)->make(true);
        }

        return view('media.index');
    }
}

<?php

namespace App\Http\Controllers\User;

use App\Plan;
use App\User;
use App\Setting;
use Carbon\Carbon;
use App\BusinessCard;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class StoreController extends Controller
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

    // All user stores
    public function index(Request $request)
    {
        $active_plan = Plan::where('plan_id', Auth::user()->plan_id)->first();
        $plan = User::where('user_id', Auth::user()->user_id)->first();
        $active_plan = json_decode($plan->plan_details);

        if ($active_plan != null) {
            if ($request->ajax()) {
                $business_cards = DB::table('business_cards')
                    ->join('users', 'business_cards.user_id', '=', 'users.user_id')
                    ->select('users.user_id', 'users.plan_validity', 'business_cards.*')
                    ->where('business_cards.user_id', Auth::user()->user_id)
                    ->where('business_cards.card_type', 'store')
                    ->where('business_cards.status', 1)
                    ->where('business_cards.card_status', '!=', 'deleted')
                    ->orderBy('business_cards.id', 'desc')
                    ->get();

                return DataTables::of($business_cards)
                    ->addIndexColumn()
                    ->editColumn('created_at', function ($card) {
                        return Carbon::parse($card->created_at)->format('M jS, Y g:i A');
                    })
                    ->editColumn('title', function ($card) {
                        return '<div class="d-flex py-1 align-items-center">
                                    <span class="avatar me-2" style="background-image: url(' . asset($card->profile) . ')"></span>
                                    <div class="flex-fill">
                                        <div class="font-weight-medium"><a href="' . route('user.edit.card', $card->card_id) . '" class="text-reset">' . $card->title . '</a></div>
                                        <div class="text-secondary">' . $card->sub_title . '</div>
                                    </div>
                                </div>';
                    })
                    ->editColumn('card_status', function ($card) {
                        return $card->card_status == 'inactive'
                            ? '<span class="badge bg-red text-white text-white">' . __('Disabled') . '</span>'
                            : '<span class="badge bg-green text-white text-white">' . __('Enabled') . '</span>';
                    })
                    ->addColumn('action', function ($card) {
                        $config = DB::table('config')->get();

                        // Preview
                        $preview = '<a class="btn btn-primary btn-icon" href="' . route('user.view.preview', $card->card_id) . '" target="_blank" title="' . trans('Preview') . '">
                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-eye"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                                    </a>';

                        // Live
                        $live = $config[46]->config_value == '1'
                            ? '<a class="btn btn-primary btn-icon" href="' . route('subdomain.profile', $card->card_url) . '" target="_blank" title="' . trans('Live') . '">
                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-world-www"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M19.5 7a9 9 0 0 0 -7.5 -4a8.991 8.991 0 0 0 -7.484 4" /><path d="M11.5 3a16.989 16.989 0 0 0 -1.826 4" /><path d="M12.5 3a16.989 16.989 0 0 1 1.828 4" /><path d="M19.5 17a9 9 0 0 1 -7.5 4a8.991 8.991 0 0 1 -7.484 -4" /><path d="M11.5 21a16.989 16.989 0 0 1 -1.826 -4" /><path d="M12.5 21a16.989 16.989 0 0 0 1.828 -4" /><path d="M2 10l1 4l1.5 -4l1.5 4l1 -4" /><path d="M17 10l1 4l1.5 -4l1.5 4l1 -4" /><path d="M9.5 10l1 4l1.5 -4l1.5 4l1 -4" /></svg>
                                    </a>'
                            : '<a class="btn btn-primary btn-icon" href="' . route('profile', $card->card_url) . '" target="_blank" title="' . trans('Live') . '">
                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-world-www"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M19.5 7a9 9 0 0 0 -7.5 -4a8.991 8.991 0 0 0 -7.484 4" /><path d="M11.5 3a16.989 16.989 0 0 0 -1.826 4" /><path d="M12.5 3a16.989 16.989 0 0 1 1.828 4" /><path d="M19.5 17a9 9 0 0 1 -7.5 4a8.991 8.991 0 0 1 -7.484 -4" /><path d="M11.5 21a16.989 16.989 0 0 1 -1.826 -4" /><path d="M12.5 21a16.989 16.989 0 0 0 1.828 -4" /><path d="M2 10l1 4l1.5 -4l1.5 4l1 -4" /><path d="M17 10l1 4l1.5 -4l1.5 4l1 -4" /><path d="M9.5 10l1 4l1.5 -4l1.5 4l1 -4" /></svg>
                                    </a>';

                        // QR
                        $qr = $config[46]->config_value == '1'
                            ? '<a class="btn btn-primary btn-icon open-qr" onclick="updateQr(`' . route('subdomain.profile', $card->card_url) . '`)" title="' . trans('QR Code') . '">
                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-qrcode"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M7 17l0 .01" /><path d="M14 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M7 7l0 .01" /><path d="M4 14m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M17 7l0 .01" /><path d="M14 14l3 0" /><path d="M20 14l0 .01" /><path d="M14 14l0 3" /><path d="M14 20l3 0" /><path d="M17 17l3 0" /><path d="M20 17l0 3" /></svg>
                                </a>'
                            : '<a class="btn btn-primary btn-icon open-qr" onclick="updateQr(`' . route('profile', $card->card_url) . '`)" title="' . trans('QR Code') . '">
                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-qrcode"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M7 17l0 .01" /><path d="M14 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M7 7l0 .01" /><path d="M4 14m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M17 7l0 .01" /><path d="M14 14l3 0" /><path d="M20 14l0 .01" /><path d="M14 14l0 3" /><path d="M14 20l3 0" /><path d="M17 17l3 0" /><path d="M20 17l0 3" /></svg>
                                </a>';

                        // Analytics
                        $analytics = '<a class="btn btn-primary btn-icon" href="' . route('user.visitors', $card->card_url) . '" title="' . trans('Visitors') . '">
                                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-chart-bar"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 13a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v6a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M15 9a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v10a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M9 5a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v14a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M4 20h14" /></svg>
                            </a>';

                        // Edit
                        $edit = '<a class="btn btn-primary btn-icon" href="' . route('user.edit.card', $card->card_id) . '" title="' . trans('Edit') . '">
                                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-pencil"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" /></svg>
                            </a>';

                        // Duplicate
                        $duplicate = '<a class="btn btn-primary btn-icon" onclick="duplicateStore(`' . $card->card_id . '`, `store`); return false;" title="' . trans('Duplicate') . '">
                                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-copy-plus"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path stroke="none" d="M0 0h24v24H0z" /><path d="M7 9.667a2.667 2.667 0 0 1 2.667 -2.667h8.666a2.667 2.667 0 0 1 2.667 2.667v8.666a2.667 2.667 0 0 1 -2.667 2.667h-8.666a2.667 2.667 0 0 1 -2.667 -2.667z" /><path d="M4.012 16.737a2 2 0 0 1 -1.012 -1.737v-10c0 -1.1 .9 -2 2 -2h10c.75 0 1.158 .385 1.5 1" /><path d="M11 14h6" /><path d="M14 11v6" /></svg>
                                        </a>';

                        // More actions
                        $actionBtn = '';
                        // Disable / Enable card
                        $actionBtn .= $card->card_status == 'activated'
                            ? '<a class="open-model dropdown-item" data-id="' . $card->card_id . '" href="#openModel">' . __('Disable') . '</a>'
                            : '<a class="open-model dropdown-item" data-id="' . $card->card_id . '" href="#openModel">' . __('Enable') . '</a>';

                        // Delete
                        $actionBtn .= '<a class="dropdown-item" onclick="deleteStore(`' . $card->card_id . '`, `delete`); return false;">' . __('Delete') . '</a>';

                        return '
                            <div class="btn-list flex-nowrap">
                                ' . $preview . '
                                ' . $live . '
                                ' . $edit . '
                                ' . $duplicate . '
                                ' . $qr . '
                                ' . $analytics . '
                                <div class="dropdown">
                                    <button class="btn btn-icon small-btn align-text-top" data-bs-boundary="viewport" data-bs-toggle="dropdown" aria-expanded="false">
                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-dots-vertical"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M12 19m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M12 5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /></svg>
                                    </button>
                                    <div class="actions actions dropdown-menu dropdown-menu-end" style="">' . $actionBtn . '</div>
                                </div>
                            </div>';
                    })
                    ->rawColumns(['title', 'card_status', 'action'])
                    ->make(true);
            }

            $config = DB::table('config')->get();
            $settings = Setting::where('status', 1)->first();

            return view('user.pages.stores.index', compact('settings', 'config'));
        } else {
            return redirect()->route('user.plans');
        }
    }

    // Delete store
    public function deleteStore(Request $request)
    {
        // Delete
        BusinessCard::where('user_id', Auth::user()->user_id)->where('card_id', $request->query('id'))->update([
            'card_status' => 'deleted',
        ]);

        return redirect()->route('user.stores')->with('success', trans('Removed!'));
    }
}

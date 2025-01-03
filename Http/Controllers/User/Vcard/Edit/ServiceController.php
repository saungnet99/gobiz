<?php

namespace App\Http\Controllers\User\Vcard\Edit;

use App\User;
use App\Service;
use App\Setting;
use Carbon\Carbon;
use App\BusinessCard;
use App\BusinessField;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
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

    // Services
    public function services(Request $request, $id)
    {
        // Queries
        $plan = User::where('user_id', Auth::user()->user_id)->where('status', 1)->first();
        $plan_details = json_decode($plan->plan_details);
        $business_card = BusinessCard::where('card_id', $id)->first();

        if ($business_card == null) {
            return redirect()->route('user.cards')->with('failed', trans('Card not found!'));
        } else {
            if ($request->ajax()) {
                $services = Service::where('card_id', $id)->orderBy('id', 'desc')->get();
                return DataTables::of($services)
                    ->addIndexColumn()
                    ->addColumn('service_image', function ($service) {
                        return __(asset($service->service_image));
                    })
                    ->addColumn('service_name', function ($service) {
                        return __($service->service_name);
                    })
                    ->addColumn('service_description', function ($service) {
                        return __($service->service_description);
                    })
                    ->addColumn('service_enquiry', function ($service) {
                        if ($service->enable_enquiry == "Disabled") {
                            return '<span class="badge bg-red text-white text-white">' . __('Disabled') . '</span>';
                        } else {
                            return '<span class="badge bg-green text-white text-white">' . __('Enabled') . '</span>';
                        }
                    })
                    ->addColumn('actions', function ($service) {
                        return '<div class="d-flex">
                            <button type="button" class="btn btn-success btn-icon m-1" onclick="editService(' . $service->id . ')">
                                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-edit"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                            </button>
                            <button type="button" class="btn btn-danger btn-icon m-1" onclick="deleteService(' . $service->id . ')">
                                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-trash"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                            </button>
                        </div>';
                    })
                    ->rawColumns(['service_enquiry', 'actions'])
                    ->make(true); 
            }

            $settings = Setting::where('status', 1)->first();
            $whatsAppNumberExists = BusinessField::where('card_id', $id)->where('type', 'wa')->exists();

            return view('user.pages.edit-cards.edit-services', compact('plan_details', 'business_card', 'settings', 'whatsAppNumberExists'));
        }
    }

    // Save new service
    public function saveService(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'service_image' => 'required|string', // Add appropriate validation rules
            'service_name' => 'required|string', // Add appropriate validation rules
            'service_description' => 'required|string', // Add appropriate validation rules
            'service_enquiry' => 'required|in:Enabled,Disabled', // Validate that it's either "Enabled" or "Disabled"
        ]);

        // Queries
        $plan = User::where('user_id', Auth::user()->user_id)->where('status', 1)->first();
        $plan_details = json_decode($plan->plan_details);

        // Already created count
        $countedServices = Service::where('card_id', $request->card_id)->count();

        // Check service limit
        if ($countedServices < $plan_details->no_of_services) {
            try {
                // Create a new service with the provided data
                $service = new Service();
                $service->card_id = $request->card_id;
                $service->service_image = $validatedData['service_image'];
                $service->service_name = $validatedData['service_name'];
                $service->service_description = $request->service_description;
                $service->enable_enquiry = $validatedData['service_enquiry'];

                // Save the new service
                $service->save();

                // Return success response
                return response()->json(['success' => true]);
            } catch (\Exception $e) {
                // Return error response if any error occurs
                return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
            }
        } else {
            return response()->json(['success' => false, 'error' => trans('You have reached the plan limit!')], 500);
        }
    }

    // Update service
    public function updateService(Request $request)
    {
        // Validate the request data as per your requirements

        $service = Service::find($request->input('service_id'));

        if (!$service) {
            return response()->json([
                'success' => false,
                'message' => 'Service not found.'
            ], 404);
        }

        // Update service data
        $service->update([
            'service_image' => $request->input('service_image'),
            'service_name' => $request->input('service_name'),
            'service_description' => $request->input('service_description'),
            'enable_enquiry' => $request->input('service_enquiry')
        ]);

        return response()->json([
            'success' => true,
            'message' => trans('Service updated successfully.')
        ]);
    }

    // Get single service ajax call
    public function getService($id)
    {
        try {
            // Retrieve the service from the database
            $service = Service::findOrFail($id);

            // Return a JSON response with the service data
            return response()->json([
                'success' => true,
                'data' => $service
            ], 200);
        } catch (\Exception $e) {
            // Handle errors (e.g., service not found)
            return response()->json([
                'success' => false,
                'message' => trans('Service not found')
            ], 404);
        }
    }

    // Delete service
    public function deleteService($id)
    {
        // Queries
        $service = Service::find($id);

        if ($service) {
            $service->delete();
            return response()->json(['message' => trans('Service deleted successfully')], 200);
        } else {
            return response()->json(['message' => trans('Service not found')], 404);
        }
    }
}

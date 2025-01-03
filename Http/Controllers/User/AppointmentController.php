<?php

namespace App\Http\Controllers\User;

use App\Setting;
use Carbon\Carbon;
use App\BookedAppointment;
use App\CardAppointmentTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\Facades\DataTables;

class AppointmentController extends Controller
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

    //  Get all booked appointments in the vcard
    public function bookedAppointments(Request $request, $id)
    {
        // Get all appointments
        $bookedAppointments = BookedAppointment::where('card_id', $id)->orderBy('id', 'desc')->get();

        $config = DB::table('config')->get();
        $settings = Setting::where('status', 1)->first();

        return view('user.pages.appointment.index', compact('config', 'settings', 'bookedAppointments'));
    }

    public function rescheduleAppointments(Request $request)
    {
        // Get appointment id, new date, and time from the request
        $appointmentId = $request->booked_appointment_id;
        $date = $request->date;
        $time = $request->time;

        // Find the appointment and update its date and time
        $bookedAppointment = BookedAppointment::where('booked_appointment_id', $appointmentId)->first();
        $bookedAppointment->booking_date = $date;
        $bookedAppointment->booking_time = $time;
        $bookedAppointment->booking_status = 1;
        $bookedAppointment->save();

        // Get appointment time slot duration
        $appointmentTimeSlotDuration = CardAppointmentTime::where('card_id', $bookedAppointment->card_id)->first()->slot_duration;

        // Prepare details for the email and Google Calendar
        $appointmentDate = $bookedAppointment->booking_date; // e.g., '2024-10-19'
        $appointmentTime = $bookedAppointment->booking_time; // e.g., '15:00'
        $endTime = Carbon::parse($appointmentTime)->addMinutes($appointmentTimeSlotDuration)->format('H:i'); // Adjust duration as needed

        // Combine date and time for start and end in ISO 8601 format
        $startDateTime = Carbon::parse("{$appointmentDate} {$appointmentTime}")->format('Ymd\THis');
        $endDateTime = Carbon::parse("{$appointmentDate} {$endTime}")->format('Ymd\THis');

        // Generate Google Calendar URL for the rescheduled time
        $googleCalendarUrl = "https://calendar.google.com/calendar/r/eventedit?text=Rescheduled+Appointment+with+Your+Business&dates={$startDateTime}/{$endDateTime}&details=Your+appointment+has+been+rescheduled";

        // Booking mail sent to customer
        $details = [
            'status' => "Rescheduled",
            'appointmentDate' => $appointmentDate,
            'appointmentTime' => Carbon::parse($appointmentTime)->format('H:i'),
            'googleCalendarUrl' => $googleCalendarUrl,
        ];

        try {
            Mail::to($bookedAppointment->email)->send(new \App\Mail\AppointmentMail($details));
        } catch (\Exception $e) {
            // Handle the exception (e.g., log the error)
        }

        return redirect()->back()->with('success', __('Appointment rescheduled successfully.'));
    }

    public function acceptAppointments(Request $request)
    {
        // Get appointment id
        $appointmentId = $request->query('id');

        $bookedAppointment = BookedAppointment::where('booked_appointment_id', $appointmentId)->first();
        $bookedAppointment->booking_status = 1;
        $bookedAppointment->save();

        // Prepare details for the email and Google Calendar
        $appointmentDate = $bookedAppointment->booking_date; // e.g., '2024-10-18'
        $appointmentTime = $bookedAppointment->booking_time; // e.g., '14:00'
        $appointmentTimeJson = explode(' - ', $appointmentTime);
        $endTime = Carbon::parse($appointmentTimeJson[1])->format('H:i'); // Adjust duration as needed

        // Combine date and time for start and end in ISO 8601 format
        $startDateTime = Carbon::parse("{$appointmentDate} {$appointmentTimeJson[0]}")->format('Ymd\THis');
        $endDateTime = Carbon::parse("{$appointmentDate} {$endTime}")->format('Ymd\THis');

        // Generate Google Calendar URL
        $googleCalendarUrl = "https://calendar.google.com/calendar/r/eventedit?text=Appointment+with+Your+Business&dates={$startDateTime}/{$endDateTime}&details=Your+appointment+is+confirmed";

        // Booking mail sent to customer
        $details = [
            'status' => "Confirmed",
            'appointmentDate' => $appointmentDate,
            'appointmentTime' => $appointmentTime,
            'googleCalendarUrl' => $googleCalendarUrl,
        ];

        try {
            Mail::to($bookedAppointment->email)->send(new \App\Mail\AppointmentMail($details));
        } catch (\Exception $e) {
            // Handle exception (e.g., log the error)
        }

        return redirect()->back()->with('success', trans('Booking accepted, and booking confirmation email sent to the customer.'));
    }

    // Reject appointment
    public function cancelAppointments(Request $request)
    {
        // Get appointment id
        $appointmentId = $request->query('id');

        $bookedAppointment = BookedAppointment::where('booked_appointment_id', $appointmentId)->first();
        $bookedAppointment->booking_status = -1;
        $bookedAppointment->save();

        // Booking mail sent to customer
        $details = [
            'status' => "Canceled",
            'appointmentDate' => $bookedAppointment->booking_date,
            'appointmentTime' => $bookedAppointment->booking_time
        ];

        try {
            Mail::to($bookedAppointment->email)->send(new \App\Mail\AppointmentMail($details));
        } catch (\Exception $e) {
        }

        return redirect()->back()->with('failed', trans('Booking canceled, and the customer has been notified via email'));
    }

    // Complete appointment
    public function completeAppointments(Request $request)
    {
        // Get appointment id
        $appointmentId = $request->query('id');

        $bookedAppointment = BookedAppointment::where('booked_appointment_id', $appointmentId)->first();
        $bookedAppointment->booking_status = 2;
        $bookedAppointment->save();

        // Booking mail sent to customer
        $details = [
            'status' => "Completed",
            'appointmentDate' => $bookedAppointment->booking_date,
            'appointmentTime' => $bookedAppointment->booking_time
        ];

        try {
            Mail::to($bookedAppointment->email)->send(new \App\Mail\AppointmentMail($details));
        } catch (\Exception $e) {
        }

        return redirect()->back()->with('success', trans('Booking completed, and the customer has been notified via email'));
    }
}

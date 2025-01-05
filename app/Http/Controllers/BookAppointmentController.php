<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Razorpay\Api\Card;
use App\BookedAppointment;
use App\CardAppointmentTime;
use Illuminate\Http\Request;
use App\Mail\AppointmentMail;
use Illuminate\Support\Facades\Mail;

class BookAppointmentController extends Controller
{
    // Book appointment
    public function bookAppointment(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'date' => 'required|date',
            'time_slot' => 'required|string',
            'price' => 'required'
        ]);

        // Save the appointment
        $bookAppointment = new BookedAppointment();
        $bookAppointment->booked_appointment_id = uniqid();
        $bookAppointment->card_id = $request->card;
        $bookAppointment->name = $request->name;
        $bookAppointment->email = $request->email;
        $bookAppointment->phone = $request->phone;
        $bookAppointment->notes = $request->notes;
        $bookAppointment->booking_date = $request->date;
        $bookAppointment->booking_time = $request->time_slot;
        $bookAppointment->total_price = $request->price;
        $bookAppointment->save();

        // Booking mail sent to customer
        $details = [
            'status' => "Pending",
            'appointmentDate' => $request->date,
            'appointmentTime' => $request->time_slot
        ];

        try {
            Mail::to($request->email)->send(new \App\Mail\AppointmentMail($details));
        } catch (\Exception $e) {
        }

        return response()->json(['success' => true, 'message' => 'Appointment booked successfully!']);
    }

    // Get day wise available time slots
    public function getAvailableTimeSlots(Request $request)
    {
        // Parse the input day into a Carbon date object
        $cardId = $request->card;
        $Date = Carbon::parse($request->choose_date)->addDay(); // Add one day
        $choosedDate = $Date->format('Y-m-d'); // Format the new date
        $day = Carbon::parse($request->day);

        // Retrieve already booked appointments for the specified card and date
        $bookedAppointments = BookedAppointment::where('card_id', $cardId)
            ->whereDate('booking_date', $choosedDate) // Use whereDate to match the date only
            ->whereIn('booking_status', [0, 1]) // Exclude booked and confirmed appointments
            ->pluck('booking_time'); // Pluck the booking times directly

        // Convert booked appointments to an array
        $excludedTimeSlots = $bookedAppointments->toArray(); // Now $excludedTimeSlots contains booked times

        // Retrieve available time slots, excluding already booked times
        $availableTimeSlots = CardAppointmentTime::where('card_id', $cardId)
            ->where('day', strtolower($day->format('l'))) // Get the day name (e.g., 'Friday')
            ->pluck('time_slots'); // Pluck time slots directly

        // Decode the available time slots JSON string into an array
        $availableTimeSlots = json_decode($availableTimeSlots[0], true); // Assuming there's only one row returned

        // Use array_diff to find available slots that are not in excluded slots
        $availableTimeSlots = array_diff($availableTimeSlots, $excludedTimeSlots);

        // Re-index the array if needed
        $availableTimeSlots = array_values($availableTimeSlots);

        // Optionally, if you need to encode it back to JSON
        $availableTimeSlotsJson = json_encode($availableTimeSlots);

        // Get price
        $price = CardAppointmentTime::where('card_id', $cardId)->first();

        return response()->json(['success' => true, 'available_time_slots' => $availableTimeSlotsJson, 'price' => $price->price]);
    }
}

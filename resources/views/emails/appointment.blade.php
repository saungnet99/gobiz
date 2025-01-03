<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>{{ __('New Appointment') }} {{ __($details['status']) }}</title>
    <style>
        /* Basic Tailwind-inspired styles for email */
        body {
            background-color: #f3f4f6;
            font-family: 'Arial', sans-serif;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header {
            background-color: #2563eb;
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }

        .content {
            padding: 20px;
        }

        .content p {
            margin-top: 16px;
            color: #4b5563;
        }

        .content p strong {
            color: #111827;
        }

        .btn {
            display: inline-block;
            margin-top: 24px;
            padding: 10px 20px;
            background-color: #2563eb;
            color: #ffffff;
            text-decoration: none;
            border-radius: 6px;
            text-align: center;
            font-weight: 600;
        }

        .btn:hover {
            background-color: #1e40af;
        }

        .footer {
            background-color: #f9fafb;
            text-align: center;
            padding: 10px;
            color: #6b7280;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>{{ __('Appointment') }} {{ __($details['status']) }}</h1>

            @if ($details['status'] == "Pending")
                <p>{{ __('Your appointment request is pending and will be confirmed shortly.') }}</p>
            @endif

            @if ($details['status'] == "Confirmed")
               <p>{{ __('Your appointment has been successfully scheduled!') }}</p> 
            @endif

            @if ($details['status'] == "Canceled")
               <p>{{ __('Your appointment has been canceled. Please contact us if you have any questions or need further assistance.') }}</p> 
            @endif

            @if ($details['status'] == "Completed")
               <p>{{ __('Your appointment has been completed. Thank you for choosing our service, and we look forward to serving you again!') }}</p> 
            @endif

            @if ($details['status'] == "Rescheduled")
               <p>{{ __('Your appointment has been rescheduled. Please contact us if you have any questions or need further assistance.') }}</p>
            @endif
        </div>

        @if ($details['status'] == "Confirmed" || $details['status'] == "Rescheduled")
            <div class="content">
                <p>{{ __('Hi,') }}</p>
                <p>{{ __('We are pleased to confirm your appointment with us. Here are the details:') }}</p>
                <p><strong>{{ __('Date') }}:</strong> {{ $details['appointmentDate'] }}</p>
                <p><strong>{{ __('Time') }}:</strong> {{ $details['appointmentTime'] }}</p>
                <p>{{ __('If you have any questions or need to reschedule or need further assistance, please don\'t hesitate to contact us.') }}</p>
                <p>{{ __('Thank you for choosing our service!') }}</p>

                @if (!empty($details['googleCalendarUrl']))
                    <p><strong>{{ __('You can add this appointment to your Google Calendar by clicking the link below:') }}</strong></p>
                    <a href="{{ $details['googleCalendarUrl'] }}" target="_blank" class="btn btn-primary">{{ __('Add to Google Calendar') }}</a>
                @endif
            </div>
        @endif
        
        <div class="footer">
            &copy; {{ ENV('APP_NAME') }}. {{ __('All rights reserved.') }}
        </div>
    </div>
</body>

</html>

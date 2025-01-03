@extends('user.layouts.index', ['header' => true, 'nav' => true, 'demo' => true, 'settings' => $settings])

@section('content')
    <div class="page-wrapper">
        <!-- Page title -->
        <div class="page-header d-print-none">
            <div class="container-xl">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <div class="page-pretitle">
                            {{ __('Overview') }}
                        </div>
                        <h2 class="page-title">
                            {{ __('Appointments') }}
                        </h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-body">
            <div class="container-xl">
                {{-- Failed --}}
                @if (Session::has('failed'))
                    <div class="alert alert-important alert-danger alert-dismissible mb-2" role="alert">
                        <div class="d-flex">
                            <div>
                                {{ Session::get('failed') }}
                            </div>
                        </div>
                        <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
                    </div>
                @endif

                {{-- Success --}}
                @if (Session::has('success'))
                    <div class="alert alert-important alert-success alert-dismissible mb-2" role="alert">
                        <div class="d-flex">
                            <div>
                                {{ Session::get('success') }}
                            </div>
                        </div>
                        <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
                    </div>
                @endif

                <div class="row row-deck row-cards">
                    <div class="col-sm-12 col-lg-12">
                        <div class="card">
                            <div class="table-responsive">
                                <table class="table table-vcenter card-table" id="table">
                                    <thead>
                                        <tr>
                                            <th>{{ __('#') }}</th>
                                            <th>{{ __('Created') }}</th>
                                            <th>{{ __('Customer Name') }}</th>
                                            <th>{{ __('Customer Email') }}</th>
                                            <th>{{ __('Customer Phone') }}</th>
                                            <th>{{ __('Appointment Date') }}</th>
                                            <th>{{ __('Appointment Time') }}</th>
                                            <th>{{ __('Notes') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            <th class="w-1">{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- Appointments --}}
                                        @foreach ($bookedAppointments as $appointment)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $appointment->created_at->diffForHumans() }}</td>
                                                <td><strong>{{ $appointment->name }}</strong></td>
                                                <td><strong><a
                                                            href="mailto:{{ $appointment->email }}">{{ $appointment->email }}</a></strong>
                                                </td>
                                                <td><strong><a
                                                            href="tel:{{ $appointment->phone }}">{{ $appointment->phone }}</a></strong>
                                                </td>
                                                <td><strong>{{ $appointment->booking_date }}</strong></td>
                                                <td><strong>{{ $appointment->booking_time }}</strong></td>
                                                <td><strong>{{ $appointment->notes ?? '-' }}</strong></td>
                                                <td>
                                                    @if ($appointment->booking_status == 0)
                                                        <span
                                                            class="badge bg-warning text-white text-white">{{ __('Pending') }}</span>
                                                    @endif
                                                    @if ($appointment->booking_status == 1)
                                                        <span
                                                            class="badge bg-primary text-white text-white">{{ __('Confirmed') }}</span>
                                                    @endif
                                                    @if ($appointment->booking_status == 2)
                                                        <span
                                                            class="badge bg-success text-white text-white">{{ __('Completed') }}</span>
                                                    @endif
                                                    @if ($appointment->booking_status == -1)
                                                        <span
                                                            class="badge bg-red text-white text-white">{{ __('Canceled') }}</span>
                                                    @endif
                                                </td>
                                                <td class="w-1">
                                                    <div class="btn-list flex-nowrap">
                                                        <div class="dropdown">
                                                            <button class="btn btn-icon btn-icon align-text-top"
                                                                data-bs-boundary="viewport" data-bs-toggle="dropdown"
                                                                aria-expanded="false">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-dots-vertical">
                                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                    <path d="M12 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                                                                    <path d="M12 19m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                                                                    <path d="M12 5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                                                                </svg>
                                                            </button>
                                                            <div class="actions actions dropdown-menu dropdown-menu-end"
                                                                style="">
                                                                {{-- Accept the appointment --}}
                                                                <a onclick="acceptAppointment(`{{ $appointment->booked_appointment_id }}`)"
                                                                    class="dropdown-item">
                                                                    {{ __('Update status') }}
                                                                </a>
                                                                {{-- Reschedule the appointment --}}
                                                                <a onclick="rescheduleAppointment(`{{ $appointment->booked_appointment_id }}`, `{{ $appointment->booking_date }}`)"
                                                                    class="dropdown-item">
                                                                    {{ __('Reschedule') }}
                                                                </a>
                                                                {{-- Complete the appointment --}}
                                                                <a onclick="completeAppointment(`{{ $appointment->booked_appointment_id }}`)"
                                                                    class="dropdown-item">
                                                                    {{ __('Complete') }}
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('user.includes.footer')
    </div>

    {{-- Accept or cancel appointment --}}
    <div class="modal modal-blur fade" id="acceptAppointmentModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="modal-title">{{ __('Are you sure?') }}</div>
                    <div id="accept_appointment_status"></div>
                </div>
                <div class="modal-footer justify-content-end">
                    <button type="button" class="btn btn-outline-primary me-auto"
                        data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <a class="btn btn-danger btn-icon" id="cancel_appointment_id">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-circle-x">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path
                                d="M17 3.34a10 10 0 1 1 -14.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 14.995 -8.336zm-6.489 5.8a1 1 0 0 0 -1.218 1.567l1.292 1.293l-1.292 1.293l-.083 .094a1 1 0 0 0 1.497 1.32l1.293 -1.292l1.293 1.292l.094 .083a1 1 0 0 0 1.32 -1.497l-1.292 -1.293l1.292 -1.293l.083 -.094a1 1 0 0 0 -1.497 -1.32l-1.293 1.292l-1.293 -1.292l-.094 -.083z" />
                        </svg>
                    </a>
                    <a class="btn btn-primary btn-icon" id="accept_appointment_id">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-circle-check">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path
                                d="M17 3.34a10 10 0 1 1 -14.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 14.995 -8.336zm-1.293 5.953a1 1 0 0 0 -1.32 -.083l-.094 .083l-3.293 3.292l-1.293 -1.292l-.094 -.083a1 1 0 0 0 -1.403 1.403l.083 .094l2 2l.094 .083a1 1 0 0 0 1.226 0l.094 -.083l4 -4l.083 -.094a1 1 0 0 0 -.083 -1.32z" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>


    {{-- Complete appointment --}}
    <div class="modal modal-blur fade" id="completeAppointmentModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="modal-title">{{ __('Are you sure?') }}</div>
                    <div id="complete_appointment_status"></div>
                </div>
                <div class="modal-footer justify-content-end">
                    <button type="button" class="btn btn-outline-primary me-auto"
                        data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <a class="btn btn-primary" id="complete_appointment_id">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-circle-check">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path
                                d="M17 3.34a10 10 0 1 1 -14.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 14.995 -8.336zm-1.293 5.953a1 1 0 0 0 -1.32 -.083l-.094 .083l-3.293 3.292l-1.293 -1.292l-.094 -.083a1 1 0 0 0 -1.403 1.403l.083 .094l2 2l.094 .083a1 1 0 0 0 1.226 0l.094 -.083l4 -4l.083 -.094a1 1 0 0 0 -.083 -1.32z" />
                        </svg>
                        <span>{{ __('Complete') }}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Reschedule appointment --}}
    <div class="modal modal-blur fade" id="rescheduleAppointmentModal" tabindex="-1" role="dialog"
        aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                {{-- Reschedule date and time --}}
                <form action="{{ route('user.reschedule.appointment') }}" method="post" class="card">
                    @csrf
                    <div class="modal-header">
                        <div class="modal-title">{{ __('Reschedule appointment') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <input type="hidden" class="form-control" name="booked_appointment_id"
                                id="booked_appointment_id">
                            <label class="form-label">{{ __('Date') }}</label>
                            <div class="input-group mb-2">
                                <input type="date" name="date" id="date" class="form-control"
                                    placeholder="{{ __('Date') }}" required>
                                <span class="input-group-text">
                                    {{ __('Date') }}
                                </span>
                            </div>
                            <label class="form-label">{{ __('Time') }}</label>
                            <div class="input-group mb-2">
                                <input type="time" name="time" id="time" class="form-control"
                                    placeholder="{{ __('Time') }}" required>
                                <span class="input-group-text">
                                    {{ __('Time') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-end">
                        <button type="button" class="btn btn-outline-primary me-auto"
                            data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-primary btn-sm">{{ __('Reschedule') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Custom JS --}}
@section('scripts')
    <script>
        // Accept appointment
        function acceptAppointment(id) {
            "use strict";

            $("#acceptAppointmentModal").modal("show");
            var accept_appointment_status = document.getElementById("accept_appointment_status");
            accept_appointment_status.innerHTML = "<?php echo __('If you proceed, you will accept this appointment.'); ?>"
            var accept_appointment_link = document.getElementById("accept_appointment_id");
            accept_appointment_link.getAttribute("href");
            accept_appointment_link.setAttribute("href", "{{ route('user.accept.appointment') }}?id=" + id);
            var cancel_appointment_link = document.getElementById("cancel_appointment_id");
            cancel_appointment_link.getAttribute("href");
            cancel_appointment_link.setAttribute("href", "{{ route('user.cancel.appointment') }}?id=" + id);
        }

        // Complete appointment
        function completeAppointment(id) {
            "use strict";

            $("#completeAppointmentModal").modal("show");
            var complete_appointment_status = document.getElementById("complete_appointment_status");
            complete_appointment_status.innerHTML = "<?php echo __('If you proceed, you will complete this appointment.'); ?>"
            var complete_appointment_link = document.getElementById("complete_appointment_id");
            complete_appointment_link.getAttribute("href");
            complete_appointment_link.setAttribute("href", "{{ route('user.complete.appointment') }}?id=" + id);
        }

        // Reschedule appointment
        function rescheduleAppointment(booked_appointment_id, date) {
            "use strict";

            // Convert the date string into a Date object
            const dateObj = new Date(date);

            // Format the date to 'yyyy-mm-dd'
            const formattedDate = formatDateToYMD(dateObj);

            // Show the modal
            $("#rescheduleAppointmentModal").modal("show");

            // Get the input fields inside the modal
            var bookedAppointmentInput = document.getElementById("booked_appointment_id");
            var dateInput = document.getElementById("date");

            // Set the values of the input fields
            bookedAppointmentInput.value = booked_appointment_id;
            dateInput.value = formattedDate;
        }

        // Helper function to format date to 'dd mm yyyy'
        function formatDateToYMD(date) {
            const y = date.getFullYear();
            const m = date.getMonth() + 1; // Months are zero-indexed in JavaScript (0-11)
            const d = date.getDate();

            // Pad day and month with leading zeros if necessary
            const day = d < 10 ? '0' + d : d;
            const month = m < 10 ? '0' + m : m;

            return `${y}-${month}-${day}`;
        }
    </script>
@endsection
@endsection

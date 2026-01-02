<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Training Calendar</h2>
        <br>
        <h3 class="font-semibold text-l text-gray-800 leading-tight">Team filter:</h3>
        <select id="teamFilter" class="form-control mb-3">
            <option value="">All Teams</option>
            @foreach($komandas as $komanda)
                <option value="{{ $komanda->id }}">{{ $komanda->vecums }}</option>
            @endforeach
        </select>
        @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
    </x-slot>
    <div class="container">

        <!-- Add Event Button -->
         @can('is-coach')
        <div class="text-center mb-3">
            <button id="addEventButton" class="btn btn-primary">Add Event</button>
        </div>
        @endcan
        <!-- Calendar -->
        <div id="calendar"></div>
    </div>

    <!-- Modals for adding/editing events -->
    <!-- Add Event Modal -->
    <div class="modal fade" id="addEventModal" tabindex="-1" role="dialog" aria-labelledby="addEventModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addEventModalLabel">Add training</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="addEventErrors" class="alert alert-danger" style="display: none;">
                        <ul class="mb-0"></ul>
                    </div>
                    <form id="addEventForm">
                        <div class="form-group mb-3">
                            <label for="newEventApraksts">Description</label>
                            <input type="text" class="form-control" id="newEventApraksts" name="apraksts" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="newEventVieta">Place</label>
                            <input type="text" class="form-control" id="newEventVieta" name="vieta">
                        </div>
                        <div class="form-group mb-3">
                            <label for="newEventLaiks">Time</label>
                            {{-- Changed input type to "time" --}}
                            <input type="text" class="form-control timepicker" id="newEventLaiks" name="laiks" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="newEventSakumaDatums">Date</label>
                            <input type="date" class="form-control" id="newEventSakumaDatums" name="sakuma_datums" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="newEventKomandas">Team</label>
                            <select class="form-control" id="newEventKomandas" name="komandas_id" required>
                                <option value="">Select Team</option>
                                @foreach($komandas as $komanda)
                                    <option value="{{ $komanda->id }}">{{ $komanda->vecums }}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-end">
                    <button type="button" class="px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150" id="saveNewEvent">Save training</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Event Details/Edit Modal -->
    <div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventModalLabel">Event Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{-- This div will display server-side (Laravel) validation errors --}}
                    <div id="eventErrors" class="alert alert-danger" style="display: none;">
                        <ul class="mb-0"></ul>
                    </div>

                    <form id="eventForm">
                        <input type="hidden" id="eventId">

                        <!-- Apraksts (Title/Description) -->
                        <div class="form-group">
                            <label for="eventApraksts">Apraksts (Title/Description)</label>
                            @can('is-coach')
                                <input type="text" class="form-control" id="eventApraksts" name="apraksts" required>
                            @else
                                <p id="eventAprakstsDisplay"></p>
                            @endcan
                        </div>

                        <!-- Laiks (Time) -->
                        <div class="form-group">
                            <label for="eventLaiks">Laiks (Time)</label>
                            @can('is-coach')
                                {{-- Changed input type to "time" --}}
                                <input type="text" class="form-control timepicker" id="eventLaiks" name="laiks">
                            @else
                                <p id="eventLaiksDisplay"></p>
                            @endcan
                        </div>

                        <!-- Vieta (Place) -->
                        <div class="form-group">
                            <label for="eventVieta">Vieta (Place)</label>
                            @can('is-coach')
                                <input type="text" class="form-control" id="eventVieta" name="vieta">
                            @else
                                <p id="eventVietaDisplay"></p>
                            @endcan
                        </div>

                        <!-- Komandas (Team) -->
                        <div class="form-group">
                            <label for="eventKomandas">Komandas (Team)</label>
                            @can('is-coach')
                                <select class="form-control" id="eventKomandas" name="komandas_id" required>
                                    <option value="">Select Team</option>
                                    @foreach($komandas as $komanda)
                                        <option value="{{ $komanda->id }}">{{ $komanda->vecums }}</option>
                                    @endforeach
                                </select>
                            @else
                                <p id="eventKomandasDisplay"></p>
                            @endcan
                        </div>

                        <!-- Sakuma Datums (Start Date) -->
                        <div class="form-group">
                            <label for="eventSakumaDatums">Sakuma Datums (Start Date)</label>
                            @can('is-coach')
                                <input type="date" class="form-control" id="eventSakumaDatums" name="sakuma_datums" required>
                            @else
                                <p id="eventSakumaDatumsDisplay"></p>
                            @endcan
                        </div>
                        
                    </form> {{-- End of eventForm --}}
                    <!-- Attendance form -->
                        <div id="attendanceFormContainer" style="display: none; margin-top: 10px;">
                            <form method="POST" action="{{ route('kalendars.savePlayers', [ 'teamslug' => $komandas[0]->vecums ?? 'default-slug']) }}"> {{-- TEMPORARY FIX: using first team slug, needs proper context --}}
                                @csrf
                                @method('PATCH')
                                <input type="hidden" id="attendanceEventId" name="event_id" value="">
                                <input type="hidden" name="redirect_url" value="{{ url()->current() }}">
                                <h4>Select players who attended this game:</h4>
                                    @foreach($players as $player)
                                        <div class="form-check player-checkbox" data-commanda-id="{{ $player->komandas_id }}">
                                            <input class="form-check-input" type="checkbox" name="attendance[]" value="{{ $player->id }}" id="player-{{ $player->id }}">
                                            <label class="form-check-label" for="player-{{ $player->id }}">
                                                {{ $player->name }} {{ $player->surname }} (#{{ $player->numurs }})
                                            </label>
                                        </div>
                                    @endforeach
                                <button type="submit" class="px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">Save Attendance</button>
                            </form>
                        </div>
                </div>
                <div class="modal-footer">
                    @can('is-coach')
                        <button type="button" class="px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150" id="markAttendance">Mark Attendance</button>
                        <button type="button" class="px-4 py-2 bg-red-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150" id="deleteEvent">Delete</button>
                        <button type="button" class="px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150" id="saveEvent">Save changes</button>
                    @endcan
                </div>
            </div>
        </div>
    </div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- Global Variables and Setup ---
    var SITEURL = "{{ url('/') }}";
    var calendarEl = document.getElementById('calendar');
    var calendar;
    const teamColors = {}; // Store team colors mapping

    flatpickr(".timepicker", {
        enableTime: true,    // Enable time picker
        noCalendar: true,    // Disable calendar (only show time)
        dateFormat: "H:i",   // 24-hour format (e.g., "13:30")
        time_24hr: true,     // Force 24-hour mode in the picker UI
        minuteIncrement: 1   // Allow minute selection, default is 5
    });

    // Function to generate a non-dark, distinct color for teams
    function generateNonDarkColor() {
        const hue = Math.floor(Math.random() * 360);
        const saturation = Math.floor(Math.random() * (100 - 50) + 50); // Saturation between 50 and 100
        const lightness = Math.floor(Math.random() * (90 - 40) + 40);   // Lightness between 40 and 90
        return `hsl(${hue}, ${saturation}%, ${lightness}%)`;
    }

    // Function to display messages (assumes Toastr library is used)
    function displayMessage(message, type = 'success') {
        if (typeof toastr !== 'undefined') {
            if (type === 'success') {
                toastr.success(message, 'Event');
            } else if (type === 'error') {
                toastr.error(message, 'Error');
            }
        } else {
            console.log(`Message (${type}): ${message}`);
        }
    }

    // Function to get team name by ID from the global komandas array
    function getTeamName(teamId) {
        var komandas = @json($komandas); // Get teams from blade
        for (var i = 0; i < komandas.length; i++) {
            if (komandas[i].id == teamId) {
                return komandas[i].vecums;
            }
        }
        return 'N/A';
    }

    // --- FullCalendar Initialization ---
    calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek', // Changed to timeGridWeek for time slots
        editable: true,
        // displayEventTime: false, // Removed or set to true, as timeGrid views inherently display time
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay' // Added timeGrid views to buttons
        },
        // --- NEW/MODIFIED: Time-specific FullCalendar Options ---
        slotMinTime: '06:00:00', // Start time for the calendar grid
        slotMaxTime: '24:00:00', // End time for the calendar grid (use '00:00:00' for midnight next day if needed)
        slotLabelFormat: { // Format for the time axis labels
            hour: 'numeric',
            minute: '2-digit',
            omitZeroMinute: false,
            meridiem: false, // Use 24-hour format
            hour12: false // Ensure 24-hour format
        },
        eventTimeFormat: { // Format for time displayed on events
            hour: 'numeric',
            minute: '2-digit',
            meridiem: false,
            hour12: false
        },
        // --- END NEW/MODIFIED ---

        // Fetch events from the server
        events: function(fetchInfo, successCallback, failureCallback) {
            $.ajax({
                url: SITEURL + "/kalendars",
                data: {
                    start: fetchInfo.startStr,
                    end: fetchInfo.endStr,
                    komandas_id: $('#teamFilter').val() // Pass selected team filter
                },
                dataType: 'json',
                success: function(response) {                   
                    const events = response.map(event => {
                        // Assign a consistent color to each team
                        if (!teamColors[event.extendedProps.komandas_id]) {
                            teamColors[event.extendedProps.komandas_id] = generateNonDarkColor();
                        }
                        const eventDate = new Date(event.start);
                        const [hours, minutes] = event.extendedProps.laiks.split(':');
                        eventDate.setHours(parseInt(hours), parseInt(minutes));
                        return {
                            id: event.id,
                            title: event.extendedProps.vieta,
                            start: eventDate.toISOString(), 
                            allDay: false, 
                            backgroundColor: teamColors[event.extendedProps.komandas_id],
                            extendedProps: {
                                apraksts: event.extendedProps.apraksts,
                                laiks: event.extendedProps.laiks,
                                vieta: event.extendedProps.vieta,
                                komandas_id: event.extendedProps.komandas_id
                            }
                        };
                    });
                    successCallback(events);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    displayMessage('Error fetching events: ' + errorThrown, 'error');
                    console.error('AJAX Error:', textStatus, errorThrown, jqXHR);
                    failureCallback(errorThrown);
                }
            });
        },

        // Handle clicking on an event
        eventClick: function(info) {
            handleEventClick(info.event);
        },

        // Handle event drag-and-drop
        eventDrop: function(info) {
            var event = info.event;
            // The event.start and event.end objects already contain the updated date and time
            $.ajax({
                url: SITEURL + '/kalendars/ajax',
                data: {
                    id: event.id,
                    apraksts: event.extendedProps.apraksts,
                    // Use event.start.toISOString() to get the full datetime string
                    sakuma_datums: event.start.toISOString().split('T')[0], // Extract date
                    laiks: event.start.toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit', hour12: false }), // Extract time HH:MM
                    komandas_id: event.extendedProps.komandas_id,
                    vieta: event.extendedProps.vieta,
                    type: 'update'
                    // beigu_datums will be inferred on the server for now (start + 1hr)
                },
                type: "POST", // Laravel expects POST for AJAX
                success: function() {
                    displayMessage("Event updated Successfully");
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    displayMessage('Error updating event: ' + errorThrown, 'error');
                    console.error('AJAX Error:', textStatus, errorThrown, jqXHR);
                    info.revert(); // Revert the event's position if there's an error
                }
            });
        },

        // Apply colors to events after they are mounted
        eventDidMount: function (info) {
            var event = info.event;
            var element = info.el;
            var komandas_id = event.extendedProps?.komandas_id;

            if (komandas_id && teamColors[komandas_id]) {
                element.style.backgroundColor = teamColors[komandas_id];
                element.style.borderColor = teamColors[komandas_id];
                element.style.color = 'black'; // Set text color for contrast
            }
        },
    });

    calendar.render(); // Render the calendar initially

    // --- Event Handlers for Filtering and Modals ---

    // Filter events based on team selection
    $('#teamFilter').change(function() {
        calendar.refetchEvents();
    });

    // Handle clicking on an event to view/edit
    function handleEventClick(event) {
        // Reset attendance form state
        $('#attendanceFormContainer').hide();
        // Clear previous errors from edit modal if any
        $('#eventErrors').hide().find('ul').empty();

        $('#eventId').val(event.id);
        $('#attendanceEventId').val(event.id);
        $('#eventModalLabel').text(event.extendedProps.vieta);
        // Format date and time for display and input
        var eventStart = new Date(event.start); // FullCalendar event.start is already a Date object
        var formattedDateDisplay = eventStart.getDate() + '. ' + eventStart.toLocaleString('default', { month: 'long' }) + ' ' + eventStart.getFullYear() + '.';
        var formattedDateInput = eventStart.toISOString().split('T')[0]; // YYYY-MM-DD for input type="date"
        var timeParts = event.extendedProps.laiks.split(':');
        var formattedTimeInput = timeParts[0] + ':' + timeParts[1];

        // Populate modal fields based on user role
        @can('is-coach')
            $('#eventApraksts').val(event.extendedProps.apraksts);
            $('#eventLaiks').val(formattedTimeInput); // Set time input
            $('#eventVieta').val(event.title);
            $('#eventSakumaDatums').val(formattedDateInput); // Set date input
            $('#eventKomandas').val(event.extendedProps.komandas_id || '');
        @else
            $('#eventAprakstsDisplay').text(event.extendedProps.apraksts || 'N/A');
            $('#eventLaiksDisplay').text(formattedTimeInput); // Display time
            $('#eventVietaDisplay').text(event.title);
            $('#eventSakumaDatumsDisplay').text(formattedDateDisplay); // Display date
            $('#eventKomandasDisplay').text(getTeamName(event.extendedProps.komandas_id));
        @endcan

        // Populate attendance form with team-specific players
        var teamId = event.extendedProps.komandas_id || '';
        $('#teamId').val(teamId); // Set hidden teamId for attendance form

        $('.player-checkbox').each(function() {
            var playerTeamId = $(this).data('commanda-id');
            // Show/hide player checkboxes based on selected event's team
            if (playerTeamId == teamId) {
                $(this).show();
            } else {
                $(this).hide();
            }
            // Uncheck all players by default when opening event details
            $(this).find('input[type="checkbox"]').prop('checked', false);
        });

        $('#eventModal').modal('show'); // Show the event details modal
    }

    // Save changes to existing event
    $('#saveEvent').click(function(e) {
        e.preventDefault(); // Prevent default button action

        const form = $('#eventForm')[0];

        // Clear all previous server-side errors
        $('#eventErrors').hide().find('ul').empty();

        // 1. Trigger native HTML5 validation
        if (!form.checkValidity()) {
            form.reportValidity(); // Show native browser validation messages
            return; // Stop if client-side validation fails
        }

        // Collect form data
        var id = $('#eventId').val();
        var apraksts = $('#eventApraksts').val();
        var laiks = $('#eventLaiks').val();
        var vieta = $('#eventVieta').val();
        var komandas_id = $('#eventKomandas').val();
        var sakuma_datums = $('#eventSakumaDatums').val();

        $.ajax({
            url: SITEURL + '/kalendars/ajax',
            data: {
                id: id,
                apraksts: apraksts,
                laiks: laiks,
                vieta: vieta,
                komandas_id: komandas_id,
                sakuma_datums: sakuma_datums,
                type: 'update'
            },
            type: "POST", // Laravel expects POST for AJAX
            success: function() {
                displayMessage("Event updated Successfully");
                $('#eventModal').modal('hide');
                calendar.refetchEvents(); // Refresh calendar to show changes
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Handle Laravel validation errors for update
                if (jqXHR.status === 422) {
                    var errors = jqXHR.responseJSON.errors;
                    $('#eventErrors').show().find('ul').empty();
                    $.each(errors, function(key, value) {
                        $('#eventErrors').find('ul').append('<li>' + value + '</li>');
                    });
                } else {
                    displayMessage('Error updating event: ' + errorThrown, 'error');
                    console.error('AJAX Error:', textStatus, errorThrown, jqXHR);
                }
            }
        });
    });

    // Handle 'Mark Attendance' button click to toggle attendance form
    $('#markAttendance').on('click', function() {
        $('#attendanceFormContainer').toggle(); // Show/hide the attendance form
    });

    // Add new event
    $('#saveNewEvent').click(function(e) {
        e.preventDefault(); // Prevent default button action

        const form = $('#addEventForm')[0];

        // Clear all previous server-side errors
        $('#addEventErrors').hide().find('ul').empty();

        // 1. Trigger native HTML5 validation
        if (!form.checkValidity()) {
            form.reportValidity(); // Show native browser validation messages
            return; // Stop if client-side validation fails
        }

        // Collect form data
        var apraksts = $('#newEventApraksts').val();
        var laiks = $('#newEventLaiks').val();
        var vieta = $('#newEventVieta').val();
        var komandas_id = $('#newEventKomandas').val();
        var sakuma_datums = $('#newEventSakumaDatums').val();

        $.ajax({
            url: SITEURL + "/kalendars/ajax",
            data: {
                apraksts: apraksts,
                laiks: laiks,
                vieta: vieta,
                komandas_id: komandas_id,
                sakuma_datums: sakuma_datums,
                type: 'add'
            },
            type: "POST", // Laravel expects POST for AJAX
            success: function() {
                displayMessage("Event Added Successfully");
                $('#addEventModal').modal('hide');
                $('#addEventForm')[0].reset(); // Reset the form fields
                calendar.refetchEvents(); // Refresh calendar to show new event
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Handle Laravel validation errors for add
                if (jqXHR.status === 422) {
                    var errors = jqXHR.responseJSON.errors;
                    $('#addEventErrors').show().find('ul').empty();
                    $.each(errors, function(key, value) {
                        $('#addEventErrors').find('ul').append('<li>' + value + '</li>');
                    });
                } else {
                    displayMessage('Error adding event: ' + errorThrown, 'error');
                    console.error('AJAX Error:', textStatus, errorThrown, jqXHR);
                }
            }
        });
    });

    // Delete event
    $('#deleteEvent').click(function() {
        var id = $('#eventId').val();
            $.ajax({
                url: SITEURL + "/kalendars/ajax",
                data: {
                    id: id,
                    type: 'delete'
                },
                type: "POST", // Laravel expects POST for AJAX
                success: function() {
                    displayMessage("Event deleted Successfully");
                    calendar.getEventById(id).remove(); // Remove event from calendar
                    $('#eventModal').modal('hide');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    displayMessage('Error deleting event: ' + errorThrown, 'error');
                    console.error('AJAX Error:', textStatus, errorThrown, jqXHR);
                }
            });
    });

    // Show modal for adding new event
    $('#addEventButton').click(function() {
        $('#addEventForm')[0].reset(); // Reset form fields
        // Clear previous errors
        $('#addEventErrors').hide().find('ul').empty();
        // Set default date to today and time to current time (or 09:00 for convenience)
        var today = new Date();
        var todayDate = today.toISOString().split('T')[0];
        // Set a default time like 09:00 or current time for convenience
        var defaultTime = '09:00'; 
        // var defaultTime = today.toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit', hour12: false });

        $('#newEventSakumaDatums').val(todayDate);
        $('#newEventLaiks').val(defaultTime); // Set default time
        $('#addEventModal').modal('show'); // Show the modal
    });

    // Clear previous errors when edit modal is opened
    $('#eventModal').on('show.bs.modal', function () {
        $('#eventErrors').hide().find('ul').empty();
    });


    // Set up CSRF token for all AJAX requests
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });
});
</script>
@endpush
</x-app-layout>
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
    </x-slot>
    <div class="container">

        <!-- Add Event Button -->
         @can('is-coach-or-owner')
        <div class="text-right mb-3">
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
                    <h5 class="modal-title" id="addEventModalLabel">Add New Event</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addEventForm">
                        <div class="form-group">
                            <label for="newEventApraksts">Apraksts (Title/Description)</label>
                            <input type="text" class="form-control" id="newEventApraksts" required>
                        </div>
                        <div class="form-group">
                            <label for="newEventLaiks">Laiks (Time)</label>
                            <input type="text" class="form-control" id="newEventLaiks" placeholder="e.g., 10:00" required>
                        </div>
                        <div class="form-group">
                            <label for="newEventVieta">Vieta (Place)</label>
                            <input type="text" class="form-control" id="newEventVieta">
                        </div>
                        <div class="form-group">
                            <label for="newEventKomandas">Komandas (Team)</label>
                            <select class="form-control" id="newEventKomandas" required>
                                <option value="">Select Team</option>
                                @foreach($komandas as $komanda)
                                    <option value="{{ $komanda->id }}">{{ $komanda->vecums }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="newEventSakumaDatums">Sakuma Datums (Start Date)</label>
                            <input type="date" class="form-control" id="newEventSakumaDatums" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveNewEvent">Save Event</button>
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
                    <form id="eventForm">
                        <input type="hidden" id="eventId">

                        <!-- Apraksts (Title/Description) -->
                        <div class="form-group">
                            <label for="eventApraksts">Apraksts (Title/Description)</label>
                            @can('is-coach-or-owner')
                                <input type="text" class="form-control" id="eventApraksts" required>
                            @else
                                <p id="eventAprakstsDisplay"></p>
                            @endcan
                        </div>

                        <!-- Laiks (Time) -->
                        <div class="form-group">
                            <label for="eventLaiks">Laiks (Time)</label>
                            @can('is-coach-or-owner')
                                <input type="text" class="form-control" id="eventLaiks" placeholder="e.g., 10:00">
                            @else
                                <p id="eventLaiksDisplay"></p>
                            @endcan
                        </div>

                        <!-- Vieta (Place) -->
                        <div class="form-group">
                            <label for="eventVieta">Vieta (Place)</label>
                            @can('is-coach-or-owner')
                                <input type="text" class="form-control" id="eventVieta">
                            @else
                                <p id="eventVietaDisplay"></p>
                            @endcan
                        </div>

                        <!-- Komandas (Team) -->
                        <div class="form-group">
                            <label for="eventKomandas">Komandas (Team)</label>
                            @can('is-coach-or-owner')
                                <select class="form-control" id="eventKomandas" required>
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
                            @can('is-coach-or-owner')
                                <input type="date" class="form-control" id="eventSakumaDatums" value = {formattedDateInput} required>
                            @else
                                <p id="eventSakumaDatumsDisplay"></p>
                            @endcan
                        </div>
                        <!-- Attendance form -->
                        <div id="attendanceFormContainer" style="display: none; margin-top: 10px;">
                            <form method="POST" action="{{ route('kalendars.savePlayers', [ 'teamslug' => $komanda->vecums]) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" id="teamId" name="teamId" value="">
                                <input type="hidden" name="redirect_url" value="{{ url()->current() }}">
                                <h4>Select players who attended this game:</h4>
                                    @foreach($players as $player)
                                        <div class="form-check player-checkbox" data-commanda-id="{{ $player->komanda_id }}">
                                            <input class="form-check-input" type="checkbox" name="attendance[]" value="{{ $player->id }}" id="player-{{ $player->id }}">
                                            <label class="form-check-label" for="player-{{ $player->id }}">
                                                {{ $player->user->name }} {{ $player->user->surname }} (#{{ $player->numurs }})
                                            </label>
                                        </div>
                                    @endforeach
                                <button type="submit" class="btn btn-success mt-3">Save Attendance</button>
                            </form>
                        </div>
                <div class="modal-footer">
                    
                    @can('is-coach-or-owner')
                        <button type="button" class="btn btn-primary" id="markAttendance">Mark Attendance</button>
                        <button type="button" class="btn btn-danger" id="deleteEvent">Delete</button>
                        <button type="button" class="btn btn-primary" id="saveEvent">Save changes</button>
                    @endcan
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    $('#markAttendance').on('click', function() {
        $('#attendanceFormContainer').toggle(); // Show/hide the attendance form
    });

    document.addEventListener('DOMContentLoaded', function() {
    var SITEURL = "{{ url('/') }}";
    var calendarEl = document.getElementById('calendar');
    var calendar;

    // Store team colors mapping
    function generateNonDarkColor() {
        // Generate a random hue (0-360)
        const hue = Math.floor(Math.random() * 360);

        // Define saturation and lightness ranges to avoid dark colors
        const saturation = Math.floor(Math.random() * (100 - 50) + 50); // Saturation between 50 and 100
        const lightness = Math.floor(Math.random() * (90 - 40) + 40);   // Lightness between 40 and 90

        return `hsl(${hue}, ${saturation}%, ${lightness}%)`;
    }

    const teamColors = {};

    calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        editable: true,
        displayEventTime: false,

         events: function(fetchInfo, successCallback, failureCallback) {
            $.ajax({
                url: SITEURL + "/kalendars",
                data: {
                    start: fetchInfo.startStr,
                    end: fetchInfo.endStr,
                    komandas_id: $('#teamFilter').val()
                },
                dataType: 'json',
                success: function(response) {                   
                  const events = response.map(event => {
                        if (!teamColors[event.extendedProps.komandas_id]) {
                            teamColors[event.extendedProps.komandas_id] = generateNonDarkColor();
                        }

                        return {
                            id: event.id,
                            title: event.title,
                            start: event.start,
                            backgroundColor: teamColors[event.extendedProps.komandas_id],
                            extendedProps: {
                                laiks: event.extendedProps.laiks,
                                vieta: event.extendedProps.vieta,
                                komandas_id: event.extendedProps.komandas_id
                            }
                        };
                    });
                    successCallback(events);
                },
            });
        },

        eventClick: function(info) {
            handleEventClick(info.event);
        },
        eventDrop: function(info) {
            var event = info.event;
            $.ajax({
                url: SITEURL + '/kalendars/ajax',
                data: {
                    apraksts: event.title,
                    sakuma_datums: event.startStr,
                    komandas_id: event.extendedProps.komandas_id,
                    vieta: event.extendedProps.vieta,
                    id: event.id,
                    laiks: event.extendedProps.laiks,
                    type: 'update'
                },
                type: "POST",
                success: function() {
                    displayMessage("Event Updated Successfully");
                }
            });
        },
         eventDidMount: function (info) {
            var event = info.event;
            var element = info.el; // Get the jQuery element for the event
            var komandas_id = event.extendedProps?.komandas_id;

            if (komandas_id && teamColors[komandas_id]) {
                element.style.backgroundColor = teamColors[komandas_id]; // Set background color
                element.style.borderColor = teamColors[komandas_id]; // Optional: Set border color
                element.style.color = 'black'; // Optional: Set text color for contrast
            }
        },
    });

    calendar.render();

    // Filter events based on team
    $('#teamFilter').change(function() {
        calendar.refetchEvents();
    });

    // Handle clicking on an event to view/edit
    function handleEventClick(event) {
        $('#eventId').val(event.id);
        var sakumaDatums = new Date(event.startStr);
            var day = sakumaDatums.getDate();
            var month = sakumaDatums.toLocaleString('default', { month: 'long' }); // Month name
            var year = sakumaDatums.getFullYear();
            var formattedDate = day + '. ' + month + ' ' + year + '. ' + ' year';

            var yyyy = sakumaDatums.getFullYear();
            var MM = String(sakumaDatums.getMonth() + 1).padStart(2, '0'); // Month is 0-indexed
            var dd = String(sakumaDatums.getDate()).padStart(2, '0');
            var formattedDateInput = yyyy + '-' + MM + '-' + dd;
        @can('is-coach-or-owner')
            $('#eventApraksts').val(event.title);
            $('#eventLaiks').val(event.extendedProps.laiks || '');
            $('#eventVieta').val(event.extendedProps.vieta || '');
            $('#eventSakumaDatums').val(formattedDateInput);
            $('#eventKomandas').val(event.extendedProps.komandas_id || '');
            
        @else
            $('#eventAprakstsDisplay').text(event.title);
            $('#eventLaiksDisplay').text(event.extendedProps.laiks || 'N/A');
            $('#eventVietaDisplay').text(event.extendedProps.vieta || 'N/A');
            $('#eventSakumaDatumsDisplay').text(formattedDate);
            $('#eventKomandasDisplay').text(getTeamName(event.extendedProps.komandas_id) || 'N/A'); // Corrected line
        @endcan
        var teamId = event.extendedProps.komandas_id || '';
            $('#teamId').val(teamId);
            $('.player-checkbox').each(function() {
            var playerTeamId = $(this).data('commanda-id');
            if (playerTeamId == teamId) {
                $(this).show(); // Show if matches
            } else {
                $(this).hide(); // Hide if not
            }
        });
        $('#eventModal').modal('show');

    }
    function getTeamName(teamId) {
            var teams = @json($komandas); // Get teams from blade
            for (var i = 0; i < teams.length; i++) {
                if (teams[i].id == teamId) {
                    return teams[i].vecums;
                }
            }
            return null;
        }
    // Save changes to existing event
    $('#saveEvent').click(function() {
        var id = $('#eventId').val();
        var apraksts = $('#eventApraksts').val();
        var laiks = $('#eventLaiks').val();
        var vieta = $('#eventVieta').val();
        var komandas_id = $('#eventKomandas').val();
        var datums = $('#eventSakumaDatums').val(); // start date

        $.ajax({
            url: SITEURL + '/kalendars/ajax',
            data: {
                id: id,
                apraksts: apraksts,
                laiks: laiks,
                vieta: vieta,
                komandas_id: komandas_id,
                sakuma_datums: datums,
                type: 'update'
            },
            type: "POST",
            success: function() {
                displayMessage("Event Updated Successfully");
                $('#eventModal').modal('hide');
                calendar.refetchEvents();
            }
        });
    });

    // Add new event
    $('#saveNewEvent').click(function() {
        var apraksts = $('#newEventApraksts').val();      // map to 'apraksts'
        var laiks = $('#newEventLaiks').val();
        var vieta = $('#newEventVieta').val();                                // Add if you have a place input
        var komandas_id = $('#newEventKomandas').val();  // map to 'komandas_id'
        var datums = $('#newEventSakumaDatums').val();

        if (apraksts && datums) {
            $.ajax({
                url: SITEURL + "/kalendars/ajax",
                data: {
                    apraksts: apraksts,
                    laiks: laiks,
                    vieta: vieta,
                    komandas_id: komandas_id,
                    sakuma_datums: datums,
                    type: 'add'
                },
                type: "POST",
                success: function() {
                    displayMessage("Event Added Successfully");
                    $('#addEventModal').modal('hide');
                    $('#addEventForm')[0].reset();
                    calendar.refetchEvents();
                }
            });
        } else {
            displayMessage("Please fill all required fields");
        }
    });

    // Delete event
    $('#deleteEvent').click(function() {
        var id = $('#eventId').val();
        if (confirm("Are you sure you want to delete this event?")) {
            $.ajax({
                url: SITEURL + "/kalendars/ajax",
                data: {
                    id: id,
                    type: 'delete'
                },
                type: "POST",
                success: function() {
                    displayMessage("Event Deleted Successfully");
                    calendar.getEventById(id).remove();
                    $('#eventModal').modal('hide');
                }
            });
        }
    });

    // Show modal for adding new event
    $('#addEventButton').click(function() {
        $('#addEventForm')[0].reset();
        $('#newEventKomandas').val('');
        $('#newEventApraksts').val('');
        $('#newEventLaiks').val('');
        $('#newEventVieta').val('');
        // Set default date to today
        var today = new Date().toISOString().split('T')[0];
        $('#newEventSakumaDatums').val(today);
        $('#addEventModal').modal('show');
    });

    // Display message function
    function displayMessage(message) {
        toastr.success(message, 'Event');
    }

    // Set up CSRF token for AJAX requests
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });
});
    </script>
    @endpush
</x-app-layout>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.10.1/main.min.css' rel='stylesheet' />
    <script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.10.1/main.min.js'></script>
    <title>Training Calendar</title>
    <style>
        #calendar {
            max-width: 900px;
            margin: 40px auto;
        }
    </style>
</head>
<body>

<div>
    <h1>Training Calendar</h1>
    <label for="team-select">Select Team:</label>
    <select id="team-select">
        <option value="">Select a Team</option>
        @foreach($teams as $team)
            <option value="{{ $team->id }}">{{ $team->vecums }}</option>
        @endforeach
    </select>
</div>

<div id='calendar'></div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridWeek',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'addEventButton'
            },
            events: [], // Initially empty; will be populated based on selected team
            editable: true,
            eventClick: function(info) {
                if (confirm("Do you want to delete this training session?")) {
                    fetch(`/delete-event/${info.event.id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    }).then(() => {
                        info.event.remove();
                    });
                }
            }
        });

        calendar.render();

        // Event listener for team selection
        document.getElementById('team-select').addEventListener('change', function() {
            var teamId = this.value;
            if (teamId) {
                fetch(`/fetch-events/${teamId}`)
                    .then(response => response.json())
                    .then(data => {
                        // Clear existing events
                        calendar.removeAllEvents();
                        // Add new events for the selected team
                        data.forEach(event => {
                            calendar.addEvent({
                                id: event.id,
                                title: event.title + ' - ' + event.location,
                                start: event.start,
                                description: event.short_description
                            });
                        });
                    });
            } else {
                // Clear events if no team is selected
                calendar.removeAllEvents();
            }
        });

        // Function to add a new event
        calendar.addEventSource([
            {
                buttonText: 'Add Training',
                click: function() {
                    var title = prompt("Enter training session title:");
                    if (!title) return; // Exit if no title is given

                    var description = prompt("Enter short description:");
                    var dateStr = prompt("Enter date in YYYY-MM-DD format:");
                    var timeStr = prompt("Enter time in HH:mm format:");
                    var location = prompt("Enter location:");
                    var team = document.getElementById('team-select').value; // Get the selected team

                    if (dateStr && timeStr && location && team) {
                        var eventData = {
                            title: title,
                            short_description: description,
                            start: dateStr + 'T' + timeStr,
                            location: location,
                            team_id: team
                        };

                        fetch('/store-event', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(eventData)
                        }).then(response => response.json()).then(data => {
                            if (data.success) {
                                calendar.addEvent({
                                    title: title + ' - ' + location,
                                    start: dateStr + 'T' + timeStr,
                                    description: description
                                });
                            }
                        });
                    } else {
                        alert('Please fill in all fields.');
                    }
                }
            }
        ]);
    });
</script>
</body>
</html>
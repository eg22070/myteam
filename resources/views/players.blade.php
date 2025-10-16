<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('AFA OLAINE') }} {{$teams?->vecums}} {{ __('team')}}
        </h2>
        <p><a href="{{ route('teams') }}">--Back to all teams</a></p>
    </x-slot>
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class=" grid grid-cols-2 gap-10  overflow-hidden ">
            <div class="bg-white border border-sky-400 rounded-md shadow-sm sm:rounded-lg p-6 text-gray-900">    
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Team information:
            </h2>
            </br>
            @can('is-coach-or-owner')    
            <!-- Button to Open Modal for editing team information-->
            <button type="button" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150" data-toggle="modal" data-target="#editTeamModal">
                Edit team information
            </button>
            @endcan
            </br>
            </br>
            <h2 class="font-semibold text-l text-gray-800 leading-tight">
                {{ __('Information for the team:') }}
                </h2>
            <div class=" bg-white border border-sky-400 rounded-md shadow-sm sm:rounded-lg p-6 text-gray-900">
                <p>{{$teams->apraksts}}</p>
            </div>
                </br>
                <div class=" bg-white border border-sky-400 rounded-md shadow-sm sm:rounded-lg p-6 text-gray-900">
                <button type="button" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150" data-toggle="modal" data-target="#viewCommentModal">
                <h2 class=" hover-underline-animation border-sky-400 rounded-md font-semibold text-xl text-white-800 leading-tight"><u>View coach comments</u></a></h2>
            </button>
            </br>
            </div>
            <br>
            <div class=" bg-white border border-sky-400 rounded-md shadow-sm sm:rounded-lg p-6 text-gray-900">
            <button type="button" class="bg-cyan-200 border border-sky-400 rounded-md overflow-hidden shadow-sm sm:rounded-lg p-1 text-gray-900" data-toggle="modal" data-target="#gameModal">
    View Game Results
</button>
</div>
</div>
<div class=" bg-white border border-sky-400 rounded-md shadow-sm sm:rounded-lg p-6 text-gray-900">
        <h3 class="font-semibold text-xl text-gray-800 leading-tight">Team players:</h3></br>
        @can('is-coach-or-owner')    
            <!-- Button to Open Modal for adding players-->
            <button type="button" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150" data-toggle="modal" data-target="#addPlayerModal">
                Add new player
            </button>
        @endcan
 </br>
 </br>

 @foreach ($players as $player)
  <div class="bg-cyan-200 border border-sky-400 rounded-md overflow-hidden shadow-sm sm:rounded-lg p-1 text-gray-900">
  <div>
  <h2 class="font-semibold text-xl text-gray-800 leading-tight"><a href="{{route('players.show', ['id' => $player->id]) }}">{{ $player->user->name }} {{ $player->user->surname }}</a></h2>
 </div>
 <div class="flex items-center justify-end mt-4">
 @can('is-coach-or-owner')
 <form method="POST"
 action="{{ action([App\Http\Controllers\PlayersController::class, 'destroy'], ['id' => $player->id, 'teamslug' => $teams->vecums])}}">
@csrf
@method('DELETE')
<x-primary-button class="ml-4">Delete player</x-primary-button>
 </form>
 @endcan
</div>
 </div></br>@endforeach
 </div>
            </div>
        </div>
    </div>
</div>
<!-- Edit team information Modal -->
<div class="modal fade" id="editTeamModal" tabindex="-1" aria-labelledby="editTeamModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editTeamModalLabel">Edit teams information</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                <form method="POST" action="{{ route('teams.show',  ['id' => $teams->id]) }}">
                    @csrf
                    <div>
                        <x-input-label for="vecums" :value="__('Vecums')" />
                        <x-text-input id="vecums" class="block mt-1 w-full" type="text" name="vecums" :value="old('vecums', $teams->vecums)" required autofocus autocomplete="vecums" />
                        <x-input-error :messages="$errors->get('vecums')" class="mt-2" />
                    </div>
                    <div class="mt-4">
                        <x-input-label for="apraksts" :value="__('Informācija komandai')" />
                        <x-text-input id="apraksts" class="block mt-1 w-full" type="text" name="apraksts" :value="old('apraksts', $teams->apraksts)" autocomplete="apraksts" />
                        <x-input-error :messages="$errors->get('apraksts')" class="mt-2" />
                    </div>
                    <div class="form-group">
    <label for="coach_id">Coach</label>
    <select name="coach_id" id="coach_id" class="form-control" required>
        <!-- Check if current coach is in the list of coaches -->
        @foreach($coaches as $coach)
            <option value="{{ $coach->id }}" 
                {{ $teams->coach_id === $coach->id ? 'selected' : '' }}>
                {{ $coach->name }} {{ $coach->surname }}
            </option>
        @endforeach
    </select>
    <x-input-error :messages="$errors->get('coach_id')" class="mt-2" />
</div>
                    <div class="flex items-center justify-end mt-4">
                        <x-primary-button class="ml-4">
                            {{ __('Update teams information') }}
                        </x-primary-button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>

<!-- Comment Modal -->
<div class="modal fade" id="viewCommentModal" tabindex="-1" aria-labelledby="commentsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 casls="modal-title" id="commentsModalLabel">Coach Comments</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @if (isset($comments) && count($comments) > 0)
                    @foreach ($comments as $comment)
                        <h3>{{ $comment->virsraksts }} ({{ $comment->datums }})</h3>
                        <p>{{ $comment->komentars }}</p>
                        @can('is-coach-or-owner')
                            <form method="POST"
                            action="{{ route('comment.destroy', ['id' => $comment->id, 'teamslug' => $teams->vecums]) }}">
                            @csrf
                            @method('DELETE')
                            <x-primary-button class="ml-4">Delete comment</x-primary-button>
                            </form>
                            @endcan
                            <hr>
                        
                    @endforeach
                @else
                    <p class='text-danger'>There are no comments for this team from the coach!</p>
                @endif
                <!-- Form to Add New comment -->
                <button class="btn btn-primary" id="toggleCommentFormButton">Add comment</button>

                @can('is-coach-or-owner')
                    <div id="addCommentForm" style="display: none; margin-top: 10px;">
                    <form action="{{ route('comments.store', ['teamslug' => $teams->vecums]) }}" method="POST">
                        @csrf
                        <div>
                            <x-input-label for="komentars" :value="__('Comment')" />
                            <x-text-input id="komentars" class="block mt-1 w-full" type="text" name="komentars" :value="old('komentars')" required autofocus autocomplete="komentars" />
                            <x-input-error :messages="$errors->get('komentars')" class="mt-2" />
                        </div>
                        
                        <input type="hidden" name="komandas_id" value="{{ $teams->id }}">
                        
                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ml-4">
                                {{ __('Save') }}
                            </x-primary-button>
                        </div>
                    </form>
            </div>
                @endcan

                <script>
                    document.getElementById('toggleCommentFormButton').addEventListener('click', function() {
                        var form = document.getElementById('addCommentForm');
                        if (form.style.display === 'none') {
                            form.style.display = 'block';
                        } else {
                            form.style.display = 'none';
                        }
                    });
                </script>
            </div>
        </div>
    </div>
</div>
<!-- Game Results Modal -->
<div class="modal fade" id="gameModal" tabindex="-1" aria-labelledby="gameModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="gameModalLabel">{{ __('AFA OLAINE') }} {{$teams?->vecums}} {{ __('game results')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Display existing game results -->
                @if ($games->isEmpty())
                    <p>No games found for this team.</p>
                @else
                    @foreach ($games as $game)
                        <h4>{{ __('AFA OLAINE') }} {{$teams?->vecums}} - {{ $game->pretinieks }} </h4> 
                        <h2>{{ $game->rezultats }}</h2>
                        <hr>
                        <ul class="goal-list">

                        {{-- Goals --}}
                        @foreach($game->varti as $goal)
                            @if($goal->vartuGuvejs)
                                <li>
                                    #{{ $goal->vartuGuvejs->numurs }} Goal by {{ $goal->vartuGuvejs->user->name }} {{ $goal->vartuGuvejs->user->surname }}
                                    @if($goal->assist)
                                        (#{{ $goal->assist->numurs }} Assisted by {{ $goal->assist->user->name }} {{ $goal->assist->user->surname }})
                                    @endif
                                    (Minute: {{ $goal->minute }})
                                    @can('is-coach-or-owner')
                                    <form method="POST" action="{{ route('varti.destroy', ['teamslug' => $teams->vecums, 'id' => $goal->id]) }}" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                    @endcan
                                </li>
                            @endif
                        @endforeach

                        {{-- Yellow cards --}}
                        @foreach($game->varti as $goal)
                            @if($goal->dzeltena_id)
                                @php
                                    $player = $goal->dzeltena; // define relation
                                @endphp
                                <li style="color: yellow;">
                                    Yellow Card to {{ $player->user->name }} {{ $player->user->surname }} (#{{ $player->numurs }})
                                    (Minute: {{ $goal->minute }})
                                    @can('is-coach-or-owner')
                                    <form method="POST" action="{{ route('varti.destroy', ['teamslug' => $teams->vecums, 'id' => $goal->id]) }}" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Remove</button>
                                    </form>
                                    @endcan
                                </li>
                            @endif
                        @endforeach

                        {{-- Red cards --}}
                        @foreach($game->varti as $goal)
                            @if($goal->sarkana_id)
                                @php
                                    $player = $goal->sarkana; // define relation
                                @endphp
                                <li style="color: red;">
                                    Red Card to {{ $player->user->name }} {{ $player->user->surname }} (#{{ $player->numurs }})
                                    (Minute: {{ $goal->minute }})
                                    @can('is-coach-or-owner')
                                    <form method="POST" action="{{ route('varti.destroy', ['teamslug' => $teams->vecums, 'id' => $goal->id]) }}" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Remove</button>
                                    </form>
                                    @endcan
                                </li>
                            @endif
                        @endforeach

                    </ul>
            @can('is-coach-or-owner')
            <!-- Button to Add Event -->
            <button class="btn btn-primary" onclick="toggleGoalForm({{ $game->id }})">
                <img src="{{ asset('images/plus-button.jpg') }}" alt="Add Goal" style="width: 20px; height: 20px;"/> Add Event
            </button>
            @endcan
            <!-- Add Event Form -->
            <div id="goalForm-{{ $game->id }}" class="goal-form" style="display: none; margin-top: 10px;">
                <form method="POST" action="{{ route('varti.store', ['teamslug' => $teams->vecums]) }}">
                    @csrf
                    <!-- Event type -->
                    <input type="hidden" name="speles_id" value="{{ $game->id }}">
                    <label>Event Type:</label>
                    <select class="event-type" data-game-id="{{ $game->id }}" id="event-type-{{ $game->id }}" name="event_type" required>
                        <option value="">Select Event Type</option>
                        <option value="goal">Goal</option>
                        <option value="yellow">Yellow Card</option>
                        <option value="red">Red Card</option>
                    </select>

                    <!-- Player (goal scorer or card recipient) -->
                    <div id="player-field">
                        <label>Player:</label>
                        <select name="player_id" required>
                            @foreach($players as $player)
                                <option value="{{ $player->id }}">{{ $player->user->name }} {{ $player->user->surname }} (#{{ $player->numurs }})</option>
                            @endforeach
                        </select>
                    </div>

                     <!-- Assist (only for goal) -->
                    <div id="assist-field-{{ $game->id }}" style="display:none;">
                        <label>Assist Player:</label>
                        <select name="assist_id">
                            <option value="">None</option>
                            @foreach($players as $player)
                                <option value="{{ $player->id }}">{{ $player->user->name }} {{ $player->user->surname }} (#{{ $player->numurs }})</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Additional fields for goals -->
                    <div id="minute-field">
                        <label>Minute:</label>
                        <input type="number" name="minute" required>
                    </div>
                    <button type="submit" class="btn btn-success">Save Event</button>
                </form>
            </div>
            <script>
            
            function toggleGoalForm(id) {
                const formDiv = document.getElementById(`goalForm-${id}`);
                if (formDiv.style.display === 'none' || formDiv.style.display === '') {
                    formDiv.style.display = 'block';
                } else {
                    formDiv.style.display = 'none';
                }
            }

            document.addEventListener('DOMContentLoaded', () => {
                document.querySelectorAll('.event-type').forEach(select => {
                    select.addEventListener('change', () => {
                    const gameId = select.dataset.gameId;
                    const assistDiv = document.getElementById('assist-field-' + gameId);
                    if (!assistDiv) return; // safety check

                    if (select.value === 'goal') {
                        assistDiv.style.display = 'block';
                    } else {
                        assistDiv.style.display = 'none';
                        // clear assist selection
                        const assistSelect = assistDiv.querySelector('select');
                        if (assistSelect) assistSelect.value = '';
                    }
                    });
                    // Trigger the event once to set the correct initial state
                    select.dispatchEvent(new Event('change'));
                });
            });
            </script>
            @can('is-coach-or-owner')
            <!-- Edit game button -->
            <button type="button" class="inline-flex items-center justify-center w-8 h-8 bg-gray-800 border border-gray-500 rounded-md hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 toggleEditGameFormButton" data-game-id="{{ $game->id }}">
                <img src="{{ asset('images/pencil-edit-button.jpg') }}" alt="Edit Game result" class="w-full h-full object-cover" style="cursor: pointer;" />
            </button>
            <!-- Line-up submit -->
            @can('is-coach-or-owner')
                <button class="btn btn-info" onclick="togglePlayerSelection({{ $game->id }})">Who played?</button>
            @endcan

            <!-- Add this form after the edit game form -->
            <div id="playerSelectionForm-{{ $game->id }}" style="display: none; margin-top: 10px;">
                <form method="POST" action="{{ route('games.updatePlayers', ['id' => $game->id, 'teamslug' => $teams->vecums]) }}">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="redirect_url" value="{{ url()->current() }}">
                    <h4>Select players who played in this game:</h4>
                    @foreach($players as $player)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="players[]" value="{{ $player->id }}" id="player-{{ $player->id }}">
                            <label class="form-check-label" for="player-{{ $player->id }}">
                                {{ $player->user->name }} {{ $player->user->surname }} (#{{ $player->numurs }})
                            </label>
                        </div>
                    @endforeach
                    <button type="submit" class="btn btn-success mt-3">Save Players</button>
                </form>
            </div>
            <script>
                function togglePlayerSelection(id) {
                    const formDiv = document.getElementById(`playerSelectionForm-${id}`);
                    if (formDiv.style.display === 'none' || formDiv.style.display === '') {
                        formDiv.style.display = 'block';
                    } else {
                        formDiv.style.display = 'none';
                    }
                }
            </script>
            <!-- Delete game button -->
            <form method="POST"
            action="{{ route('speles.destroy', ['id' => $game->id, 'teamslug' => $teams->vecums]) }}">
            @csrf
            @method('DELETE')
            <x-primary-button class="ml-4">Delete game result</x-primary-button>
            </form>
            <!-- Edit game modal -->
            <div id="editGameForm-{{ $game->id }}" style="display: none; margin-top: 10px;">
            <form method="POST" action="{{ route('games.show', ['id' => $game->id, 'teamslug' => $teams->vecums]) }}">
            @csrf
            <div class="form-group">
                <x-input-label for="pretinieks" :value="__('Opponent')" />
                <x-text-input id="pretinieks" class="block mt-1 w-full" type="text" name="pretinieks" :value="old('pretinieks', $game->pretinieks)" required autofocus autocomplete="pretinieks" />
                <x-input-error :messages="$errors->get('pretinieks')" class="mt-2" />
            </div>
            <div class="form-group">
                <x-input-label for="rezultats" :value="__('Result')" />
                <x-text-input id="rezultats" class="block mt-1 w-full" type="text" name="rezultats" :value="old('rezultats', $game->rezultats)" required autofocus autocomplete="rezultats" />
                <x-input-error :messages="$errors->get('rezultats')" class="mt-2" />
            </div>
            <input type="hidden" name="komanda_id" value="{{ $teams->id }}">
                <button type="submit" class="btn btn-success">Save</button>
            </form>
        </div>
                            
            @endcan
                        
            @endforeach
            <script>
                document.querySelectorAll('.toggleEditGameFormButton').forEach(button => {
                    button.addEventListener('click', function() {
                        const gameId = this.dataset.gameId;
                        const form = document.getElementById(`editGameForm-${gameId}`);
                        if (form.style.display === 'none' || form.style.display === '') {
                            form.style.display = 'block'; // Show the form
                        } else {
                            form.style.display = 'none'; // Hide the form
                        }
                    });
                });
            </script>
            @endif
            <!-- Form to Add New Game -->
            @can('is-coach-or-owner')
                <button class="btn btn-primary" id="toggleGameFormButton">Add Game</button>
                    <div id="addGameForm" style="display: none; margin-top: 10px;">
                        <form method="POST" action="{{ route('games.store', ['teamslug' => $teams->vecums]) }}">
                            @csrf
                            <div class="form-group">
                                <label for="pretinieks">Opponent</label>
                                <input type="text" class="form-control" id="pretinieks" name="pretinieks" required>
                            </div>
                            <div class="form-group">
                                <label for="rezultats">Result</label>
                                <input type="text" class="form-control" id="rezultats" name="rezultats" required>
                            </div>
                            <input type="hidden" name="komanda_id" value="{{ $teams->id }}">
                            <button type="submit" class="btn btn-success">Save</button>
                        </form>
                    </div>
            @endcan
                <script>
                    document.getElementById('toggleGameFormButton').addEventListener('click', function() {
                        var form = document.getElementById('addGameForm');
                        if (form.style.display === 'none') {
                            form.style.display = 'block';
                        } else {
                            form.style.display = 'none';
                        }
                    });
                </script>
            </div>
        </div>
    </div>
</div>    
 <!-- Add Player Modal -->
<div class="modal fade" id="addPlayerModal" tabindex="-1" aria-labelledby="addPlayerModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="addPlayerModalLabel">Select Players to Add</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>

      <div class="modal-body">
        <!-- Show error if no available players -->
        @if($availablePlayers->isEmpty())
          <div class="alert alert-danger">No available players to add.</div>
        @endif

        <!-- Search and sort controls -->
        @if(!$availablePlayers->isEmpty())
        <div class="d-flex mb-3">
          <input type="text" id="searchInput" class="form-control" placeholder="Search by name or surname" />
          <button type="button" id="sortButton" class="btn btn-secondary ml-2">Sort by DOB (Young to Old)</button>
        </div>

        <!-- Player list -->
        <form id="addPlayersForm" method="POST" action="{{ route('players.store', ['teamslug' => $teams->vecums]) }}">
          @csrf
          <div id="playersContainer" style="max-height: 400px; overflow-y: auto;">
            @foreach($availablePlayers as $user)
              <div class="player-item" data-dob="{{ $user->speletajs->dzimsanas_datums ?? '0000-00-00' }}">
                <input type="checkbox" name="player_ids[]" value="{{ $user->speletajs->id }}" id="player{{ $user->speletajs->id }}">
                <label for="player{{ $user->speletajs->id }}">
                  {{ $user->name }} {{ $user->surname }} (DOB: {{ $user->speletajs->dzimsanas_datums ?? 'N/A' }})
                </label>
              </div>
            @endforeach
          </div>
          <div class="mt-3">
            <x-primary-button class="ml-4">Add Selected Players</x-primary-button>
          </div>
        </form>
        @endif
      </div>

    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchInput');
    const container = document.getElementById('playersContainer');
    const sortButton = document.getElementById('sortButton');

    // Filter players based on search input
    searchInput.addEventListener('input', function () {
      const query = this.value.toLowerCase();
      Array.from(container.getElementsByClassName('player-item')).forEach(item => {
        const text = item.textContent.toLowerCase();
        if (text.includes(query)) {
          item.style.display = '';
        } else {
          item.style.display = 'none';
        }
      });
    });

    // Sort players by DOB from youngest to oldest
    let sortedAsc = true;
    sortButton.addEventListener('click', () => {
      const items = Array.from(container.getElementsByClassName('player-item'));
      items.sort((a, b) => {
        const dateA = new Date(a.dataset.dob);
        const dateB = new Date(b.dataset.dob);
        return sortedAsc ? dateA - dateB : dateB - dateA;
      });
      // Append sorted items to container
      items.forEach(item => container.appendChild(item));
      // Toggle sorting order
      sortedAsc = !sortedAsc;
      // Update button text
      sortButton.textContent = sortedAsc ? 'Sort by DOB (Young to Old)' : 'Sort by DOB (Old to Young)';
    });
  });
</script>
</x-app-layout>
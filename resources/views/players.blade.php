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
                @can('access-comments-view', $teams)
                <div class=" bg-white border border-sky-400 rounded-md shadow-sm sm:rounded-lg p-6 text-gray-900">
                <button type="button" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150" data-toggle="modal" data-target="#viewCommentModal">
                <h2 class=" hover-underline-animation border-sky-400 rounded-md font-semibold text-xl text-white-800 leading-tight"><u>View coach comments</u></a></h2>
            </button>
            </br>
            </div>
            @endcan
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
  <h2 class="font-semibold text-xl text-gray-800 leading-tight"><a href="{{route('players.show', ['id' => $player->id]) }}">{{ $player->name }} {{ $player->surname }}</a></h2>
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

<!-- Comment/Tactical Material Modal -->
<div class="modal fade" id="viewCommentModal" tabindex="-1" aria-labelledby="commentsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl"> {{-- Increased modal size to extra-large for more content --}}
        <div class="modal-content">
            <div class="modal-header flex justify-between items-center bg-gray-100 border-b border-gray-200 p-4"> {{-- Styled header --}}
                <h5 class="modal-title font-semibold text-2xl text-gray-800" id="commentsModalLabel">Coach tactical materials</h5> {{-- Updated title and styling --}}
                <div class="flex items-center space-x-3">
                    {{-- "Add Tactical Material" button --}}
                    @can('is-coach')
                        <button type="button" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"  id="toggleAddCommentFormButton"> {{-- Renamed ID for clarity --}}
                            {{ __('Add tactical material') }} {{-- Changed button text --}}
                        </button>
                    @endcan
                    <button type="button" class="close text-gray-600 hover:text-gray-900 text-2xl" data-dismiss="modal" aria-label="Close"> {{-- Styled close button --}}
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <div class="modal-body p-6"> {{-- Added padding to modal body --}}
                {{-- Form to Add New tactical material (initially hidden) --}}
                @can('is-coach')
                    <div id="addCommentForm" class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6" style="display: none;"> {{-- Styled form container --}}
                        <h4 class="font-bold text-lg mb-4 text-gray-800">{{ __('Add New Tactical Material') }}</h4>
                        <form action="{{ route('comments.store', ['teamslug' => $teams->vecums]) }}" method="POST" enctype="multipart/form-data"> {{-- Added enctype for file upload --}}
                            @csrf
                            <div class="mb-4">
                                <x-input-label for="add_komentars" :value="__('Description')" /> {{-- Changed label to description --}}
                                <textarea id="add_komentars" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" name="komentars" rows="4" required>{{ old('komentars') }}</textarea> {{-- Textarea for description --}}
                                <x-input-error :messages="$errors->get('komentars')" class="mt-2" />
                            </div>
                             <div class="mb-4"> {{-- Image upload field --}}
                                <x-input-label for="add_image" :value="__('Image (Optional)')" />
                                <input type="file" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" id="add_image" name="image" accept="image/*" />
                                <x-input-error :messages="$errors->get('image')" class="mt-2" />
                            </div>
                            
                            <input type="hidden" name="komandas_id" value="{{ $teams->id }}">
                            
                            <div class="flex items-center justify-end mt-4">
                                <x-primary-button class="ml-4">
                                    {{ __('Add Material') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                @endcan

                {{-- Display existing comments/materials --}}
                @if (isset($comments) && count($comments) > 0)
                    @foreach ($comments as $comment)
                        <div class="bg-gray-100 border border-gray-300 rounded-lg shadow-sm p-4 mb-4">
                            <div class="flex flex-col md:flex-row gap-6">
                                {{-- Image Placeholder/Display --}}
                                <div class="flex-shrink-0 w-full md:w-64"> 
                                    @if($comment->bilde)
                                        <img src="{{ asset('storage/' . $comment->bilde) }}" alt="{{ $comment->virsrakst }}" class="w-50 h-50 object-cover rounded-md border border-gray-200"> {{-- w-full h-40 object-cover for responsive image --}}
                                    @else
                                        {{-- Wireframe-style placeholder --}}
                                        <div class="w-full h-40 bg-white border border-gray-300 rounded-md flex items-center justify-center text-gray-400 text-lg">
                                            <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="flex-grow flex flex-col justify-between"> {{-- Text content and buttons --}}
                                    <div>
                                        <h3 class="font-semibold text-lg text-gray-800 leading-tight">
                                            {{ $comment->virsraksts }} 
                                            <span class="text-gray-600 text-sm">({{ \Carbon\Carbon::parse($comment->created_at)->format('d/m/Y') }})</span> {{-- Use created_at if no 'datums' column --}}
                                        </h3>
                                        <p class="mt-2 text-gray-700 text-sm whitespace-pre-line">{{ $comment->komentars }}</p>
                                    </div>
                                    @can('is-coach')
                                        <div class="flex items-center justify-end mt-4 gap-2"> {{-- Buttons section --}}
                                            <!-- Edit Button (Pencil Icon) -->
                                            {{-- Changed to a button that toggles an inline form --}}
                                            <button type="button" class="toggle-edit-form-button w-8 h-8 p-1 rounded-md bg-gray-300 border border-dark hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                                    data-target-form="#editCommentForm-{{ $comment->id }}">
                                               <img src="{{ asset('images/pencil-edit-button.jpg') }}" alt="Edit comment" class="w-full h-full object-cover" style="cursor: pointer;" />
                                            </button>

                                            <!-- Delete Button (Trash Can Icon) -->
                                            <form method="POST" action="{{ route('comment.destroy', ['id' => $comment->id, 'teamslug' => $teams->vecums]) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-8 h-8 p-1 rounded-md bg-white-500 border border-danger hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                    <img src="{{ asset('images/delete-icon.png') }}" alt="Delete Material" class="w-full h-full object-cover" style="cursor: pointer;"  /> 
                                                </button>
                                            </form>
                                        </div>
                                    @endcan
                                </div>
                            </div>
                        </div>

                        {{-- INLINE EDIT FORM FOR THIS COMMENT (initially hidden) --}}
                        @can('is-coach')
                        <div id="editCommentForm-{{ $comment->id }}" class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6" style="display: none;">
                            <h4 class="font-bold text-lg mb-4 text-gray-800">{{ __('Edit Tactical Material') }}</h4>
                            <form method="POST" action="{{ route('comment.update', ['teamslug' => $teams->vecums, 'id' => $comment->id]) }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="mb-4">
                                    <x-input-label for="edit_komentars_{{ $comment->id }}" :value="__('Description')" />
                                    <textarea id="edit_komentars_{{ $comment->id }}" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" name="komentars" rows="4" required>{{ old('komentars', $comment->komentars) }}</textarea>
                                    <x-input-error :messages="$errors->get('komentars')" class="mt-2" />
                                </div>
                                <div class="mb-4">
                                    <x-input-label for="edit_current_image_{{ $comment->id }}" :value="__('Current Image')" />
                                    @if($comment->bilde)
                                        <div class="mb-2">
                                            <img src="{{ asset('storage/' . $comment->bilde) }}" alt="Current Material Image" class="w-25 h-25 border border-gray-300 rounded-md object-cover">
                                        </div>
                                    @else
                                        <p class="text-sm text-gray-500">No image currently uploaded.</p>
                                    @endif
                                    <x-input-label for="edit_image_upload_{{ $comment->id }}" :value="__('Upload New Image (Optional)')" class="mt-2" />
                                    <input type="file" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" id="edit_image_upload_{{ $comment->id }}" name="image" accept="image/*" />
                                    <x-input-error :messages="$errors->get('image')" class="mt-2" />
                                </div>
                                <div class="flex items-center justify-end mt-4 space-x-2"> {{-- Added space-x-2 for buttons --}}
                                    <x-primary-button type="button" class="cancel-edit-form-button bg-gray-500 hover:bg-gray-600" data-target-form="#editCommentForm-{{ $comment->id }}">
                                        {{ __('Cancel') }}
                                    </x-primary-button>
                                    <x-primary-button class="ml-4">
                                        {{ __('Save Changes') }}
                                    </x-primary-button>
                                </div>
                            </form>
                        </div>
                        @endcan
                    @endforeach
                @else
                    <p class='text-gray-600 italic'>There are no tactical materials to show!</p> {{-- Updated message --}}
                @endif
                
                <script>
                    // Toggle Add Comment Form
                    document.getElementById('toggleAddCommentFormButton').addEventListener('click', function() {
                        var form = document.getElementById('addCommentForm');
                        if (form.style.display === 'none') {
                            form.style.display = 'block';
                            this.textContent = 'Hide form';
                        } else {
                            form.style.display = 'none';
                            this.textContent = 'Add tactical material';
                        }
                    });

                    // Toggle Edit Comment Forms
                    document.querySelectorAll('.toggle-edit-form-button').forEach(button => {
                        button.addEventListener('click', function() {
                            const formId = this.getAttribute('data-target-form');
                            const form = document.querySelector(formId);
                            if (form.style.display === 'none') {
                                form.style.display = 'block';
                                this.style.display = 'none'; // Hide the edit button itself
                            }
                        });
                    });

                    // Cancel Edit Comment Forms
                    document.querySelectorAll('.cancel-edit-form-button').forEach(button => {
                        button.addEventListener('click', function() {
                            const formId = this.getAttribute('data-target-form');
                            const form = document.querySelector(formId);
                            form.style.display = 'none'; // Hide the edit form
                            
                            // Show the original edit button again
                            const toggleButton = document.querySelector(`.toggle-edit-form-button[data-target-form="${formId}"]`);
                            if (toggleButton) {
                                toggleButton.style.display = 'block';
                            }
                        });
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
                                    Yellow Card to {{ $player->name }} {{ $player->surname }} (#{{ $player->numurs }})
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
                                    Red Card to {{ $player->name }} {{ $player->surname }} (#{{ $player->numurs }})
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
                                <option value="{{ $player->id }}">{{ $player->name }} {{ $player->surname }} (#{{ $player->numurs }})</option>
                            @endforeach
                        </select>
                    </div>

                     <!-- Assist (only for goal) -->
                    <div id="assist-field-{{ $game->id }}" style="display:none;">
                        <label>Assist Player:</label>
                        <select name="assist_id">
                            <option value="">None</option>
                            @foreach($players as $player)
                                <option value="{{ $player->id }}">{{ $player->name }} {{ $player->surname }} (#{{ $player->numurs }})</option>
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

            <!-- Line-up modal -->
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
                                {{ $player->name }} {{ $player->surname }} (#{{ $player->numurs }})
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
              <div class="player-item" data-dob="{{ $user->dzimsanas_datums ?? '0000-00-00' }}">
                <input type="checkbox" name="player_ids[]" value="{{ $user->id }}" id="player{{ $user->id }}">
                <label for="player{{ $user->id }}">
                  {{ $user->name }} {{ $user->surname }} (DOB: {{ $user->dzimsanas_datums ?? 'N/A' }})
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
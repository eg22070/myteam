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
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class=" grid grid-cols-2 gap-10  overflow-hidden ">
            <div class="bg-white border border-sky-400 rounded-md shadow-sm sm:rounded-lg p-6 text-gray-900">    
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Team information:
            </h2>
            </br>
            @can('is-coach-or-owner')    
            <!-- Button to Open Modal for adding players-->
            <button type="button" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150" data-toggle="modal" data-target="#editTeamModal">
                Edit team information
            </button>
            @endcan
            </br>
            </br>
            <h3 class="font-semibold text-l text-gray-800 leading-tight">
                {{ __('Next training for') }} {{$teams?->vecums}} {{ __('team:')}}
                </h2>
                <h3 class="font-semibold text-m text-teal-400 leading-tight">{{$teams->nakosaisTrenins}}</h3>
                </br></br>
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
  <h2 class="font-semibold text-xl text-gray-800 leading-tight"><a href="{{route('players.show', ['id' => $player->id]) }}">{{ $player->vards }} {{ $player->uzvards }}</a></h2>
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
                        <x-input-label for="nakosaisTrenins" :value="__('NakosaisTrenins')" />
                        <x-text-input id="nakosaisTrenins" class="block mt-1 w-full" type="text" name="nakosaisTrenins" :value="old('nakosaisTrenins', $teams->nakosaisTrenins)" required autocomplete="nakosaisTrenins" />
                        <x-input-error :messages="$errors->get('nakosaisTrenins')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="coach_id" :value="__('coach_id')" />
                        <x-text-input id="coach_id" class="block mt-1 w-full" type="text" name="coach_id" :value="old('coach_id', $teams->coach_id)" required autofocus autocomplete="coach_id" />
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
                @foreach($game->varti as $goal)
                    <li>
                        Goal by {{ $goal->VartuGuvejs->name }} {{ $goal->VartuGuvejs->surname }}
                        @if($goal->assist)
                            (Assisted by {{ $goal->assist->name }} {{ $goal->assist->surname }} )
                        @endif
                        (Minute: {{ $goal->minute }})
                        <form method="POST" action="{{ route('varti.destroy', ['teamslug' => $teams->vecums, 'id' => $goal->id]) }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Are you sure you want to delete this goal?');" class="btn btn-danger">Delete</button>
                        </form>
                    </li>
                @endforeach
            </ul>

            <!-- Button to Add Goal -->
            <button class="btn btn-primary" onclick="toggleGoalForm({{ $game->id }})">
                <img src="{{ asset('images/plus-button.jpg') }}" alt="Add Goal" style="width: 20px; height: 20px;"/> Add Goal
            </button>

            <!-- Add Goal Form -->
            <div id="goalForm-{{ $game->id }}" class="goal-form" style="display: none; margin-top: 10px;">
                <form method="POST" action="{{ route('varti.store', ['teamslug' => $teams->vecums]) }}">
                    @csrf
                    <input type="hidden" name="speles_id" value="{{ $game->id }}">
                    <label for="vartuGuveja_id">Player (Goal Scorer):</label>
                    <select name="vartuGuveja_id" required>
                    @foreach($players as $goalPlayer)
                        <option value="{{ $goalPlayer->id }}">{{ $goalPlayer->vards }} {{ $goalPlayer->uzvards }}</option>
                    @endforeach
                    </select>
                    <label for="assist_id">Player (Assist):</label>
                    <select name="assist_id">
                        <option value="">None</option>
                        @foreach($players as $assistPlayer)
                            <option value="{{ $assistPlayer->id }}">{{ $assistPlayer->vards }} {{ $assistPlayer->uzvards }}</option>
                        @endforeach
                    </select>
                    <label for="minute">Minute:</label>
                    <input type="number" name="minute" required>
                    <button type="submit" class="btn btn-success">Save Goal</button>
                </form>
            </div>

        <script>
        function toggleGoalForm(gameId) {
            const form = document.getElementById(`goalForm-${gameId}`);
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }
        </script>
                        @can('is-coach-or-owner')
                            <button type="button" class="inline-flex items-center justify-center w-8 h-8 bg-gray-800 border border-gray-500 rounded-md hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 toggleEditGameFormButton" data-game-id="{{ $game->id }}">
                                <img src="{{ asset('images/pencil-edit-button.jpg') }}" alt="Edit Game result" class="w-full h-full object-cover" style="cursor: pointer;" />
                            </button>
                            <form method="POST"
                            action="{{ route('speles.destroy', ['id' => $game->id, 'teamslug' => $teams->vecums]) }}">
                            @csrf
                            @method('DELETE')
                            <x-primary-button class="ml-4">Delete game result</x-primary-button>
                            </form>
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
                    <h5 class="modal-title" id="addPlayerModalLabel">Adding New Player</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                <form method="POST" action="{{ route('players',  ['teamslug' =>
$teams->vecums]) }}">
        @csrf

        <div>
            <x-input-label for="vards" :value="__('Name')" />
            <x-text-input id="vards" class="block mt-1 w-full" type="text" name="vards" :value="old('vards')" required autofocus autocomplete="vards" />
            <x-input-error :messages="$errors->get('vards')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="uzvards" :value="__('Surname')" />
            <x-text-input id="uzvards" class="block mt-1 w-full" type="text" name="uzvards" :value="old('uzvards')" required autocomplete="uzvards" />
            <x-input-error :messages="$errors->get('uzvards')" class="mt-2" />
        </div>
        <input type="hidden" name="komanda_id" value="{{ $teams->id }}">
        <div>
            <x-input-label for="nepamekletieTrenini" :value="__('Missed trainings this month')" />
            <x-text-input id="nepamekletieTrenini" class="block mt-1 w-full" type="text" name="nepamekletieTrenini" :value="old('nepamekletieTrenini')" required autofocus autocomplete="nepamekletieTrenini" />
            <x-input-error :messages="$errors->get('nepamekletieTrenini')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="speles" :value="__('Games')" />
            <x-text-input id="speles" class="block mt-1 w-full" type="text" name="speles" :value="old('speles')" required autofocus autocomplete="speles" />
            <x-input-error :messages="$errors->get('speles')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="varti" :value="__('Goals')" />
            <x-text-input id="varti" class="block mt-1 w-full" type="text" name="varti" :value="old('varti')" required autofocus autocomplete="varti" />
            <x-input-error :messages="$errors->get('varti')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="piespeles" :value="__('Assists')" />
            <x-text-input id="piespeles" class="block mt-1 w-full" type="text" name="piespeles" :value="old('piespeles')" required autofocus autocomplete="piespeles" />
            <x-input-error :messages="$errors->get('piespeles')" class="mt-2" />
        </div>
        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="ml-4">
                {{ __('Add player') }}
            </x-primary-button>
        </div>
    </form>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
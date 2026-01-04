<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('AFA OLAINE teams') }} 
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @can('is-owner')
                        <!-- Button to Open Modal -->
                        <button type="button" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150" data-toggle="modal" data-target="#addTeamModal">
                            Add new team
                        </button>
                    @endcan
                    </br></br>

                    @if (count($teams) == 0)
                        <p class='error'>There are no teams to show!</p>
                    @else
                        @foreach ($teams as $team)
                            <div class="bg-cyan-200 border border-sky-400 rounded-md overflow-hidden shadow-sm sm:rounded-lg p-1 text-gray-900">
                                <div>
                                    <h2 class="font-semibold text-xl text-gray-800 leading-tight"><a href="{{ route('players', ['teamslug' => $team->vecums]) }}">{{ $team->vecums }} team</a></h2>
                                </div>
                                <div class="flex items-center justify-end mt-4">
                                    @can('is-owner')
                                        <form method="POST" action="{{ route('teams.destroy', $team->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <x-primary-button class="ml-4">Delete team</x-primary-button>
                                        </form>
                                    @endcan
                                </div>
                            </div>
                            </br>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Add Team Modal -->
    <div class="modal fade" id="addTeamModal" tabindex="-1" aria-labelledby="addTeamModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTeamModalLabel">Adding New Team</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('teams.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="vecums">Team age</label>
                            <input type="text" class="form-control" id="vecums" name="vecums" required autofocus>
                            <x-input-error :messages="$errors->get('vecums')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="apraksts" :value="__('Information for the team')" />
                            <textarea id="apraksts" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" name="apraksts" rows="4"></textarea>
                            <x-input-error :messages="$errors->get('apraksts')" class="mt-2" />
                        </div>
                        
                        <div class="mb-4"> 
                            <x-input-label for="add_image" :value="__('Team photo (Optional)')" />
                            <input type="file" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" id="add_image" name="image" accept="image/*" />
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>

                        <div class="form-group">
                        <label for="coach_id">Coach</label>
                            <select name="coach_id" id="coach_id" class="form-control">
                                <option value="">Select a coach</option>
                                @foreach($coaches as $coach)
                                    <option value="{{ $coach->id }}">{{ $coach->name}} {{ $coach->surname }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('coach_id')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Add Team</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
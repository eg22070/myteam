<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('AFA OLAINE komandas') }} 
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-200 text-green-800 p-4">
                    {{ session('success') }}
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
                        <p class='error'>There are no teams in this club!</p>
                    @else
                        @foreach ($teams as $team)
                            <div class="bg-cyan-200 border border-sky-400 rounded-md overflow-hidden shadow-sm sm:rounded-lg p-1 text-gray-900">
                                <div>
                                    <h2 class="font-semibold text-xl text-gray-800 leading-tight"><a href="{{ route('players', ['teamslug' => $team->vecums]) }}">{{ $team->vecums }} komanda</a></h2>
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
                    <form method="POST" action="{{ route('teams.store') }}">
                        @csrf
                        <div class="form-group">
                            <label for="vecums">Vecums</label>
                            <input type="text" class="form-control" id="vecums" name="vecums" required autofocus>
                            <x-input-error :messages="$errors->get('vecums')" class="mt-2" />
                        </div>

                        <div class="form-group">
                            <label for="nakosaisTrenins">Nakosais Trenins</label>
                            <input type="text" class="form-control" id="nakosaisTrenins" name="nakosaisTrenins" required>
                            <x-input-error :messages="$errors->get('nakosaisTrenins')" class="mt-2" />
                        </div>

                        <div class="form-group">
                            <label for="coach_id">Coach ID</label>
                            <input type="text" class="form-control" id="coach_id" name="coach_id" required>
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
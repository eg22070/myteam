<x-app-layout>
<x-slot name="header">
        <h1 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ $player->user->name }} {{ $player->user->surname }}
        </h1>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class=" grid grid-cols-2 gap-10 bg-slate-400 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="bg-white border border-sky-400 rounded-md shadow-sm sm:rounded-lg p-6 text-gray-900">
                <h2 class="border-sky-400 rounded-md font-semibold text-xl text-gray-800 leading-tight">Player information</h2>
                    </br> 
                    @can('is-coach-or-owner')
                    <!-- Button to open modal -->
                    <button type="button" data-toggle="modal" data-target="#editPlayerModal" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Edit player information
                    </button>
                    @endcan

                    @if($player->bilde)
                        <div>
                            <img src="{{ asset('storage/' . $player->bilde) }}" alt="Player Photo" class="border rounded-md border transparent" style="width: 50%; height: 50%;">
                        </div>
                    @endif</br>
                <table style="width:80%"
                class=" py-12 border border-sky-400  max-w">
							<tr>
					<td style="width:50%" class="bg-slate-200">Shirt number:</td>
					<td class="text-center max-w">{{$player->numurs}}</td>
				</tr>
                </table>
                </br>
                <table style="width:80%" class=" py-12 border border-sky-400  max-w">
                <tr>
					<td style="width:50%" class="bg-slate-200">Date of birth:</td>
					<td class="text-center max-w">{{$player->dzimsanas_datums}}</td>
				</tr>
                </table>														
            </div>
            <div class="bg-white border border-sky-400 rounded-md shadow-sm sm:rounded-lg p-6 text-gray-900">
            <h2 class="border-sky-400 rounded-md font-semibold text-xl text-gray-800 leading-tight">Player statistics</h2>
                <table style="width:80%" class="py-12 border border-sky-400  max-w">
                <tr>
					<td style="width:50%" class="bg-slate-200">Missed Trainings this month:</td>
					<td class="text-center max-w">{{$player->nepamekletieTrenini}}</td>
				</tr>
                </table></br>
                <table style="width:80%"
                class=" py-12 border border-sky-400  max-w">
							<tr>
					<td style="width:50%" class="bg-slate-200">Games:</td>
					<td class="text-center max-w">{{$player->speles}}</td>
				</tr>
                </table>
                </br>
                <table style="width:80%" class=" py-12 border border-sky-400  max-w">
                <tr>
					<td style="width:50%" class="bg-slate-200">Goals:</td>
					<td class="text-center max-w">{{$player->varti}}</td>
				</tr>
                </table></br>
                <table style="width:80%" class=" py-12 border border-sky-400  max-w">
                <tr>
					<td style="width:50%" class="bg-slate-200">Assists:</td>
					<td class="text-center max-w">{{$player->piespeles}}</td>
				</tr>
                </table></br>
                <table style="width:80%" class=" py-12 border border-sky-400  max-w">
                <tr>
					<td style="width:50%" class="bg-slate-200">Yellow cards:</td>
					<td class="text-center max-w">{{$player->dzeltenas}}</td>
				</tr>
                </table></br>
                <table style="width:80%" class=" py-12 border border-sky-400  max-w">
                <tr>
					<td style="width:50%" class="bg-slate-200">Red cards:</td>
					<td class="text-center max-w">{{$player->sarkanas}}</td>
				</tr>
                </table></br>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editPlayerModal" tabindex="-1" aria-labelledby="editPlayerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPlayerModalLabel">Editing Player Information</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('players.update', ['id' => $player->id]) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT') 

                    <!-- Name -->
                    <div>
                        <x-input-label for="vards" :value="__('Name')" />
                        <x-text-input id="vards" class="block mt-1 w-full" type="text" name="vards" :value="old('vards', $player->user->name)" required autofocus autocomplete="vards" />
                        <x-input-error :messages="$errors->get('vards')" class="mt-2" />
                    </div>

                    <!-- Surname -->
                    <div class="mt-4">
                        <x-input-label for="uzvards" :value="__('Surname')" />
                        <x-text-input id="uzvards" class="block mt-1 w-full" type="text" name="uzvards" :value="old('uzvards', $player->user->surname)" required autocomplete="uzvards" />
                        <x-input-error :messages="$errors->get('uzvards')" class="mt-2" />
                    </div>
                    <!-- Team -->
                    <div class="mt-4">
                        <x-input-label for="team_id" :value="__('Team')" />
                        <select id="team_id" name="team_id" class="block mt-1 w-full" required>
                            @foreach($teams as $team)
                                <option value="{{ $team->id }}" {{ (old('team_id') ?? $player->komanda_id) == $team->id ? 'selected' : '' }}>
                                    {{ $team->vecums }} 
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('team_id')" class="mt-2" />
                    </div>
                    <!-- Missed trainings -->
                    <div class="mt-4">
                        <x-input-label for="nepamekletieTrenini" :value="__('Missed trainings this month')" />
                        <x-text-input id="nepamekletieTrenini" class="block mt-1 w-full" type="text" name="nepamekletieTrenini" :value="old('nepamekletieTrenini', $player->nepamekletieTrenini)" required autofocus autocomplete="nepamekletieTrenini" />
                        <x-input-error :messages="$errors->get('nepamekletieTrenini')" class="mt-2" />
                    </div>

                    <!-- Player photo upload -->
                    <div class="mt-4">
                        <x-input-label for="bilde" :value="__('Upload New Photo (Optional)')" />
                        @if($player->bilde)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $player->bilde) }}" alt="Player Photo" style="width: 100px; height: auto; border: 1px solid #ccc; border-radius: 4px;">
                            </div>
                        @endif
                        <input type="file" class="form-control" id="bilde" name="bilde" accept="image/*" />
                        <x-input-error :messages="$errors->get('bilde')" class="mt-2" />
                    </div>

                    <!-- Games -->
                    <div class="mt-4">
                        <x-input-label for="speles" :value="__('Games')" />
                        <x-text-input id="speles" class="block mt-1 w-full" type="text" name="speles" :value="old('speles', $player->speles)" required />
                        <x-input-error :messages="$errors->get('speles')" class="mt-2" />
                    </div>

                    <!-- Goals -->
                    <div class="mt-4">
                        <x-input-label for="varti" :value="__('Goals')" />
                        <x-text-input id="varti" class="block mt-1 w-full" type="text" name="varti" :value="old('varti', $player->varti)" required />
                        <x-input-error :messages="$errors->get('varti')" class="mt-2" />
                    </div>

                    <!-- Assists -->
                    <div class="mt-4">
                        <x-input-label for="piespeles" :value="__('Assists')" />
                        <x-text-input id="piespeles" class="block mt-1 w-full" type="text" name="piespeles" :value="old('piespeles', $player->piespeles)" required />
                        <x-input-error :messages="$errors->get('piespeles')" class="mt-2" />
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-center justify-end mt-4">
                        <x-primary-button type="submit" class="ml-4 bg-blue-500 text-white py-2 px-4 rounded">
                            {{ __('Update Player') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
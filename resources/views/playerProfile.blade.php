<x-app-layout>
    <x-slot name="header">
        <h1 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $player->name }} {{ $player->surname }}
        </h1>
        <p>
            <a href="{{ route('players', ['teamslug' => $playersteam?->vecums ?? '']) }}">
                -- Back to {{ $playersteam?->vecums }} team
            </a>
        </p>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-2 gap-10">
            {{-- LEFT COLUMN: Player information --}}
            <div class="bg-white border border-sky-400 rounded-md shadow-sm sm:rounded-lg p-6 text-gray-900">
                {{-- header row --}}
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        Player information
                    </h2>

                    @can('is-team-coach', $playersteam)
                        <button type="button"
                                data-toggle="modal"
                                data-target="#editPlayerModal"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Edit player information
                        </button>
                    @endcan
                </div>

                {{-- photo + info grid --}}
                <div class="grid grid-cols-3 gap-4">
                    {{-- photo / placeholder --}}
                    <div class="col-span-1 flex items-start">
                        @if($player->bilde)
                            <img src="{{ asset('storage/' . $player->bilde) }}"
                                 alt="Player Photo"
                                 class="w-50 h-auto border border-gray-300 rounded-md object-cover">
                        @else
                            <div class="w-full h-40 border border-dashed border-gray-300 rounded-md flex items-center justify-center text-gray-400 text-sm">
                                Player photo
                            </div>
                        @endif
                    </div>

                    {{-- details: full-width row with middle cut --}}
                    <div class="col-span-2 space-y-3 mb-5">
                        {{-- Current team --}}
                        <div class="flex w-full border border-sky-400 mb-3">
                            <div class="w-50 bg-slate-200 px-3 py-2 text-sm border-r border-sky-400">
                                Current team:
                            </div>
                            <div class="w-50 px-3 py-2 text-sm text-center">
                                {{ $playersteam?->vecums ?? 'N/A' }}
                            </div>
                        </div>

                        {{-- Shirt number --}}
                        <div class="flex w-full border border-sky-400 mb-3">
                            <div class="w-50 bg-slate-200 px-3 py-2 text-sm border-r border-sky-400">
                                Shirt number:
                            </div>
                            <div class="w-50 px-3 py-2 text-sm text-center">
                                {{ $player->numurs ?? '-' }}
                            </div>
                        </div>

                        {{-- Date of birth --}}
                        <div class="flex w-full border border-sky-400 mb-3">
                            <div class="w-50 bg-slate-200 px-3 py-2 text-sm border-r border-sky-400">
                                Date of birth:
                            </div>
                            <div class="w-50 px-3 py-2 text-sm text-center">
                                {{ $player->dzimsanas_datums ?? '-' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT COLUMN: Player statistics --}}
            <div class="bg-white border border-sky-400 rounded-md shadow-sm sm:rounded-lg p-6 text-gray-900">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-4">
                    Player statistics
                </h2>

                <div class="space-y-3 w-full">
                    {{-- Missed trainings --}}
                    <div class="flex w-full border border-sky-400 mb-3">
                        <div class="w-50 bg-slate-200 px-3 py-2 text-sm border-r border-sky-400">
                            Missed trainings:
                        </div>
                        <div class="w-50 px-3 py-2 text-sm text-center">
                            {{ $player->neapmekletie_trenini ?? 0 }}
                        </div>
                    </div>

                    {{-- Games --}}
                    <div class="flex w-full border border-sky-400 mb-3">
                        <div class="w-50 bg-slate-200 px-3 py-2 text-sm border-r border-sky-400">
                            Games:
                        </div>
                        <div class="w-50 px-3 py-2 text-sm text-center">
                            {{ $player->speles ?? 0 }}
                        </div>
                    </div>

                    {{-- Goals --}}
                    <div class="flex w-full border border-sky-400 mb-3">
                        <div class="w-50 bg-slate-200 px-3 py-2 text-sm border-r border-sky-400">
                            Goals:
                        </div>
                        <div class="w-50 px-3 py-2 text-sm text-center">
                            {{ $player->varti ?? 0 }}
                        </div>
                    </div>

                    {{-- Assists --}}
                    <div class="flex w-full border border-sky-400 mb-3">
                        <div class="w-50 bg-slate-200 px-3 py-2 text-sm border-r border-sky-400">
                            Assists:
                        </div>
                        <div class="w-50 px-3 py-2 text-sm text-center">
                            {{ $player->piespeles ?? 0 }}
                        </div>
                    </div>

                    {{-- Yellow cards --}}
                    <div class="flex w-full border border-sky-400 mb-3">
                        <div class="w-50 bg-slate-200 px-3 py-2 text-sm border-r border-sky-400">
                            Yellow cards:
                        </div>
                        <div class="w-50 px-3 py-2 text-sm text-center">
                            {{ $player->dzeltenas ?? 0 }}
                        </div>
                    </div>

                    {{-- Red cards --}}
                    <div class="flex w-full border border-sky-400 mb-3">
                        <div class="w-50 bg-slate-200 px-3 py-2 text-sm border-r border-sky-400">
                            Red cards:
                        </div>
                        <div class="w-50 px-3 py-2 text-sm text-center">
                            {{ $player->sarkanas ?? 0 }}
                        </div>
                    </div>
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

                    {{-- Name --}}
                    <div>
                        <x-input-label for="name" :value="__('Name')" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text"
                                    name="name" :value="old('name', $player->name)" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    {{-- Surname --}}
                    <div class="mt-4">
                        <x-input-label for="surname" :value="__('Surname')" />
                        <x-text-input id="surname" class="block mt-1 w-full" type="text"
                                    name="surname" :value="old('surname', $player->surname)" required />
                        <x-input-error :messages="$errors->get('surname')" class="mt-2" />
                    </div>

                    {{-- Team --}}
                    <div class="mt-4">
                        <x-input-label for="komandas_id" :value="__('Team')" />
                        <select id="komandas_id" name="komandas_id" class="block mt-1 w-full" required>
                            @foreach($teams as $team)
                                <option value="{{ $team->id }}"
                                    {{ (old('komandas_id') ?? $player->komandas_id) == $team->id ? 'selected' : '' }}>
                                    {{ $team->vecums }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('komandas_id')" class="mt-2" />
                    </div>

                    {{-- Date of birth --}}
                    <div class="mt-4">
                        <x-input-label for="dzimsanas_datums" :value="__('Date of birth')" />
                        <x-text-input id="dzimsanas_datums" class="block mt-1 w-full" type="date"
                                    name="dzimsanas_datums" :value="old('dzimsanas_datums', $player->dzimsanas_datums)" />
                        <x-input-error :messages="$errors->get('dzimsanas_datums')" class="mt-2" />
                    </div>

                    {{-- Shirt number --}}
                    <div class="mt-4">
                        <x-input-label for="numurs" :value="__('Shirt number')" />
                        <x-text-input id="numurs" class="block mt-1 w-full" type="number"
                                    name="numurs" :value="old('numurs', $player->numurs)" min="0" />
                        <x-input-error :messages="$errors->get('numurs')" class="mt-2" />
                    </div>

                    {{-- Photo --}}
                    <div class="mt-4">
                        <x-input-label for="bilde" :value="__('Upload New Photo (Optional)')" />
                        @if($player->bilde)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $player->bilde) }}" alt="Player Photo"
                                    style="width: 100px; height: auto; border: 1px solid #ccc; border-radius: 4px;">
                            </div>
                        @endif
                        <input type="file" class="form-control" id="bilde" name="bilde" accept="image/*" />
                        <x-input-error :messages="$errors->get('bilde')" class="mt-2" />
                    </div>

                    {{-- Missed trainings --}}
                    <div class="mt-4">
                        <x-input-label for="neapmekletie_trenini" :value="__('Missed trainings this month')" />
                        <x-text-input id="neapmekletie_trenini" class="block mt-1 w-full" type="number"
                                    name="neapmekletie_trenini"
                                    :value="old('neapmekletie_trenini', $player->neapmekletie_trenini)" min="0" />
                        <x-input-error :messages="$errors->get('neapmekletie_trenini')" class="mt-2" />
                    </div>

                    {{-- Games --}}
                    <div class="mt-4">
                        <x-input-label for="speles" :value="__('Games')" />
                        <x-text-input id="speles" class="block mt-1 w-full" type="number"
                                    name="speles" :value="old('speles', $player->speles)" min="0" required />
                        <x-input-error :messages="$errors->get('speles')" class="mt-2" />
                    </div>

                    {{-- Goals --}}
                    <div class="mt-4">
                        <x-input-label for="varti" :value="__('Goals')" />
                        <x-text-input id="varti" class="block mt-1 w-full" type="number"
                                    name="varti" :value="old('varti', $player->varti)" min="0" required />
                        <x-input-error :messages="$errors->get('varti')" class="mt-2" />
                    </div>

                    {{-- Assists --}}
                    <div class="mt-4">
                        <x-input-label for="piespeles" :value="__('Assists')" />
                        <x-text-input id="piespeles" class="block mt-1 w-full" type="number"
                                    name="piespeles" :value="old('piespeles', $player->piespeles)" min="0" required />
                        <x-input-error :messages="$errors->get('piespeles')" class="mt-2" />
                    </div>

                    {{-- Yellow cards --}}
                    <div class="mt-4">
                        <x-input-label for="dzeltenas" :value="__('Yellow cards')" />
                        <x-text-input id="dzeltenas" class="block mt-1 w-full" type="number"
                                    name="dzeltenas" :value="old('dzeltenas', $player->dzeltenas)" min="0" />
                        <x-input-error :messages="$errors->get('dzeltenas')" class="mt-2" />
                    </div>

                    {{-- Red cards --}}
                    <div class="mt-4">
                        <x-input-label for="sarkanas" :value="__('Red cards')" />
                        <x-text-input id="sarkanas" class="block mt-1 w-full" type="number"
                                    name="sarkanas" :value="old('sarkanas', $player->sarkanas)" min="0" />
                        <x-input-error :messages="$errors->get('sarkanas')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <x-primary-button type="submit" class="ml-4">
                            {{ __('Update player information') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
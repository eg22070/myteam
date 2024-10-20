<x-guest-layout>
    <h1 class="font-semibold text-xl text-gray-800 leading-tight">Editing player information</h1>
</br>
    <form method="POST" action="{{route('players.show', ['id' => $player->id]) }}">
        @csrf

        <div>
            <x-input-label for="vards" :value="__('Name')" />
            <x-text-input id="vards" class="block mt-1 w-full" type="text" name="vards" :value="old('vards', $player->vards)" required autofocus autocomplete="vards" />
            <x-input-error :messages="$errors->get('vards')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="uzvards" :value="__('Surname')" />
            <x-text-input id="uzvards" class="block mt-1 w-full" type="text" name="uzvards" :value="old('uzvards', $player->uzvards)" required autocomplete="uzvards" />
            <x-input-error :messages="$errors->get('uzvards')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="nepamekletieTrenini" :value="__('Missed trainings this month')" />
            <x-text-input id="nepamekletieTrenini" class="block mt-1 w-full" type="text" name="nepamekletieTrenini" :value="old('nepamekletieTrenini', $player->nepamekletieTrenini)" required autofocus autocomplete="nepamekletieTrenini" />
            <x-input-error :messages="$errors->get('nepamekletieTrenini')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="speles" :value="__('Games')" />
            <x-text-input id="speles" class="block mt-1 w-full" type="text" name="speles" :value="old('speles', $player->speles)" required autofocus autocomplete="speles" />
            <x-input-error :messages="$errors->get('speles')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="varti" :value="__('Goals')" />
            <x-text-input id="varti" class="block mt-1 w-full" type="text" name="varti" :value="old('varti', $player->varti)" required autofocus autocomplete="varti" />
            <x-input-error :messages="$errors->get('varti')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="piespeles" :value="__('Assists')" />
            <x-text-input id="piespeles" class="block mt-1 w-full" type="text" name="piespeles" :value="old('piespeles', $player->piespeles)" required autofocus autocomplete="piespeles" />
            <x-input-error :messages="$errors->get('piespeles')" class="mt-2" />
        </div>
        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="ml-4">
                {{ __('Update player information') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
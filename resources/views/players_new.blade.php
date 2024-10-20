<x-guest-layout>
    <h1 class="font-semibold text-xl text-gray-800 leading-tight">Adding new player to the team</h1>
</br>
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
</x-guest-layout>
<x-guest-layout>
    <h1 class="font-semibold text-xl text-gray-800 leading-tight">Adding comment to the players profile</h1>
</br>
    <form method="POST" action="{{ route('comment',  ['teamslug' => $team->vecums]) }}">
        @csrf

        <div>
            <x-input-label for="virsraksts" :value="__('Title')" />
            <x-text-input id="virsraksts" class="block mt-1 w-full" type="text" name="virsraksts" :value="old('virsraksts')" required autofocus autocomplete="virsraksts" />
            <x-input-error :messages="$errors->get('virsraksts')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="komentars" :value="__('Comment')" />
            <x-text-input id="komentars" class="block mt-1 w-full" type="text" name="komentars" :value="old('komentars')" required autofocus autocomplete="komentars" />
            <x-input-error :messages="$errors->get('komentars')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="datums" :value="__('Date(YYYY.MM.DD)')" />
            <x-text-input id="datums" class="block mt-1 w-full" type="text" name="datums" :value="old('datums')" required autocomplete="datums" />
            <x-input-error :messages="$errors->get('datums')" class="mt-2" />
        </div>
        <input type="hidden" name="speletajs_id" value="{{ $team->id }}">
        
        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="ml-4">
                {{ __('Add comment') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
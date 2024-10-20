<x-guest-layout>
    <h1 class="font-semibold text-xl text-gray-800 leading-tight">Adding new team to the club</h1>
</br>
    <form method="POST" action="{{ route('teams') }}">
        @csrf


        <div>
            <x-input-label for="vecums" :value="__('Vecums')" />
            <x-text-input id="vecums" class="block mt-1 w-full" type="text" name="vecums" :value="old('vecums')" required autofocus autocomplete="vecums" />
            <x-input-error :messages="$errors->get('vecums')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="nakosaisTrenins" :value="__('NakosaisTrenins')" />
            <x-text-input id="nakosaisTrenins" class="block mt-1 w-full" type="text" name="nakosaisTrenins" :value="old('nakosaisTrenins')" required autocomplete="nakosaisTrenins" />
            <x-input-error :messages="$errors->get('nakosaisTrenins')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="coach_id" :value="__('coach_id')" />
            <x-text-input id="coach_id" class="block mt-1 w-full" type="text" name="coach_id" :value="old('coach_id')" required autofocus autocomplete="coach_id" />
            <x-input-error :messages="$errors->get('coach_id')" class="mt-2" />
        </div>
        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="ml-4">
                {{ __('Add team') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
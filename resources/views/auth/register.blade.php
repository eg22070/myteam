<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Surname -->
        <div>
            <x-input-label for="surname" :value="__('Surname')" />
            <x-text-input id="surname" class="block mt-1 w-full" type="text" name="surname"  required />
            <x-input-error :messages="$errors->get('surname')" class="mt-2" />
        </div>
        <!-- Date of Birth (will be shown if role = player) -->
        <div>
            <x-input-label for="birth_date" :value="__('Date of Birth')" />
            <x-text-input id="birth_date" class="block mt-1 w-full" type="date" name="birth_date" required/>
            <x-input-error :messages="$errors->get('birth_date')" class="mt-2" />
        </div>
        <!-- Role -->
        <div>
            <x-input-label for="role" :value="__('Role')" />
            <x-input-select id="role" class="block mt-1 w-full" name="role" 
                            :selected="old('role')" required/>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>
        <!-- Player photo upload -->
        <div class="mt-4">
            <x-input-label for="bilde" :value="__('Photo (Optional)')" />
                <input type="file" class="form-control" id="bilde" name="bilde" accept="image/*" />
            <x-input-error :messages="$errors->get('bilde')" class="mt-2" />
        </div>        
        <!-- Email -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Submit Button & Login Link -->
        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="ml-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Surname -->
        <div>
            <x-input-label for="surname" :value="__('Surname')" />
            <x-text-input id="surname" class="block mt-1 w-full" type="text" name="surname" :value="old('surname')" required />
            <x-input-error :messages="$errors->get('surname')" class="mt-2" />
        </div>

        <!-- Role -->
        <div>
            <x-input-label for="role" :value="__('Role')" />
            <x-input-select id="role" class="block mt-1 w-full" name="role" 
                            :selected="old('role')" required/>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <!-- Additional Player Fields (hidden by default) -->
        <div id="player-fields" style="display: none; margin-top: 10px;">
            <!-- Date of Birth (will be shown if role = player) -->
            <div>
                <x-input-label for="birth_date" :value="__('Date of Birth')" />
                <x-text-input id="birth_date" class="block mt-1 w-full" type="date" name="birth_date" :value="old('birth_date')" />
                <x-input-error :messages="$errors->get('birth_date')" class="mt-2" />
            </div>
            <!-- Player Number -->
            <div>
                <x-input-label for="player_number" :value="__('Number')" />
                <x-text-input id="player_number" class="block mt-1 w-full" type="number" name="player_number" :value="old('player_number')" />
                <x-input-error :messages="$errors->get('player_number')" class="mt-2" />
            </div>

            <!-- Team -->
            <div>
                <x-input-label for="team_id" :value="__('Team')" />
                <select id="team_id" name="team_id" class="form-control">
                    <option value="">-- Select Team --</option>
                    @foreach($teams as $team)
                        <option value="{{ $team->id }}" {{ old('team_id') == $team->id ? 'selected' : '' }}>{{ $team->vecums }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('team_id')" class="mt-2" />
            </div>
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
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>
            <x-primary-button class="ml-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('role');
    const playerFields = document.getElementById('player-fields');

    function togglePlayerFields() {
        if (roleSelect.value === 'Player') {
            playerFields.style.display = 'block';
        } else {
            playerFields.style.display = 'none';
            // Optionally clear fields
            document.getElementById('birth_date').value = '';
            document.getElementById('player_number').value = '';
            document.getElementById('team_id').value = '';
        }
    }

    // Run on page load
    togglePlayerFields();

    // Listen for role change
    roleSelect.addEventListener('change', togglePlayerFields);
});
</script>
</x-guest-layout>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            All users
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="alert alert-success mb-4">{{ session('success') }}</div>
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
                <div class="alert alert-danger mb-4">{{ session('error') }}</div>
            @endif
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                @foreach($users as $user)
                <div class="flex items-center justify-between mb-2 bg-cyan-200 border border-sky-400 rounded-md px-4 py-2">
                    {{-- Clickable name opens edit modal --}}
                    <button type="button"
                            class="text-left font-semibold text-gray-800 hover:underline"
                            data-toggle="modal"
                            data-target="#editUserModal-{{ $user->id }}">
                        {{ $user->name }} {{ $user->surname }} ({{ $user->role }})
                    </button>

                    {{-- Delete button --}}
                    <form method="POST" action="{{ route('users.destroy', $user) }}"
                          onsubmit="return confirm('Delete this user?');">
                        @csrf
                        @method('DELETE')
                        <x-primary-button class="bg-red-600 hover:bg-red-700">
                            Delete user
                        </x-primary-button>
                    </form>
                </div>

                {{-- Edit User Modal --}}
                <div class="modal fade" id="editUserModal-{{ $user->id }}" tabindex="-1"
                     aria-labelledby="editUserModalLabel-{{ $user->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editUserModalLabel-{{ $user->id }}">
                                    Edit user: {{ $user->name }} {{ $user->surname }}
                                </h5>
                                <button type="button" class="close" data-dismiss="modal"
                                        aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="{{ route('users.update', $user) }}" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    {{-- Name --}}
                                    <div class="mb-3">
                                        <x-input-label for="name-{{ $user->id }}" :value="__('Name')" />
                                        <x-text-input id="name-{{ $user->id }}" class="block mt-1 w-full"
                                                      type="text" name="name"
                                                      :value="old('name', $user->name)" required />
                                    </div>

                                    {{-- Surname --}}
                                    <div class="mb-3">
                                        <x-input-label for="surname-{{ $user->id }}" :value="__('Surname')" />
                                        <x-text-input id="surname-{{ $user->id }}" class="block mt-1 w-full"
                                                      type="text" name="surname"
                                                      :value="old('surname', $user->surname)" required />
                                    </div>

                                    {{-- Date of Birth --}}
                                    <div class="mb-3">
                                        <x-input-label for="birth_date-{{ $user->id }}" :value="__('Date of Birth')" />
                                        <x-text-input id="birth_date-{{ $user->id }}" class="block mt-1 w-full"
                                                      type="date" name="birth_date"
                                                      :value="old('birth_date', $user->dzimsanas_datums)" required/>
                                    </div>

                                    {{-- Role --}}
                                    <div class="mb-3">
                                        <x-input-label for="role-{{ $user->id }}" :value="__('Role')" />
                                        <select id="role-{{ $user->id }}" name="role"
                                                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
                                            @foreach(['Owner','Coach','Player'] as $role)
                                                <option value="{{ $role }}"
                                                    {{ (old('role', $user->role) === $role) ? 'selected' : '' }}>
                                                    {{ $role }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Photo --}}
                                    <div class="mb-3">
                                        <x-input-label for="bilde-{{ $user->id }}" :value="__('Photo (Optional)')" />
                                        @if($user->bilde)
                                            <div class="mb-2">
                                                <img src="{{ asset('storage/' . $user->bilde) }}"
                                                     alt="User Photo"
                                                     style="width: 80px; height: auto; border: 1px solid #ccc; border-radius: 4px;">
                                            </div>
                                        @endif
                                        <input type="file" id="bilde-{{ $user->id }}" name="bilde"
                                               accept="image/*" class="form-control" />
                                    </div>

                                    {{-- Email --}}
                                    <div class="mb-3">
                                        <x-input-label for="email-{{ $user->id }}" :value="__('Email')" />
                                        <x-text-input id="email-{{ $user->id }}" class="block mt-1 w-full"
                                                      type="email" name="email"
                                                      :value="old('email', $user->email)" required />
                                    </div>

                                    {{-- Password (optional) --}}
                                    <div class="mb-3">
                                        <x-input-label for="password-{{ $user->id }}" :value="__('New Password (optional)')" />
                                        <x-text-input id="password-{{ $user->id }}" class="block mt-1 w-full"
                                                      type="password" name="password" autocomplete="new-password"/>
                                    </div>

                                    {{-- Confirm Password --}}
                                    <div class="mb-3">
                                        <x-input-label for="password_confirmation-{{ $user->id }}" :value="__('Confirm Password')" />
                                        <x-text-input id="password_confirmation-{{ $user->id }}" class="block mt-1 w-full"
                                                      type="password" name="password_confirmation"
                                                      autocomplete="new-password"/>
                                    </div>

                                    <div class="flex items-center justify-end mt-4">
                                        <x-primary-button>
                                            {{ __('Update user') }}
                                        </x-primary-button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            </div>
            </div>
        </div>
    </div>
</x-app-layout>

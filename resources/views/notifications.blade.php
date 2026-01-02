<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kluba paziņojumi') }} 
        </h2>
    </x-slot>

    <div class="py-12">
        
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
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
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                @can('is-owner')
                    <div class="mt-4 ml-4">
                        <!-- Button to Open Modal -->
                        <button type="button" class="px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150" data-toggle="modal" data-target="#addNotificationModal">
                            Add notification
                        </button>
                    </div>
                @endcan
                <div class="p-6 text-gray-900">
                    @if (count($pazinojumi) == 0)
                        <p class='error'>There are no notifications to show!</p>
                    @else
                        @foreach ($pazinojumi as $pazinojums)
                            <div class="bg-gray-100 border border-gray-300 rounded-lg shadow-sm p-4 mb-4"> {{-- Grey background, border, increased padding, and spacing below --}}
                                <div class="flex justify-between items-start">
                                    <div class="flex-grow pr-4"> {{-- Text content area --}}
                                        <h3 class="font-semibold text-xl text-gray-800 leading-tight">
                                            {{ $pazinojums->virsraksts }} <i class="text-gray-600 text-base">({{ \Carbon\Carbon::parse($pazinojums->datums)->format('d/m/Y') }})</i>
                                        </h3>
                                        <div class="mt-2 text-gray-700 leading-relaxed text-justify"> {{-- Converted inline styles to Tailwind --}}
                                            {{ $pazinojums->pazinojums }}
                                        </div>
                                        <p class="mt-2 text-sm text-gray-600"><i>--- {{ $pazinojums->user->name }} {{ $pazinojums->user->surname }}</i></p>
                                    </div>
                                    @can('is-owner')
                                        <div class="flex flex-col items-end space-y-2">
                                            <!-- Edit Button -->
                                            <button type="button" class="w-8 h-8 p-1 rounded-md bg-gray-300 border border-dark hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                                    data-toggle="modal" data-target="#editNotificationModal-{{ $pazinojums->id }}">
                                                <img src="{{ asset('images/pencil-edit-button.jpg') }}" alt="Edit Notification" class="w-full h-full object-cover" style="cursor: pointer;" />
                                            </button>

                                            <!-- Delete Button -->
                                            <form method="POST" action="{{ route('notifications.destroy', $pazinojums->id) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-8 h-8 p-1 rounded-md bg-white-500 border border-danger hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                    <img src="{{ asset('images/delete-icon.png') }}" alt="Delete notification" class="w-full h-full object-cover" style="cursor: pointer;"  /> {{-- Assumes you have a trash can icon --}}
                                                </button>
                                            </form>
                                        </div>
                                    @endcan
                                </div>
                            </div>
                        @endforeach
                </div>
            </div>
        </div>
    </div>


<!-- Edit Notification Modal -->
    @foreach ($pazinojumi as $pazinojums)
        <div class="modal fade" id="editNotificationModal-{{ $pazinojums->id }}" tabindex="-1" aria-labelledby="editNotificationModalLabel-{{ $pazinojums->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editNotificationModalLabel-{{ $pazinojums->id }}">{{ __('Edit Notification') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ route('notifications.show', $pazinojums->id) }}">
                            @csrf
                            @method('PATCH') {{-- Use PATCH or PUT for updates --}}

                            <div class="mb-4">
                                <x-input-label for="edit_virsraksts_{{ $pazinojums->id }}" :value="__('Virsraksts')" />
                                <x-text-input id="edit_virsraksts_{{ $pazinojums->id }}" class="block mt-1 w-full" type="text" name="virsraksts" :value="old('virsraksts', $pazinojums->virsraksts)" required autofocus autocomplete="off" />
                                <x-input-error :messages="$errors->get('virsraksts')" class="mt-2" />
                            </div>
                            <div class="mb-4">
                                <x-input-label for="edit_pazinojums_{{ $pazinojums->id }}" :value="__('Paziņojuma saturs')" />
                                {{-- Changed to textarea for multi-line input --}}
                                <textarea id="edit_pazinojums_{{ $pazinojums->id }}" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" name="pazinojums" required>{{ old('pazinojums', $pazinojums->pazinojums) }}</textarea>
                                <x-input-error :messages="$errors->get('pazinojums')" class="mt-2" />
                            </div>
                            <div class="mb-4">
                                <x-input-label for="edit_datums_{{ $pazinojums->id }}" :value="__('Datums (GGGG.MM.DD)')" />
                                <x-text-input id="edit_datums_{{ $pazinojums->id }}" class="block mt-1 w-full" type="date" name="datums" :value="old('datums', $pazinojums->datums)" required autocomplete="off" />
                                <x-input-error :messages="$errors->get('datums')" class="mt-2" />
                            </div>
                            <div class="flex items-center justify-end mt-4">
                                <x-primary-button class="ml-4">
                                    {{ __('Save Changes') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endif 
   <!-- Add Notification Modal -->
    <div class="modal fade" id="addNotificationModal" tabindex="-1" aria-labelledby="addNotificationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addNotificationModalLabel">{{ __('Adding New Notification') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('notifications.store') }}">
                        @csrf
                        <div class="mb-4"> {{-- Added margin-bottom --}}
                            <x-input-label for="virsraksts" :value="__('Virsraksts')" />
                            <x-text-input id="virsraksts" class="block mt-1 w-full" type="text" name="virsraksts" :value="old('virsraksts')" required autofocus autocomplete="off" />
                            <x-input-error :messages="$errors->get('virsraksts')" class="mt-2" />
                        </div>

                        <div class="mb-4"> {{-- Added margin-bottom --}}
                            <x-input-label for="pazinojums" :value="__('Paziņojuma saturs')" />
                            {{-- Changed to textarea for multi-line input --}}
                            <textarea id="pazinojums" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" name="pazinojums" required>{{ old('pazinojums') }}</textarea>
                            <x-input-error :messages="$errors->get('pazinojums')" class="mt-2" />
                        </div>

                        <div class="mb-4"> {{-- Added margin-bottom --}}
                            <x-input-label for="datums" :value="__('Datums (GGGG.MM.DD)')" />
                            <x-text-input id="datums" class="block mt-1 w-full" type="date" name="datums" :value="old('datums')" required autocomplete="off" />
                            <x-input-error :messages="$errors->get('datums')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Add Notification') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
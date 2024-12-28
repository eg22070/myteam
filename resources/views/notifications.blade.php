<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kluba paziņojumi') }} 
        </h2>
        @can('is-owner')
                        <!-- Button to Open Modal -->
                        <button type="button" class="inline-flex items-center justify-end px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150" data-toggle="modal" data-target="#addNotificationModal">
                            Add notification
                        </button>
                    @endcan
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-200 text-green-800 p-4">
                    {{ session('success') }}
                </div>
            @endif
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (count($pazinojumi) == 0)
                        <p class='error'>There are no notifications to show!</p>
                    @else
                        @foreach ($pazinojumi as $pazinojums)
                            <div class="bg-cyan-200 border border-sky-400 rounded-md overflow-hidden shadow-sm sm:rounded-lg p-1 text-gray-900">
                                <div>
                                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $pazinojums->virsraksts }} </h2>
                                    <div class="p" style="line-height: 1.6em; margin: 10px 0px; text-align: justify; color: rgb(34, 34, 34); font-family: Arial, Helvetica, sans-serif;">
                                    {{ $pazinojums->pazinojums}}
                                    </br>
                                    <i >({{ $pazinojums->datums }})</i>
                                    </div>   
                                <div class="flex items-center justify-end mt-4">
                                    @can('is-owner')
                                        <form method="POST" action="{{ route('notifications.destroy', $pazinojums->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <x-primary-button class="ml-4">Delete</x-primary-button>
                                        </form>
                                    @endcan
                                </div>
                                @can('is-coach-or-owner')
                                <button type="button" class="inline-flex items-center justify-center w-8 h-8 bg-gray-800 border border-gray-500 rounded-md hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150" data-toggle="modal" data-target="#openEditNotificationModal">
        <img src="{{ asset('images/pencil-edit-button.jpg') }}" alt="Edit Notification" class="w-full h-full object-cover" style="cursor: pointer;" />
    </button>
                                @endcan
                            </div>
                                
                        
                            </div>
                            </br>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Add Notification Modal -->
    <div class="modal fade" id="addNotificationModal" tabindex="-1" aria-labelledby="addNotificationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addNotificationModalLabel">Adding New Notification</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('notifications.store') }}">
                        @csrf
                        <div class="form-group">
                            <label for="virsraksts">Virsraksts</label>
                            <input type="text" class="form-control" id="virsraksts" name="virsraksts" required autofocus>
                            <x-input-error :messages="$errors->get('virsraksts')" class="mt-2" />
                        </div>

                        <div class="form-group">
                            <label for="pazinojums">Paziņojuma saturs</label>
                            <input type="text" class="form-control" id="pazinojums" name="pazinojums" required>
                            <x-input-error :messages="$errors->get('pazinojums')" class="mt-2" />
                        </div>

                        <div class="form-group">
                            <label for="datums">Datums (GGGG.MM.DD)</label>
                            <input type="text" class="form-control" id="datums" name="datums" required>
                            <x-input-error :messages="$errors->get('datums')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Add Notification</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<!-- Edit Notification Modal -->
    <div class="modal fade" id="openEditNotificationModal" tabindex="-1" aria-labelledby="editNotificationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editNotificationModalLabel">Edit notification</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                <form method="POST" action="{{ route('notifications.show',  ['id' => $pazinojums->id]) }}">
                    @csrf
                    <div>
                        <x-input-label for="virsraksts" :value="__('Virsraksts')" />
                        <x-text-input id="virsraksts" class="block mt-1 w-full" type="text" name="virsraksts" :value="old('virsraksts', $pazinojums->virsraksts)" required autofocus autocomplete="virsraksts" />
                        <x-input-error :messages="$errors->get('virsraksts')" class="mt-2" />
                    </div>
                    <div class="mt-4">
                        <x-input-label for="pazinojums" :value="__('Paziņojuma saturs')" />
                        <x-text-input id="pazinojums" class="block mt-1 w-full" type="text" name="pazinojums" :value="old('pazinojums', $pazinojums->pazinojums)" required autocomplete="pazinojums" />
                        <x-input-error :messages="$errors->get('pazinojums')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="datums" :value="__('Datums (GGGG.MM.DD)')" />
                        <x-text-input id="datums" class="block mt-1 w-full" type="text" name="datums" :value="old('datums', $pazinojums->datums)" required autofocus autocomplete="datums" />
                        <x-input-error :messages="$errors->get('datums')" class="mt-2" />
                    </div>
                    <div class="flex items-center justify-end mt-4">
                        <x-primary-button class="ml-4">
                            {{ __('Save') }}
                        </x-primary-button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
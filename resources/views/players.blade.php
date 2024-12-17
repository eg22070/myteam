<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('AFA OLAINE') }} {{$teams?->vecums}} {{ __('team')}}
        </h2>
        <p><a href="{{ route('teams') }}">--Back to all teams</a></p>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class=" grid grid-cols-2 gap-10  overflow-hidden ">
            <div class="bg-white border border-sky-400 rounded-md shadow-sm sm:rounded-lg p-6 text-gray-900">    
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Team information:
            </h2>
            </br>
            @can('is-coach-or-owner')
            <a href="{{route('teams.edit', ['team' => $teams->id]) }}"
 class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
 Edit teams information</a>
            
            </br>
            </br>@endcan
            <h3 class="font-semibold text-l text-gray-800 leading-tight">
                {{ __('Next training for') }} {{$teams?->vecums}} {{ __('team:')}}
                </h2>
                <h3 class="font-semibold text-m text-teal-400 leading-tight">{{$teams->nakosaisTrenins}}</h3>
                </br></br>
                <div class=" bg-white border border-sky-400 rounded-md shadow-sm sm:rounded-lg p-6 text-gray-900">
                <button type="button" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150" data-toggle="modal" data-target="#viewCommentModal">
                <h2 class=" hover-underline-animation border-sky-400 rounded-md font-semibold text-xl text-gray-800 leading-tight"><u>View coach comments</u></a></h2>
            </button>
            </br> @can('is-coach-or-owner')
            <a href="{{route('comment.create', ['teamslug' => $teams->vecums]) }}"
 class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
 Add comment</a>
@endcan
            </div>
</div>
<div class=" bg-white border border-sky-400 rounded-md shadow-sm sm:rounded-lg p-6 text-gray-900">
        <h3 class="font-semibold text-xl text-gray-800 leading-tight">Team players:</h3></br>
        @can('is-coach-or-owner')    
        <a href="{{action([App\Http\Controllers\PlayersController::class, 'create'], ['teamslug' => $teams->vecums])}}"
 class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
 Add new player</a>@endcan
 </br>
 </br>

 @foreach ($player as $player)
  <div class="bg-cyan-200 border border-sky-400 rounded-md overflow-hidden shadow-sm sm:rounded-lg p-1 text-gray-900">
  <div>
  <h2 class="font-semibold text-xl text-gray-800 leading-tight"><a href="{{route('players.show', ['id' => $player->id]) }}">{{ $player->vards }} {{ $player->uzvards }}</a></h2>
 </div>
 <div class="flex items-center justify-end mt-4">
 @can('is-coach-or-owner')
 <form method="POST"
 action="{{ action([App\Http\Controllers\PlayersController::class, 'destroy'], $player->id) }}">
@csrf
@method('DELETE')
<x-primary-button class="ml-4">Delete player</x-primary-button>
 </form>
 @endcan
</div>
 </div></br>@endforeach
 </div>
 
 
            </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="viewCommentModal" tabindex="-1" aria-labelledby="commentsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="commentsModalLabel">Coach Comments</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @if (isset($comments) && count($comments) > 0)
                    @foreach ($comments as $comment)
                        <h3>{{ $comment->virsraksts }} ({{ $comment->datums }})</h3>
                        <p>{{ $comment->komentars }}</p>
                        @can('is-coach-or-owner')
 <form method="POST"
 action="{{ route('comment.destroy', ['id' => $comment->id]) }}">
@csrf
@method('DELETE')
<x-primary-button class="ml-4">Delete comment</x-primary-button>
 </form>
 @endcan
 <hr>
                        
                    @endforeach
                @else
                    <p class='text-danger'>There are no comments for this team from the coach!</p>
                @endif
            </div>
        </div>
    </div>
</div>
</x-app-layout>
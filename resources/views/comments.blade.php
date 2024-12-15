<x-app-layout>
<x-slot name="header">
        <h1 class="font-semibold text-xl text-gray-800 leading-tight">
        Trenera norādījumi komandai
</h1>
<p><a href="{{route('players.show', ['id' => $player->id]) }}">Back to profile</a></p>
    </x-slot>

    <div class="py-12">
        
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border border-sky-400 rounded-md overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900">
            <h2 class="border-sky-400 rounded-md font-semibold text-xl text-gray-800 leading-tight">Coach comments</h2></br>
            @if (isset($comments) && count($comments) < 1)

 <p class='error'>There are no comments for this player from coach!</p>
 @else

 @foreach ($comments as $comment)
  
  <h3 class="border-sky-400 rounded-md font-semibold text-xl text-teal-800 leading-tight"> {{$comment->virsraksts}} ({{$comment->datums}})</h3>
  <div class="bg-cyan-200 border border-sky-400 rounded-md overflow-hidden shadow-sm sm:rounded-lg p-2 text-gray-900">
    <div>
 <p>{{ $comment->komentars}}</p>
 </div>
 <div class="flex items-center justify-end mt-4">
 @can('is-coach-or-owner')
 <form method="POST"
 action="{{ route('comment.destroy', ['id' => $comment->id]) }}">
@csrf
@method('DELETE')
<x-primary-button class="ml-4">Delete comment</x-primary-button>
 </form>
 @endcan
 </div>
 </div>
 </br>
 @endforeach
 @endif

            </div>
        </div>
    </div>
</x-app-layout>
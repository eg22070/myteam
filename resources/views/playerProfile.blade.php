<x-app-layout>
<x-slot name="header">
        <h1 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ $player->vards }} {{ $player->uzvards }}
        </h1>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class=" grid grid-cols-2 gap-10 bg-slate-400 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="bg-white border border-sky-400 rounded-md shadow-sm sm:rounded-lg p-6 text-gray-900">
                <h2 class="border-sky-400 rounded-md font-semibold text-xl text-gray-800 leading-tight">Player information</h2>
                    </br> @can('is-coach-or-owner')
                                    <a href="{{route('players.edit', ['player' => $player->id]) }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Edit player information</a>
                    </br>
                    </br>
                    @endcan
                    @if($player->bilde)
                        <div>
                            <img src="{{ asset('storage/app/public/photos' . $player->bilde) }}" alt="Player Photo" class="border rounded-md border transparent"  style="width: 50%; height: 50%;">
                        </div>
                    @endif</br>
                <table style="width:80%"
                class=" py-12 border border-sky-400  max-w">
							<tr>
					<td style="width:50%" class="bg-slate-200">Shirt number:</td>
					<td class="text-center max-w">{{$player->numurs}}</td>
				</tr>
                </table>
                </br>
                <table style="width:80%" class=" py-12 border border-sky-400  max-w">
                <tr>
					<td style="width:50%" class="bg-slate-200">Date of birth:</td>
					<td class="text-center max-w">{{$player->dzimsanas_datums}}</td>
				</tr>
                </table>														
            </div>
            <div class="bg-white border border-sky-400 rounded-md shadow-sm sm:rounded-lg p-6 text-gray-900">
            <h2 class="border-sky-400 rounded-md font-semibold text-xl text-gray-800 leading-tight">Player statistics</h2>
                <table style="width:80%" class="py-12 border border-sky-400  max-w">
                <tr>
					<td style="width:50%" class="bg-slate-200">Missed Trainings this month:</td>
					<td class="text-center max-w">{{$player->nepamekletieTrenini}}</td>
				</tr>
                </table></br>
                <table style="width:80%"
                class=" py-12 border border-sky-400  max-w">
							<tr>
					<td style="width:50%" class="bg-slate-200">Games:</td>
					<td class="text-center max-w">{{$player->speles}}</td>
				</tr>
                </table>
                </br>
                <table style="width:80%" class=" py-12 border border-sky-400  max-w">
                <tr>
					<td style="width:50%" class="bg-slate-200">Goals:</td>
					<td class="text-center max-w">{{$player->varti}}</td>
				</tr>
                </table></br>
                <table style="width:80%" class=" py-12 border border-sky-400  max-w">
                <tr>
					<td style="width:50%" class="bg-slate-200">Assists:</td>
					<td class="text-center max-w">{{$player->piespeles}}</td>
				</tr>
                </table></br>
                <table style="width:80%" class=" py-12 border border-sky-400  max-w">
                <tr>
					<td style="width:50%" class="bg-slate-200">Yellow cards:</td>
					<td class="text-center max-w">{{$player->dzeltenas}}</td>
				</tr>
                </table></br>
                <table style="width:80%" class=" py-12 border border-sky-400  max-w">
                <tr>
					<td style="width:50%" class="bg-slate-200">Red cards:</td>
					<td class="text-center max-w">{{$player->sarkanas}}</td>
				</tr>
                </table></br>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
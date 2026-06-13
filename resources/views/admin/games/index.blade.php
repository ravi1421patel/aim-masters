<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Admin Game Panel
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @foreach($games as $game)

                <div class="bg-white p-6 mb-4 rounded shadow">

                    <h3 class="text-lg font-bold">
                        {{ $game->title }}
                    </h3>

                    <p>Entry Fee: ₹{{ $game->entry_fee }}</p>
                    <p>Players: {{ $game->participants->count() }}</p>
                    <p>Status: {{ $game->status }}</p>

                    <p class="font-semibold text-green-600">
                        Prize Pool: ₹{{ $game->prize_pool }}
                    </p>

                    <div class="flex gap-2 mt-4">

                        <form method="POST" action="{{ route('admin.games.start', $game) }}">
                            @csrf
                            <button class="bg-blue-500 text-white px-3 py-1 rounded">
                                Start
                            </button>
                        </form>

                        <form method="POST" action="{{ route('admin.games.finish', $game) }}">
                            @csrf
                            <button class="bg-yellow-500 text-white px-3 py-1 rounded">
                                Finish
                            </button>
                        </form>

                        <form method="POST" action="{{ route('admin.games.winner', $game) }}">
                            @csrf

                            <select name="winner_id" class="border p-1">
                                @foreach($game->participants as $p)
                                    <option value="{{ $p->user_id }}">
                                        {{ $p->user->name }}
                                    </option>
                                @endforeach
                            </select>

                            <button class="bg-green-600 text-white px-3 py-1 rounded">
                                Declare Winner
                            </button>
                        </form>

                    </div>

                </div>

            @endforeach

        </div>
    </div>

</x-app-layout>
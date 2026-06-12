<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Available Games
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid md:grid-cols-2 gap-6">

                @foreach($games as $game)

                    <div class="bg-white p-6 rounded-lg shadow">

                        <h3 class="text-lg font-bold">
                            {{ $game->title }}
                        </h3>

                        <p class="text-gray-600 mt-2">
                            Entry Fee: ₹{{ $game->entry_fee }}
                        </p>

                        <p class="text-gray-600">
                            Players: {{ $game->participants_count }}/{{ $game->max_players }}
                        </p>

                        <form method="POST" action="{{ route('games.join', $game) }}" class="mt-4">
                            @csrf

                            <button class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
                                Join Game
                            </button>
                        </form>

                    </div>

                @endforeach

            </div>

        </div>
    </div>

</x-app-layout>
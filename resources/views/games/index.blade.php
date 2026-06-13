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

                    <div class="bg-white rounded-xl shadow p-6 mb-4 border game-card"
                         id="game-{{ $game->id }}"
                         data-game-id="{{ $game->id }}">

                        <!-- GAME HEADER -->
                        <div class="flex justify-between items-center">
                            <h2 class="text-xl font-bold">
                                {{ $game->title }}
                            </h2>

                            <span class="px-3 py-1 rounded text-white text-sm
                                {{ $game->status == 'waiting' ? 'bg-green-500' : 'bg-red-500' }}">
                                {{ strtoupper($game->status) }}
                            </span>
                        </div>

                        <!-- ENTRY INFO -->
                        <div class="mt-3 text-gray-600">
                            Entry Fee: <b>₹{{ $game->entry_fee }}</b>
                        </div>

                        <!-- PLAYERS PROGRESS -->
                        @php
                            $filled = $game->participants_count;
                            $total = $game->max_players;
                            $percent = ($total > 0) ? ($filled / $total) * 100 : 0;
                        @endphp

                        <div class="mt-4">

                            <div class="flex justify-between text-sm mb-1">
                                <span>Players</span>

                                <span class="player-text">
                                    <span class="current">{{ $filled }}</span>
                                    /
                                    <span class="max">{{ $total }}</span>
                                </span>
                            </div>

                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="bg-blue-600 h-3 rounded-full progress-fill"
                                     style="width: {{ $percent }}%">
                                </div>
                            </div>

                        </div>

                        <!-- JOIN BUTTON -->
                        <form method="POST"
                              action="{{ route('games.join', $game) }}"
                              class="mt-5 join-form">

                            @csrf

                            <button type="submit"
                                    class="join-btn w-full py-2 rounded bg-blue-600 text-white font-semibold hover:bg-blue-700 transition">

                                Join Game
                            </button>

                        </form>

                    </div>

                @endforeach

            </div>

        </div>
    </div>

    <!-- JOIN BUTTON LOADER -->
    <script>
        document.querySelectorAll('.join-form').forEach(form => {
            form.addEventListener('submit', function () {

                const btn = form.querySelector('.join-btn');

                btn.disabled = true;
                btn.innerText = 'Joining...';
                btn.classList.add('opacity-50');

            });
        });
    </script>

    <!-- REAL-TIME ENGINE -->
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.16.1/dist/echo.iife.js"></script>

    <script>
        window.Echo = new Echo({
            broadcaster: 'reverb',
            key: '{{ env("REVERB_APP_KEY") }}',
            wsHost: window.location.hostname,
            wsPort: 8080,
            forceTLS: false,
            disableStats: true,
        });

        @foreach($games as $game)

        Echo.channel('game.{{ $game->id }}')
            .listen('.game.updated', (e) => {
                updateGameUI(e.gameId);
            });

        @endforeach

        function updateGameUI(gameId) {

            let card = document.getElementById('game-' + gameId);

            if (!card) return;

            fetch('/api/game/' + gameId + '/status')
                .then(res => res.json())
                .then(data => {

                    // update player count
                    card.querySelector('.current').innerText = data.players;

                    // update progress bar
                    let percent = (data.players / data.max_players) * 100;

                    card.querySelector('.progress-fill').style.width = percent + '%';

                    // small animation effect
                    card.style.transform = "scale(1.02)";
                    card.style.transition = "0.2s";

                    setTimeout(() => {
                        card.style.transform = "scale(1)";
                    }, 200);

                });
        }
    </script>

</x-app-layout>
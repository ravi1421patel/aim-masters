<x-app-layout>

    <div class="max-w-7xl mx-auto">

        <h2 class="text-2xl font-bold mb-6">
            Deposit Requests
        </h2>

        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-3 bg-red-100">
                {{ session('error') }}
            </div>
        @endif

        <table class="w-full border">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Amount</th>
                    <th>UTR</th>
                    <th>Status</th>
                    <th>Screenshot</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>

                @foreach($deposits as $deposit)

                    <tr>

                        <td>{{ $deposit->id }}</td>

                        <td>{{ $deposit->user->name }}</td>

                        <td>
                            ₹{{ number_format($deposit->amount, 2) }}
                        </td>

                        <td>{{ $deposit->utr }}</td>

                        <td>{{ $deposit->status }}</td>

                        <td>

                            <a href="{{ asset('storage/' . $deposit->screenshot) }}" target="_blank">
                                View
                            </a>

                        </td>

                        <td>

                            @if($deposit->status === 'pending')

                                <form method="POST" action="{{ route('admin.deposits.approve', $deposit) }}" class="inline">
                                    @csrf

                                    <button class="bg-green-500 text-white px-2 py-1">
                                        Approve
                                    </button>

                                </form>

                                <form method="POST" action="{{ route('admin.deposits.reject', $deposit) }}" class="inline">
                                    @csrf

                                    <button class="bg-red-500 text-white px-2 py-1">
                                        Reject
                                    </button>

                                </form>

                            @endif

                            </td>

                        </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</x-app-layout>
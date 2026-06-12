<x-app-layout>
    <div class="max-w-7xl mx-auto py-6 px-4">

        <h2 class="text-2xl font-bold mb-6">
            Wallet Dashboard
        </h2>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

            <div class="bg-green-500 text-white p-6 rounded-lg shadow">
                <h3 class="text-sm font-medium">
                    Current Balance
                </h3>

                <p class="text-3xl font-bold mt-2">
                    ₹{{ number_format($wallet->balance, 2) }}
                </p>
            </div>

            <div class="bg-orange-500 text-white p-6 rounded-lg shadow">
                <h3 class="text-sm font-medium">
                    Total Deposits
                </h3>

                <p class="text-3xl font-bold mt-2">
                    ₹{{ number_format(
    $deposits->where('status', 'approved')->sum('amount'),
    2
) }}
                </p>
            </div>

            <div class="bg-blue-500 text-white p-6 rounded-lg shadow">
                <h3 class="text-sm font-medium">
                    Total Transactions
                </h3>

                <p class="text-3xl font-bold mt-2">
                    {{ $transactions->count() }}
                </p>
            </div>

        </div>

        <!-- Deposit History -->
        <div class="bg-white shadow rounded-lg p-6 mb-6">

            <h3 class="text-lg font-bold mb-4">
                Deposit History
            </h3>

            <div class="overflow-x-auto">

                <table class="min-w-full border">

                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border px-4 py-2 text-left">
                                Date
                            </th>

                            <th class="border px-4 py-2 text-left">
                                Amount
                            </th>

                            <th class="border px-4 py-2 text-left">
                                Status
                            </th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($deposits as $deposit)

                            <tr>

                                <td class="border px-4 py-2">
                                    {{ $deposit->created_at->format('d M Y H:i') }}
                                </td>

                                <td class="border px-4 py-2">
                                    ₹{{ number_format($deposit->amount, 2) }}
                                </td>

                                <td class="border px-4 py-2">

                                    @if($deposit->status === 'approved')
                                        <span class="text-green-600 font-semibold">
                                            Approved
                                        </span>

                                    @elseif($deposit->status === 'rejected')
                                        <span class="text-red-600 font-semibold">
                                            Rejected
                                        </span>

                                    @else
                                        <span class="text-orange-500 font-semibold">
                                            Pending
                                        </span>
                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="3" class="border px-4 py-4 text-center">
                                    No deposits found.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        <!-- Transaction History -->
        <div class="bg-white shadow rounded-lg p-6">

            <h3 class="text-lg font-bold mb-4">
                Transaction History
            </h3>

            <div class="overflow-x-auto">

                <table class="min-w-full border">

                    <thead class="bg-gray-100">
                        <tr>

                            <th class="border px-4 py-2 text-left">
                                Date
                            </th>

                            <th class="border px-4 py-2 text-left">
                                Type
                            </th>

                            <th class="border px-4 py-2 text-left">
                                Amount
                            </th>

                        </tr>
                    </thead>

                    <tbody>

                        @forelse($transactions as $transaction)

                            <tr>

                                <td class="border px-4 py-2">
                                    {{ $transaction->created_at->format('d M Y H:i') }}
                                </td>

                                <td class="border px-4 py-2">
                                    {{ ucfirst($transaction->type) }}
                                </td>

                                <td class="border px-4 py-2">
                                    ₹{{ number_format($transaction->amount, 2) }}
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="3" class="border px-4 py-4 text-center">
                                    No transactions found.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>
</x-app-layout>
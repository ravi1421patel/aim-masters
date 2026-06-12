<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            Aim Masters Dashboard
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Stats Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">

                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
                    <p class="text-gray-500 text-sm">Wallet Balance</p>
                    <h2 class="text-3xl font-bold text-green-600">
                        ₹{{ number_format($wallet->balance, 2) }}
                    </h2>
                </div>

                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-orange-500">
                    <p class="text-gray-500 text-sm">Pending Deposits</p>
                    <h2 class="text-3xl font-bold text-orange-600">
                        {{ $pendingDeposits }}
                    </h2>
                </div>

                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
                    <p class="text-gray-500 text-sm">Total Deposited</p>
                    <h2 class="text-3xl font-bold text-blue-600">
                        ₹{{ number_format($totalDeposited, 2) }}
                    </h2>
                </div>

                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-500">
                    <p class="text-gray-500 text-sm">Total Withdrawn</p>
                    <h2 class="text-3xl font-bold text-red-600">
                        ₹{{ number_format($totalWithdrawn, 2) }}
                    </h2>
                </div>

                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
                    <p class="text-gray-500 text-sm">Total Winnings</p>
                    <h2 class="text-3xl font-bold text-purple-600">
                        ₹{{ number_format($totalWinnings, 2) }}
                    </h2>
                </div>

            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-wrap gap-4 mb-8">

                <a href="{{ route('deposit.create') }}"
                    class="inline-flex items-center px-6 py-3 bg-orange-500 border border-transparent rounded-md font-semibold text-white uppercase tracking-widest hover:bg-orange-600 transition">
                    + Add Money
                </a>

                <a href="{{ route('withdraw.create') }}"
                    class="inline-flex items-center px-6 py-3 bg-green-500 border border-transparent rounded-md font-semibold text-white uppercase tracking-widest hover:bg-green-600 transition">
                    Withdraw
                </a>

            </div>

            {{-- Recent Transactions --}}
            <div class="bg-white shadow rounded-lg">

                <div class="p-6 border-b">
                    <h3 class="text-lg font-semibold">
                        Recent Transactions
                    </h3>
                </div>

                <div class="overflow-x-auto">

                    <table class="min-w-full divide-y divide-gray-200">

                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Date
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Type
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Amount
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Status
                                </th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">

                            @forelse($transactions as $transaction)

                                <tr>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $transaction->created_at->format('d M Y') }}
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap capitalize">
                                        {{ str_replace('_', ' ', $transaction->type) }}
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap font-semibold">
                                        ₹{{ number_format($transaction->amount, 2) }}
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">

                                        @if($transaction->status === 'approved')
                                            <span class="px-2 py-1 text-xs font-semibold rounded bg-green-100 text-green-700">
                                                Approved
                                            </span>
                                        @elseif($transaction->status === 'pending')
                                            <span class="px-2 py-1 text-xs font-semibold rounded bg-orange-100 text-orange-700">
                                                Pending
                                            </span>
                                        @elseif($transaction->status === 'rejected')
                                            <span class="px-2 py-1 text-xs font-semibold rounded bg-red-100 text-red-700">
                                                Rejected
                                            </span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-semibold rounded bg-gray-100 text-gray-700">
                                                {{ ucfirst($transaction->status) }}
                                            </span>
                                        @endif

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="4" class="px-6 py-6 text-center text-gray-500">
                                        No transactions found.
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>
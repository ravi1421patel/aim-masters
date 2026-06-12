<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Admin Dashboard
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                {{-- Total Deposits --}}
                <div class="bg-white p-6 rounded-lg shadow">
                    <p class="text-gray-500 text-sm">Total Deposits</p>
                    <h3 class="text-2xl font-bold text-green-600 mt-2">
                        ₹{{ $totalDeposits ?? 0 }}
                    </h3>
                </div>

                {{-- Pending Deposits --}}
                <div class="bg-white p-6 rounded-lg shadow">
                    <p class="text-gray-500 text-sm">Pending Deposits</p>
                    <h3 class="text-2xl font-bold text-yellow-500 mt-2">
                        {{ $pendingDeposits ?? 0 }}
                    </h3>
                </div>

                {{-- Total Withdrawals --}}
                <div class="bg-white p-6 rounded-lg shadow">
                    <p class="text-gray-500 text-sm">Total Withdrawals</p>
                    <h3 class="text-2xl font-bold text-red-600 mt-2">
                        ₹{{ $totalWithdrawals ?? 0 }}
                    </h3>
                </div>

                {{-- Wallet Balance --}}
                <div class="bg-white p-6 rounded-lg shadow">
                    <p class="text-gray-500 text-sm">Platform Wallet Balance</p>
                    <h3 class="text-2xl font-bold text-blue-600 mt-2">
                        ₹{{ $platformBalance ?? 0 }}
                    </h3>
                </div>

            </div>

            {{-- Quick Links --}}
            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">

                <a href="{{ route('admin.deposits') }}"
                    class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
                    <h3 class="text-lg font-semibold">Manage Deposits</h3>
                    <p class="text-gray-500 text-sm mt-2">
                        Approve or reject user deposit requests
                    </p>
                </a>

                <a href="{{ route('admin.withdraws') }}"
                    class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
                    <h3 class="text-lg font-semibold">Manage Withdrawals</h3>
                    <p class="text-gray-500 text-sm mt-2">
                        Approve or reject withdrawal requests
                    </p>
                </a>

            </div>

        </div>
    </div>

</x-app-layout>
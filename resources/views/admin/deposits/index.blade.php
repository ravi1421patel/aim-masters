<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Deposit Requests
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Alerts --}}
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white shadow-sm rounded-lg overflow-hidden">

                <div class="p-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-700">
                        All Deposit Requests
                    </h3>
                </div>

                {{-- Table Wrapper (IMPORTANT for mobile scroll) --}}
                <div class="overflow-x-auto">

                    <table class="min-w-full divide-y divide-gray-200">

                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    ID
                                </th>

                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    User
                                </th>

                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Amount
                                </th>

                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Proof
                                </th>

                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Status
                                </th>

                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Date
                                </th>

                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Action
                                </th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-100">

                        @forelse($deposits as $deposit)

                            <tr class="hover:bg-gray-50">

                                <td class="px-4 py-3 text-sm text-gray-700">
                                    {{ $deposit->id }}
                                </td>

                                <td class="px-4 py-3">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $deposit->user?->name }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $deposit->user?->email }}
                                    </div>
                                </td>

                                <td class="px-4 py-3 text-sm font-semibold text-gray-800">
                                    ₹{{ number_format($deposit->amount, 2) }}
                                </td>

                                <td class="px-4 py-3">

                                    @if($deposit->screenshot)
                                        <a href="{{ asset('storage/' . $deposit->screenshot) }}"
                                           target="_blank"
                                           class="inline-block px-3 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700">
                                            View
                                        </a>
                                    @else
                                        <span class="text-gray-400 text-sm">No Proof</span>
                                    @endif

                                </td>

                                <td class="px-4 py-3">

                                    @if($deposit->status == 'approved')
                                        <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded-full">
                                            Approved
                                        </span>

                                    @elseif($deposit->status == 'rejected')
                                        <span class="px-2 py-1 text-xs bg-red-100 text-red-700 rounded-full">
                                            Rejected
                                        </span>

                                    @else
                                        <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-700 rounded-full">
                                            Pending
                                        </span>
                                    @endif

                                </td>

                                <td class="px-4 py-3 text-sm text-gray-600">
                                    {{ $deposit->created_at->format('d M Y, h:i A') }}
                                </td>

                                <td class="px-4 py-3">

                                    @if($deposit->status === 'pending')

                                        <div class="flex flex-col sm:flex-row gap-2">

                                            <form method="POST"
                                                  action="{{ route('admin.deposits.approve', $deposit) }}">
                                                @csrf
                                                <button type="submit"
                                                        onclick="return confirm('Approve deposit?')"
                                                        class="w-full sm:w-auto px-3 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700">
                                                    Approve
                                                </button>
                                            </form>

                                            <form method="POST"
                                                  action="{{ route('admin.deposits.reject', $deposit) }}">
                                                @csrf
                                                <button type="submit"
                                                        onclick="return confirm('Reject deposit?')"
                                                        class="w-full sm:w-auto px-3 py-1 bg-red-600 text-white text-xs rounded hover:bg-red-700">
                                                    Reject
                                                </button>
                                            </form>

                                        </div>

                                    @else
                                        <span class="text-gray-400 text-sm">Done</span>
                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="7" class="px-4 py-6 text-center text-gray-500">
                                    No deposit requests found.
                                </td>
                            </tr>

                        @endforelse

                        </tbody>

                    </table>

                </div>

                {{-- Pagination --}}
                <div class="p-4">
                    {{ $deposits->links() }}
                </div>

            </div>
        </div>
    </div>

</x-app-layout>
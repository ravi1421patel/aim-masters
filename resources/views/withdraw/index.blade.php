<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Deposit Requests
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 rounded-md bg-green-100 p-4 text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 rounded-md bg-red-100 p-4 text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

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

                            <tbody class="bg-white divide-y divide-gray-200">

                                @forelse($deposits as $deposit)

                                    <tr>

                                        <td class="px-4 py-4">
                                            {{ $deposit->id }}
                                        </td>

                                        <td class="px-4 py-4">
                                            <div class="font-medium">
                                                {{ $deposit->user?->name }}
                                            </div>

                                            <div class="text-sm text-gray-500">
                                                {{ $deposit->user?->email }}
                                            </div>
                                        </td>

                                        <td class="px-4 py-4 font-semibold">
                                            ₹{{ number_format($deposit->amount, 2) }}
                                        </td>

                                        <td class="px-4 py-4">
                                            @if($deposit->payment_proof)
                                                <a href="{{ asset('storage/' . $deposit->payment_proof) }}" target="_blank"
                                                    class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">
                                                    View Proof
                                                </a>
                                            @else
                                                <span class="text-gray-400">
                                                    No Proof
                                                </span>
                                            @endif
                                        </td>

                                        <td class="px-4 py-4">

                                            @if($deposit->status === 'approved')
                                                <span
                                                    class="px-3 py-1 text-xs font-semibold bg-green-100 text-green-700 rounded-full">
                                                    Approved
                                                </span>

                                            @elseif($deposit->status === 'rejected')
                                                <span
                                                    class="px-3 py-1 text-xs font-semibold bg-red-100 text-red-700 rounded-full">
                                                    Rejected
                                                </span>

                                            @else
                                                <span
                                                    class="px-3 py-1 text-xs font-semibold bg-yellow-100 text-yellow-700 rounded-full">
                                                    Pending
                                                </span>
                                            @endif

                                        </td>

                                        <td class="px-4 py-4">
                                            {{ $deposit->created_at->format('d M Y h:i A') }}
                                        </td>

                                        <td class="px-4 py-4">

                                            @if($deposit->status === 'pending')

                                                <div class="flex gap-2">

                                                    <form method="POST"
                                                        action="{{ route('admin.deposits.approve', $deposit) }}">
                                                        @csrf

                                                        <button type="submit" onclick="return confirm('Approve this deposit?')"
                                                            class="px-3 py-2 bg-green-600 text-white text-sm rounded-md hover:bg-green-700">
                                                            Approve
                                                        </button>
                                                    </form>

                                                    <form method="POST" action="{{ route('admin.deposits.reject', $deposit) }}">
                                                        @csrf

                                                        <button type="submit" onclick="return confirm('Reject this deposit?')"
                                                            class="px-3 py-2 bg-red-600 text-white text-sm rounded-md hover:bg-red-700">
                                                            Reject
                                                        </button>
                                                    </form>

                                                </div>

                                            @else

                                                <span class="text-gray-400">
                                                    Completed
                                                </span>

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

                    <div class="mt-6">
                        {{ $deposits->links() }}
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
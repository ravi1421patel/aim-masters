<x-app-layout>

    <div class="max-w-2xl mx-auto">

        <h2 class="text-2xl font-bold mb-6">
            Withdraw Funds
        </h2>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                <ul class="list-disc ml-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white shadow rounded p-6">

            <form method="POST" action="{{ route('withdraw.store') }}">
                @csrf

                <div class="mb-4">

                    <label class="block mb-2 font-medium">
                        Amount
                    </label>

                    <div class="flex gap-2 mb-3">

                        <button type="button" onclick="setAmount(100)"
                            class="px-4 py-2 bg-orange-500 text-white rounded">
                            ₹100
                        </button>

                        <button type="button" onclick="setAmount(200)"
                            class="px-4 py-2 bg-orange-500 text-white rounded">
                            ₹200
                        </button>

                        <button type="button" onclick="setAmount(500)"
                            class="px-4 py-2 bg-orange-500 text-white rounded">
                            ₹500
                        </button>

                    </div>

                    <input id="amount" type="number" name="amount" min="100" class="w-full border rounded px-3 py-2">
                </div>

                <div class="mb-4">
                    <label class="block mb-2 font-medium">
                        UPI ID
                    </label>

                    <input type="text" name="upi_id" class="w-full border rounded px-3 py-2">
                </div>

                <button type="submit" class="bg-emerald-500 text-white px-6 py-2 rounded">
                    Submit Withdraw Request
                </button>

            </form>

        </div>

    </div>

    <script>
        function setAmount(amount) {
            document.getElementById('amount').value = amount;
        }
    </script>

</x-app-layout>
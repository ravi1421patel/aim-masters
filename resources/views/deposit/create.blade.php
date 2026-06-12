<x-app-layout>

    <div class="max-w-2xl mx-auto">

        <h2 class="text-2xl font-bold mb-6">
            Deposit Funds
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

        <div class="bg-white shadow rounded p-6 mb-6">
            <h4 class="text-lg font-semibold mb-2">
                UPI Payment
            </h4>

            <p>
                UPI ID: aimmasters@upi
            </p>
        </div>

        <form id="depositForm" method="POST" action="{{ route('deposit.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">

                <label class="block mb-2 font-medium">
                    Amount
                </label>

                <div class="flex flex-wrap gap-2 mb-3">

                    <button type="button" onclick="setAmount(10)"
                        class="px-4 py-2 bg-orange-500 text-white rounded hover:bg-orange-600 transition">
                        ₹10
                    </button>

                    <button type="button" onclick="setAmount(20)"
                        class="px-4 py-2 bg-orange-500 text-white rounded hover:bg-orange-600 transition">
                        ₹20
                    </button>

                    <button type="button" onclick="setAmount(50)"
                        class="px-4 py-2 bg-orange-500 text-white rounded hover:bg-orange-600 transition">
                        ₹50
                    </button>

                    <button type="button" onclick="setAmount(100)"
                        class="px-4 py-2 bg-orange-500 text-white rounded hover:bg-orange-600 transition">
                        ₹100
                    </button>

                    <button type="button" onclick="setAmount(500)"
                        class="px-4 py-2 bg-orange-500 text-white rounded hover:bg-orange-600 transition">
                        ₹500
                    </button>

                </div>

                <input id="amount" type="number" name="amount" min="10" step="1" placeholder="Enter amount"
                    class="w-full border rounded px-3 py-2">

            </div>
            <div class="mb-4">
                <label class="block mb-2 font-medium">
                    UTR Number
                </label>

                <input type="text" name="utr" class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-medium">
                    Screenshot
                </label>

                <input type="file" name="screenshot" class="w-full border rounded px-3 py-2">
            </div>

            <button type="submit" class="bg-emerald-500 text-white px-6 py-2 rounded hover:bg-emerald-600 transition">
                Submit Deposit
            </button>

        </form>

    </div>
    <script>
        function setAmount(amount) {
            document.getElementById('amount').value = amount;
        }
        document.addEventListener('DOMContentLoaded', () => {

            const form = document.getElementById('depositForm');
            const button = document.getElementById('depositSubmitBtn');

            form.addEventListener('submit', () => {

                button.disabled = true;
                button.textContent = 'Processing...';

            });

        });
    </script>
</x-app-layout>
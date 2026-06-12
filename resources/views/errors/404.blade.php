<x-app-layout>
    <div class="max-w-7xl mx-auto py-10 px-4">

        <div class="bg-white rounded-xl shadow p-10 text-center">

            <h1 class="text-6xl font-bold text-red-500">
                404
            </h1>

            <h2 class="text-2xl font-semibold mt-4">
                Page Not Found
            </h2>

            <p class="mt-2 text-gray-600">
                The page you are looking for does not exist.
            </p>

            <a href="{{ route('dashboard') }}" class="inline-block mt-6 px-6 py-3 bg-green-600 text-white rounded-lg">
                Go To Dashboard
            </a>

        </div>

    </div>
</x-app-layout>
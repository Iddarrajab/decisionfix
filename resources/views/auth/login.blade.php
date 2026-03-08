<x-app-layout title="Login">
    <x-slot name="heading">
        Login
    </x-slot>

    <x-slot name="body">
        @if(session('error'))
        <p class="text-sm text-red-600 mb-4">{{ session('error') }}</p>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf

            {{-- Email --}}
            <label for="email" class="block font-medium mb-1">Email:</label>
            <input
                type="email"
                name="email"
                id="email"
                value="{{ old('email') }}"
                class="border rounded px-2 py-1 w-full mb-3"
                required>
            @error('email')
            <p class="text-sm text-red-600 mt-1 mb-3">{{ $message }}</p>
            @enderror

            {{-- Password --}}
            <label for="password" class="block font-medium mb-1">Password:</label>
            <input
                type="password"
                name="password"
                id="password"
                class="border rounded px-2 py-1 w-full mb-6"
                required>
            @error('password')
            <p class="text-sm text-red-600 mt-1 mb-3">{{ $message }}</p>
            @enderror

            <x-button class="mt-6">
                Login
            </x-button>
        </form>
    </x-slot>
</x-app-layout>
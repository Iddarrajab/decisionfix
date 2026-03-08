<x-app-layout title="{{ $page_meta['title'] }}">
    <x-slot name="heading">
        {{ $page_meta['title'] }}
    </x-slot>

    <x-slot name="body">
        <h1 class="text-xl font-semibold mb-4">{{ $page_meta['title'] }}</h1>

        @if(session('success'))
        <p class="text-green-600 mb-4">{{ session('success') }}</p>
        @endif

        <form method="POST" action="{{ $page_meta['url'] }}">
            @csrf
            @method($page_meta['method'])

            {{-- Nama --}}
            <label for="name" class="block font-medium mb-1">Nama:</label>
            <input
                type="text"
                name="name"
                id="name"
                value="{{ old('name', $admin->name ?? '') }}"
                class="border rounded px-2 py-1 w-full mb-3">
            @error('name')
            <p class="text-sm text-red-600 mt-1 mb-3">{{ $message }}</p>
            @enderror

            {{-- Email --}}
            <label for="email" class="block font-medium mb-1">Email:</label>
            <input
                type="email"
                name="email"
                id="email"
                value="{{ old('email', $admin->email ?? '') }}"
                class="border rounded px-2 py-1 w-full mb-3">
            @error('email')
            <p class="text-sm text-red-600 mt-1 mb-3">{{ $message }}</p>
            @enderror

            {{-- Password --}}
            <label for="password" class="block font-medium mb-1">Password:</label>
            <input
                type="password"
                name="password"
                id="password"
                class="border rounded px-2 py-1 w-full mb-6">
            @error('password')
            <p class="text-sm text-red-600 mt-1 mb-3">{{ $message }}</p>
            @enderror

            <x-button class="mt-6">
                {{ $page_meta['button'] }}
            </x-button>
        </form>
    </x-slot>
</x-app-layout>
<!DOCTYPE html>
<html class="h-full bg-white" lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>
        @isset($title)
        {{ $title }} / Laravel 12
        @else
        Laravel 12
        @endisset
    </title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine -->
    <script src="//unpkg.com/alpinejs" defer></script>

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


</head>

<header class="bg-white shadow flex items-center justify-between px-4 h-16 md:ml-64">

    <!-- Tombol Hamburger (hanya muncul di HP) -->
    <button @click="open = true" class="md:hidden text-gray-700">
        <i class="fa fa-bars text-xl"></i>
    </button>



</header>

<body class="h-full bg-gray-100" x-data="{ open: false }">



    <div class="min-h-screen flex flex-col">

        <div class="flex flex-1">

            <!-- OVERLAY MOBILE -->
            <div x-show="open"
                @click="open = false"
                class="fixed inset-0 bg-black/50 z-40 md:hidden">
            </div>

            <!-- SIDEBAR (FIXED) -->
            <aside
                class="w-64 bg-indigo-700 text-white flex flex-col
                   fixed inset-y-0 left-0 z-50
                   transform transition duration-300"
                :class="open ? 'translate-x-0' : '-translate-x-full md:translate-x-0'">

                <!-- Header -->
                <div class="flex items-center justify-between h-16 px-6 border-b border-indigo-800">
                    <span class="font-bold">Sistem Pakar</span>

                    <button @click="open = false" class="md:hidden">
                        <i class="fa fa-times"></i>
                    </button>
                </div>

                <!-- MENU -->
                <nav class="flex-1 flex flex-col overflow-y-auto py-4">

                    @auth

                    <div class="space-y-1 px-3">
                        <a href="/" class="block px-3 py-2 rounded hover:bg-indigo-600">Dashboard</a>
                        <a href="{{ route('gejala.index') }}" class="block px-3 py-2 rounded hover:bg-indigo-600">Gejala</a>
                        <a href="{{ route('penyakit.index') }}" class="block px-3 py-2 rounded hover:bg-indigo-600">Penyakit</a>
                        <a href="{{ route('aturan.index') }}" class="block px-3 py-2 rounded hover:bg-indigo-600">Aturan</a>
                        <a href="{{ route('decision-nodes.index') }}" class="block px-3 py-2 rounded hover:bg-indigo-600">Decision Tree</a>
                        <a href="{{ route('diagnosa.index') }}" class="block px-3 py-2 rounded hover:bg-indigo-600">Hasil Diagnosa</a>
                    </div>

                    <form action="{{ route('logout') }}" method="POST" class="px-3 mt-6">
                        @csrf
                        <button class="w-full py-2 bg-red-600 hover:bg-red-700 rounded">
                            Logout
                        </button>
                    </form>

                    <div class="flex items-center px-3 mt-auto pb-6">
                        <img class="w-10 h-10 rounded-full"
                            src="https://images.unsplash.com/photo-1531427186611-ecfd6d936c79">
                        <span class="ml-3 text-sm">{{ Auth::user()->name }}</span>
                    </div>

                    @else

                    <div class="space-y-1 px-3">
                        <a href="/" class="block px-3 py-2 rounded hover:bg-indigo-600">Dashboard</a>
                        <a href="{{ route('diagnosa.create') }}" class="block px-3 py-2 rounded hover:bg-indigo-600">Input Diagnosa</a>
                        <a href="{{ route('diagnosa.index') }}" class="block px-3 py-2 rounded hover:bg-indigo-600">Hasil Diagnosa</a>
                    </div>

                    <div class="mt-auto px-3 pb-6">
                        <a href="{{ route('login') }}"
                            class="block text-center py-2 bg-indigo-900 hover:bg-indigo-800 rounded">
                            Login
                        </a>
                    </div>

                    @endauth

                </nav>
            </aside>

            <!-- MAIN CONTENT -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8 md:ml-64">
                <div class="bg-white rounded shadow p-6">
                    {{ $body }}
                </div>
            </main>

        </div>

    </div>

</body>

</html>
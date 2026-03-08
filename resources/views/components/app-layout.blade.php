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

    <script src="https://cdn.tailwindcss.com"></script>

    <!-- FontAwesome Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>

<body class="h-full bg-white text-gray-900">
    <div class="min-h-screen flex flex-col">

        <header class="bg-white shadow-sm w-full"></header>

        <div class="flex flex-1 min-h-0">

            <!-- Sidebar -->
            <aside class="fixed top-0 left-0 z-50 flex flex-col w-64 h-screen bg-indigo-700 text-white border-r border-indigo-800 ">

                <!-- Brand -->
                <div class="flex items-center h-16 px-6 text-lg font-bold border-b border-indigo-800">
                    Sistem Pakar
                </div>

                <!-- Menu -->
                <nav class="flex-1 overflow-y-auto py-4 flex flex-col "> <!-- tambah flex-col -->

                    @auth

                    <!-- Wrapper menu agar spacing rapi -->
                    <div class="space-y-4">
                        <a href="/" class="flex items-center px-6 py-2 transition hover:bg-indigo-600">
                            <span class="ml-1">Dashboard</span>
                        </a>

                        <a href="{{ route('gejala.index') }}" class="flex items-center px-6 py-2 transition hover:bg-indigo-600">
                            <span class="ml-1">Gejala</span>
                        </a>

                        <a href="{{ route('penyakit.index') }}" class="flex items-center px-6 py-2 transition hover:bg-indigo-600">
                            <span class="ml-1">Penyakit</span>
                        </a>

                        <a href="{{ route('aturan.index') }}" class="flex items-center px-6 py-2 transition hover:bg-indigo-600">
                            <span class="ml-1">Aturan</span>
                        </a>

                        <a href="{{ route('decision-nodes.index') }}" class="flex items-center px-6 py-2 transition hover:bg-indigo-600">
                            <span class="ml-1">Decision Tree</span>
                        </a>

                        <a href="{{ route('diagnosa.index') }}" class="flex items-center px-6 py-2 transition hover:bg-indigo-600">
                            <span class="ml-1">Hasil Diagnosa</span>
                        </a>
                    </div>

                    <!-- Tombol Logout di bawah menu -->
                    <form action="{{ route('logout') }}" method="POST" class="px-6 mt-6">
                        @csrf
                        <button type="submit"
                            class="w-full px-4 py-2 text-center bg-red-600 hover:bg-red-700 rounded transition">
                            Logout
                        </button>
                    </form>

                    <!-- Profil di paling bawah -->
                    <div class="flex items-center px-6 mt-auto pb-6">
                        <img class="w-10 h-10 rounded-full"
                            src="https://images.unsplash.com/photo-1531427186611-ecfd6d936c79?auto=format&fit=crop&w=200&q=60"
                            alt="avatar">
                        <span class="ml-3 text-sm font-medium">{{ Auth::user()->name }}</span>
                    </div>

                    @else

                    <div class="space-y-4">
                        <a href="/" class="flex items-center px-6 py-2 transition hover:bg-indigo-600">
                            <span class="ml-1">Dashboard</span>
                        </a>

                        <a href="{{ route('diagnosa.create') }}" class="flex items-center px-6 py-2 transition hover:bg-indigo-600">
                            <span class="ml-1">Input Diagnosa</span>
                        </a>

                        <a href="{{ route('diagnosa.index') }}" class="flex items-center px-6 py-2 transition hover:bg-indigo-600">
                            <span class="ml-1">Hasil Diagnosa</span>
                        </a>
                    </div>

                    <!-- Login berada paling bawah -->
                    <div class="mt-auto px-6 pb-6">
                        <a href="{{ route('login') }}"
                            class="block text-center w-full px-4 py-3 bg-indigo-900 hover:bg-indigo-800 rounded-md transition">
                            Login
                        </a>
                    </div>

                    @endauth

                </nav>

            </aside>


            <!-- Konten utama -->
            <main class="flex-1 bg-white px-4 py-6 sm:px-6 lg:px-8 overflow-x-auto ml-64">

                <!-- DASHBOARD CARDS -->
                

                <!-- <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                    <div class="bg-blue-600 text-white p-5 rounded-lg shadow flex justify-between items-center">
                        <div>
                            <h3 class="text-3xl font-bold">150</h3>
                            <p class="opacity-90">New Orders</p>
                        </div>
                        <i class="fas fa-shopping-cart text-5xl opacity-40"></i>
                    </div>

                    <div class="bg-green-600 text-white p-5 rounded-lg shadow flex justify-between items-center">
                        <div>
                            <h3 class="text-3xl font-bold">53%</h3>
                            <p class="opacity-90">Bounce Rate</p>
                        </div>
                        <i class="fas fa-chart-line text-5xl opacity-40"></i>
                    </div>

                    <div class="bg-yellow-500 text-white p-5 rounded-lg shadow flex justify-between items-center">
                        <div>
                            <h3 class="text-3xl font-bold">44</h3>
                            <p class="opacity-90">User Registrations</p>
                        </div>
                        <i class="fas fa-user-plus text-5xl opacity-40"></i>
                    </div>

                    <div class="bg-red-600 text-white p-5 rounded-lg shadow flex justify-between items-center">
                        <div>
                            <h3 class="text-3xl font-bold">65</h3>
                            <p class="opacity-90">Unique Visitors</p>
                        </div>
                        <i class="fas fa-chart-pie text-5xl opacity-40"></i>
                    </div>

                </div> -->

                <!-- Tempat konten halaman lain -->
                <div class="mt-8">
                    {{ $body }}
                </div>

            </main>
        </div>

    </div>
</body>

</html>
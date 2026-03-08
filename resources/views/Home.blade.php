<x-app-layout title="Home">
    <x-slot name="heading"> heading
    </x-slot>

    <x-slot name="body">
        <h1 class="text-2xl font-bold mb-6">Dashboard</h1>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">


            <div class="bg-blue-600 text-white p-5 rounded-lg shadow flex justify-between items-center h-37">
                <div>
                    <h3 class="text-4xl font-bold">4,8 juta</h3>
                    <p class="opacity-90 text-base">Kucing Peliharaan Di Indonesia</p>
                </div>
                <i class="fas fa-cat text-7xl opacity-40"></i>
            </div>

            <div class="bg-green-600 text-white p-5 rounded-lg shadow flex justify-between items-center h-40">
                <div>
                    <h3 class="text-4xl font-bold">53%</h3>
                    <p class="opacity-90 text-base">Keluarga Yang Memelihara Kucing</p>
                </div>
                <i class="fas fa-people-roof text-7xl opacity-40"></i>
            </div>

            <div class="bg-yellow-500 text-white p-5 rounded-lg shadow flex justify-between items-center h-40">
                <div>
                    <h3 class="text-4xl font-bold">13.500</h3>
                    <p class="opacity-90 text-base">Dokter Hewan Di Indonesia</p>
                </div>
                <i class="fas fa-globe text-7xl opacity-40"></i>
            </div>

            <div class="bg-red-600 text-white p-5 rounded-lg shadow flex justify-between items-center h-40">
                <div>
                    <h3 class="text-4xl font-bold">8.900</h3>
                    <p class="opacity-90 text-base">Klinik Hewan Di Indonesia</p>
                </div>
                <i class="fas fa-chart-pie text-7xl opacity-40"></i>
            </div>

        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-8">

            <!-- Bagian Teks (60%) -->
            <div class="lg:col-span-2 bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Penyakit pada kucing</h2>
                <p class="text-gray-700 leading-relaxed">
                    Kucing merupakan salah satu hewan peliharaan yang banyak dipelihara dan memiliki kedekatan emosional dengan manusia. Namun, kesehatan kucing seringkali kurang diperhatikan sehingga rentan terinfeksi berbagai penyakit, baik yang disebabkan oleh virus, bakteri, parasit, maupun faktor lingkungan. Beberapa penyakit seperti Feline Panleukopenia, Feline Calicivirus, Rhinotracheitis, cacingan, scabies, dan diare akut merupakan penyakit yang umum terjadi dan dapat berdampak serius bagi kesehatan kucing. Gejala yang ditimbulkan dapat meliputi menurunnya nafsu makan, gangguan pernapasan, gangguan pencernaan, kerusakan kulit, hingga penurunan imunitas. Kondisi ini dapat membahayakan kucing, terutama jika tidak didiagnosis dan ditangani secara tepat. Oleh karena itu, pemahaman mengenai jenis penyakit, gejala klinis, serta upaya pencegahan sangat penting untuk menjaga kesehatan kucing dan mencegah penyebaran penyakit antar hewan.
                </p>
            </div>

            <!-- Bagian Map (40%) -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Lokasi Klinik</h2>
                <div class="w-full h-64 rounded overflow-hidden">
                    <!-- Embed Map -->
                    <iframe
                        class="w-full h-full"
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3477.1447380827562!2d108.4678934749951!3d-6.7591510932374606!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6f1f103b95ca1f%3A0x74c80819f9a1c809!2sUPT%20PUSKESWAN%20TENGAH%20TANI!5e1!3m2!1sid!2sid!4v1751566581089!5m2!1sid!2sid"
                        style="border:0;" allowfullscreen="" loading="lazy">
                    </iframe>
                </div>
            </div>

        </div>

    </x-slot>

</x-app-layout>
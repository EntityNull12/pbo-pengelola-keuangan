<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body class="bg-gray-800 text-white">
    <!-- Hero Section -->
    <div class="relative bg-gray-900">
        <div class="absolute inset-0 bg-cover bg-center opacity-60" style="background-image: url('https://source.unsplash.com/1600x900/?nature,technology');"></div>
        <div class="relative py-32 px-6 sm:py-48 sm:px-12 lg:px-24 text-center">
            <h1 class="text-5xl font-extrabold text-white sm:text-6xl">KELUANG</h1>
            <p class="mt-4 text-xl text-gray-300 sm:mt-6">Kelola Uang anda!!!</p>
            <div class="flex gap-4 justify-center">
                <a href="/login" class="mt-8 inline-block bg-yellow-600 hover:bg-yellow-500 text-white font-bold py-3 px-6 rounded-lg shadow-lg transition duration-300">Saya ingin kelola uang!</a>
                <a href="#more" class="mt-8 inline-block bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-3 px-6 rounded-lg shadow-lg transition duration-300">Apa itu?</a>
            </div>
        </div>
    </div>

    <!-- About Content Section -->
    <div id="more" class="py-16 bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-white">Apa itu KELUANG?</h2>
                <p class="mt-4 text-lg text-gray-400">KELUANG adalah aplikasi pengelola uang sederhana yang dirancang untuk membantu Anda mengatur keuangan pribadi secara praktis dan efisien. Dengan KELUANG, Anda memungkinkan mencatat pemasukan dan pengeluaran dengan cepat serta memantau keuangan harian.</p>
            </div>
            <h5 class="mb-8 text-center font-semibold text-3xl">Dibuat oleh :</h5>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <div class="text-center">
                    <!-- <img class="mx-auto w-24 h-24 rounded-full mb-4" src="https://source.unsplash.com/100x100/?team,technology" alt="Our Mission"> -->
                    <h3 class="text-2xl font-semibold text-white">Enda Adittia Saputra</h3>
                    <p class="mt-2 text-gray-400">223443009</p>
                </div>
                <div class="text-center">
                    <!-- <img class="mx-auto w-24 h-24 rounded-full mb-4" src="https://source.unsplash.com/100x100/?vision,tech" alt="Our Vision"> -->
                    <h3 class="text-2xl font-semibold text-white">Khalis Pancaroba</h3>
                    <p class="mt-2 text-gray-400">2234430</p>
                </div>
                <div class="text-center">
                    <!-- <img class="mx-auto w-24 h-24 rounded-full mb-4" src="https://source.unsplash.com/100x100/?values,teamwork" alt="Our Values"> -->
                    <h3 class="text-2xl font-semibold text-white">Muhammad Farid Gunawan</h3>
                    <p class="mt-2 text-gray-400">2234430</p>
                </div>
            </div>
            <!-- Back Button -->
            <!-- <div class="text-center mt-12">
                <a href="/dashboard" class="inline-block bg-yellow-600 hover:bg-yellow-500 text-white font-bold py-3 px-6 rounded-lg shadow-lg transition duration-300">Kembali ke Dashboard</a>
            </div> -->
        </div>
    </div>
</body>
</html>

<h2 class="text-center text-lg font-bold">Pendaftaran</h2>

<form class="max-w-sm mx-auto" method="post" action="/register/save">
    <div class="mb-5">
        <label for="nama" class="block mb-2 text-sm font-medium text-gray-900">Nama</label>
        <input type="text" name="nama" id="nama" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required />
    </div>
    <div class="mb-5">
        <label for="username" class="block mb-2 text-sm font-medium text-gray-900">Username</label>
        <input type="text" name="username" id="username" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required />
        
        <!-- Menampilkan pesan kesalahan di bawah textbox username -->
        <?php if (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['username'])): ?>
            <div class="text-red-500 text-sm mt-1">
                <?= esc(session()->getFlashdata('errors')['username']) ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="mb-5">
        <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Password</label>
        <input type="password" name="password" id="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required />
    </div>

    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Daftar</button>
</form>

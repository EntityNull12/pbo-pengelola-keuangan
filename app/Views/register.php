<h3 class="font-bold text-center my-12 text-6xl">Pendaftaran</h3>
<?php
// Jika user sudah login, redirect ke dashboard
if (session()->get('logged_in')) {
    header('Location: /dashboard');
    exit;
}
?>
<form class="max-w-2xl mx-auto mt-4" method="post" action="/register/save">
<?= csrf_field() ?>
  <div class="mb-5">
    <label for="nama" class="block mb-2 text-lg font-semibold">Nama</label>
    <input type="text" name="nama" id="nama" class="bg-transparent border border-yellow-200 text-yellow-200 text-lg rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5" placeholder="John Doe" required />
  </div>
  <div class="mb-5">
    <label for="username" class="block mb-2 text-lg font-semibold">Username</label>
    <input type="text" name="username" id="username" class="bg-transparent border border-yellow-200 text-yellow-200 text-lg rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5" placeholder="John Doe" required />

    <!-- Menampilkan pesan kesalahan di bawah textbox username -->
    <?php if (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['username'])): ?>
        <div class="text-red-500 text-sm mt-1">
            <?= esc(session()->getFlashdata('errors')['username']) ?>
        </div>
    <?php endif; ?>
  </div>
  <div class="mb-5">
    <label for="password" class="block mb-2 text-lg font-semibold">Password</label>
    <input type="password" name="password" id="password" class="bg-transparent border border-yellow-200 text-yellow-200 text-lg rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5" required />
  </div>
  <div class="text-yellow-200 my-4">
    Sudah punya akun? 
    <a href="<?= session()->get('logged_in') ? '/dashboard' : '/login' ?>" class="hover:text-yellow-500 hover:underline">
        <?= session()->get('logged_in') ? 'Kembali ke Dashboard' : 'Login disini' ?>
    </a>
  </div>
  
  <button type="submit" class="text-yellow-200 bg-yellow-700 hover:bg-yellow-800 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Submit</button>
</form>
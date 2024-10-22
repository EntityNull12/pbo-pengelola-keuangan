<h3 class="font-bold text-center my-12 text-6xl">Selamat Datang</h3>

<form class="max-w-2xl mx-auto" method="post" action="<?= base_url('login/authenticate') ?>">
  <div class="mb-5">
    <label for="username" class="block mb-2 text-lg font-semibold">Username</label>
    <input type="text" id="username" name="username" class="bg-transparent border border-yellow-200 text-yellow-200 text-lg rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 " placeholder="John Doe" required />
  </div>
  <div class="mb-5">
    <label for="password" class="block mb-2 text-lg font-semibold ">Password</label>
    <input type="password" id="password" name="password" class="bg-transparent border border-yellow-200 text-yellow-200 text-lg rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 " required />
  </div>
  <div class="text-yellow-200 my-4">Belum punya akun? <a href="/register" class="hover:text-yellow-500 hover:underline">Daftar disini</a></div>  
  <button type="submit" class="text-yellow-200 bg-yellow-700 hover:bg-yellow-800 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center ">Submit</button>
</form>

<!-- Tampilkan error jika ada -->
<?php if(session()->getFlashdata('error')): ?>
    <div class="text-red-500 text-sm mt-2">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

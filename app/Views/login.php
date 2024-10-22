<form class="max-w-sm mx-auto" method="post" action="<?= base_url('login/authenticate') ?>">
  <div class="mb-5">
    <label for="username" class="block mb-2 text-sm font-medium text-gray-900 ">Username</label>
    <input type="text" id="username" name="username" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 " placeholder="John Doe" required />
  </div>
  <div class="mb-5">
    <label for="password" class="block mb-2 text-sm font-medium text-gray-900 ">Password</label>
    <input type="password" id="password" name="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 " required />
  </div>
  
  <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center ">Submit</button>
</form>

<!-- Tampilkan error jika ada -->
<?php if(session()->getFlashdata('error')): ?>
    <div class="text-red-500 text-sm mt-2">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<h3 class="font-bold text-center my-12 text-6xl">Selamat Datang</h3>

<form class="max-w-2xl mx-auto">
  <div class="mb-5">
    <label for="username" class="block mb-2 text-lg font-semibold">Username</label>
    <input type="text" id="username" class="bg-transparent border border-yellow-200 text-yellow-200 text-lg rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 " placeholder="John Doe" required />
  </div>
  <div class="mb-5">
    <label for="password" class="block mb-2 text-lg font-semibold ">Password</label>
    <input type="password" id="password" class="bg-transparent border border-yellow-200 text-yellow-200 text-lg rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 " required />
  </div>
  <div class="text-yellow-200 my-4">Belum punya akun? <a href="/register" class="hover:text-yellow-500 hover:underline">Daftar disini</a></div>


<!-- <div class="relative overflow-x-auto">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 ">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50  ">
            <tr>
                <th scope="col" class="px-6 py-3">
                    id
                </th>
                <th scope="col" class="px-6 py-3">
                    nama
                </th>
                <th scope="col" class="px-6 py-3">
                    Username
                </th>
                <th scope="col" class="px-6 py-3">
                    Password
                </th>
                <th scope="col" class="px-6 py-3">
                    Aksi
                </th>
            </tr>
        </thead>
        <tbody>
          <?php foreach ($user as $k) :?>
            <tr class="bg-white border-b ">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap ">
                    <?= $k['id']; ?>
                </th>
                <td class="px-6 py-4">
                <?= $k['nama']; ?>
                </td>
                <td class="px-6 py-4">
                <?= $k['username']; ?>
                </td>
                <td class="px-6 py-4">
                <?= $k['password']; ?>
                </td>
                <td class="px-6 py-4">
                <a href=""  class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 focus:outline-none ">Edit</a>
                <a href=""  class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 focus:outline-none ">Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div> -->

  
  <button type="submit" class="text-yellow-200 bg-yellow-700 hover:bg-yellow-800 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center ">Submit</button>
</form>

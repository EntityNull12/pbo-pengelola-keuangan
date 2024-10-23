<nav class="bg-gray-800 p-4">
    <div class="container mx-auto flex justify-between items-center">
        <div class="text-white text-lg font-bold text-3xl">Dashboard</div>
        <div class="relative">
            <!-- Icon garis 3 (hamburger menu) -->
            <button id="menu-button" class="text-yellow-200 hover:bg-yellow-700 hover:text-white px-3 py-2 rounded focus:outline-none focus:ring-2 focus:ring-yellow-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                </svg>
            </button>
            
            <!-- Dropdown menu -->
            <div id="menu-dropdown" class="hidden absolute right-0 mt-2 w-48 bg-gray-800 rounded-lg shadow-lg z-10">
                <a href="/about" class="block px-4 py-2 text-yellow-200 hover:bg-yellow-700 hover:text-white rounded-t-lg">About</a>
                <a href="/profile" class="block px-4 py-2 text-yellow-200 hover:bg-yellow-700 hover:text-white">Profile</a>
            </div>
        </div>
    </div>
</nav>

<h6 class="font-medium text-lg mt-8 mb-4 text-center">Uang saat ini</h6>
<h5 class="font-bold text-5xl text-center">Rp300.000</h5>

<a href="/dashboard/pengelola" class="text-yellow-200 bg-yellow-700 hover:bg-yellow-800 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Catatan</a>

<div class="relative overflow-x-auto mt-6">
    <table class="w-full text-sm text-left rtl:text-right text-yellow-500 bg-transparent">
        <thead class="text-xs text-yellow-300 uppercase bg-transparent">
            <tr>
                <th scope="col" class="px-6 py-3">no</th>
                <th scope="col" class="px-6 py-3">tanggal</th>
                <th scope="col" class="px-6 py-3">jumlah</th>
                <th scope="col" class="px-6 py-3">uang masuk / keluar</th>
                <th scope="col" class="px-6 py-3">deskripsi</th>
                <th scope="col" class="px-6 py-3">Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php //foreach ($user as $k) :?>
            <tr class="bg-transparent border-b">
                <th scope="row" class="px-6 py-4 font-medium text-yellow-500 whitespace-nowrap">
                    <?//= $k['id']; ?>
                </th>
                <td class="px-6 py-4"><? //=$k['nama']; ?></td>
                <td class="px-6 py-4"><? //=$k['username']; ?></td>
                <td class="px-6 py-4"><? //=$k['password']; ?></td>
                <td class="px-6 py-4"><? //=$k['password']; ?></td>
                <td class="px-6 py-4">
                    <a href="" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 focus:outline-none">Edit</a>
                    <a href="" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 focus:outline-none">Hapus</a>
                </td>
            </tr>
            <?php //endforeach; ?>
        </tbody>
    </table>
</div>

<div id="chartContainer" class="mt-6" style="height: 370px; width: 100%;"></div>

<!-- Script to toggle the dropdown -->
<script>
    const menuButton = document.getElementById('menu-button');
    const menuDropdown = document.getElementById('menu-dropdown');

    menuButton.addEventListener('click', () => {
        menuDropdown.classList.toggle('hidden');
    });

    window.addEventListener('click', (e) => {
        if (!menuButton.contains(e.target) && !menuDropdown.contains(e.target)) {
            menuDropdown.classList.add('hidden');
        }
    });
</script>

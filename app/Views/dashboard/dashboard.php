<h2 class="font-bold text-4xl my-5">Dashboard</h2>

<h6 class="font-medium text-lg mt-8 mb-4 text-center">Uang saat ini</h6>
<h5 class="font-bold text-5xl  text-center">Rp300.000</h5>

<a href="/dashboard/pengelola" class="text-yellow-200 bg-yellow-700 hover:bg-yellow-800 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center ">form</a>

<div class="relative overflow-x-auto mt-6">
    <table class="w-full text-sm text-left rtl:text-right text-yellow-500 bg-transparent ">
        <thead class="text-xs text-yellow-300 uppercase bg-transparent  ">
            <tr>
                <th scope="col" class="px-6 py-3">
                    no
                </th>
                <th scope="col" class="px-6 py-3">
                    tanggal
                </th>
                <th scope="col" class="px-6 py-3">
                    jumlah
                </th>
                <th scope="col" class="px-6 py-3">
                    uang masuk / keluar
                </th>
                <th scope="col" class="px-6 py-3">
                    deskripsi
                </th>
                <th scope="col" class="px-6 py-3">
                    Aksi
                </th>
            </tr>
        </thead>
        <tbody>
        <?php //foreach ($user as $k) :?>
            <tr class="bg-transparant border-b ">
                <th scope="row" class="px-6 py-4 font-medium text-yellow-500 whitespace-nowrap ">
                    <?//= $k['id']; ?>
                </th>
                <td class="px-6 py-4">
                <? //=$k['nama']; ?>
                </td>
                <td class="px-6 py-4">
                <? //=$k['username']; ?>
                </td>
                <td class="px-6 py-4">
                <? //=$k['password']; ?>
                </td>
                <td class="px-6 py-4">
                <? //=$k['password']; ?>
                </td>
                <td class="px-6 py-4">
                <a href=""  class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 focus:outline-none ">Edit</a>
                <a href=""  class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 focus:outline-none ">Hapus</a>
                </td>
            </tr>
            <?php //endforeach; ?>
        </tbody>
    </table>
</div>

    
     <div id="chartContainer" class="mt-6" style="height: 370px; width: 100%;"></div>
    
                                   
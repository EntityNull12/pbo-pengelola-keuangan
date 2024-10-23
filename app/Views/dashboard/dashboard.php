<h2 class="font-bold text-4xl my-5">Dashboard</h2>

<h3 class="font-medium text-4xl my-5 text-center">Your Balance</h3>
<h5 class="font-bold text-5xl  text-center">Rp300.000</h5>

<div class="flex justify-center gap-4 mt-6">
    <a href="/dashboard/pengelola" class="text-yellow-200 bg-yellow-700 hover:bg-yellow-800 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
        Catatan
    </a>
    <a href="/dashboard/riwayat" class="text-yellow-200 bg-yellow-700 hover:bg-yellow-800 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
        Riwayat Catatan
    </a>
</div>
<div class="relative overflow-x-auto mt-6">
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
            </tr>
            <?php //endforeach; ?>
        </tbody>
    </table>
</div>

    
     <div id="chartContainer" class="mt-6" style="height: 370px; width: 100%;"></div>
    
                                   
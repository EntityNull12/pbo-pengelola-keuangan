<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Catatan</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-yellow-200 min-h-screen p-4">
    <h3 class="font-bold text-center my-12 text-6xl">Riwayat Catatan</h3>
    
    <a href="/dashboard" class="block w-fit mx-auto text-gray-200 bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mb-8">
        Keluar
    </a>

    <!-- Filter Section -->
    <div class="max-w-6xl mx-auto mb-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="filterJenis" class="block mb-2 text-lg font-semibold">Filter Kategori</label>
                <select id="filterJenis" class="bg-gray-900 border border-yellow-200 text-yellow-200 text-lg rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5">
                    <option value="semua">Semua</option>
                    <option value="pemasukan">Pemasukan</option>
                    <option value="pengeluaran">Pengeluaran</option>
                </select>
            </div>
            <div>
                <label for="startDate" class="block mb-2 text-lg font-semibold">Tanggal Mulai</label>
                <input type="date" id="startDate" class="bg-gray-900 border border-yellow-200 text-yellow-200 text-lg rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5">
            </div>
            <div>
                <label for="endDate" class="block mb-2 text-lg font-semibold">Tanggal Akhir</label>
                <input type="date" id="endDate" class="bg-gray-900 border border-yellow-200 text-yellow-200 text-lg rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5">
            </div>
        </div>
    </div>

    <!-- Transaction History Table -->
    <div class="max-w-6xl mx-auto">
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-left rtl:text-right text-yellow-200">
                <thead class="text-lg text-yellow-200 uppercase bg-gray-800">
                    <tr>
                        <th scope="col" class="px-6 py-4">ID</th>
                        <th scope="col" class="px-6 py-4">Pengelola</th>
                        <th scope="col" class="px-6 py-4">Jumlah</th>
                        <th scope="col" class="px-6 py-4">Tanggal</th>
                        <th scope="col" class="px-6 py-4">Deskripsi</th>
                        <th scope="col" class="px-6 py-4">Kategori</th>
                        <th scope="col" class="px-6 py-4">Tipe Catatan</th>
                        <th scope="col" class="px-6 py-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($transactions as $transaction): ?>
                    <tr class="border-b bg-gray-800 border-gray-700 hover:bg-gray-700">
                        <td class="px-6 py-4"><?= $transaction['id'] ?></td>
                        <td class="px-6 py-4"><?= $transaction['pengelola'] ?></td>
                        <td class="px-6 py-4 <?= $transaction['tipe_catatan'] === 'pemasukan' ? 'text-green-400' : 'text-red-400' ?>">
                            Rp <?= number_format($transaction['jumlah'], 0, ',', '.') ?>
                        </td>
                        <td class="px-6 py-4"><?= $transaction['tanggal'] ?></td>
                        <td class="px-6 py-4"><?= $transaction['deskripsi'] ?></td>
                        <td class="px-6 py-4"><?= $transaction['kategori_transaksi'] ?></td>
                        <td class="px-6 py-4">
                            <span class="<?= $transaction['tipe_catatan'] === 'pemasukan' ? 'bg-green-900 text-green-300' : 'bg-red-900 text-red-300' ?> px-2 py-1 rounded-full text-sm">
                                <?= ucfirst($transaction['tipe_catatan']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <button onclick="editTransaksi(<?= $transaction['id'] ?>)" 
                                    class="font-medium text-blue-500 hover:underline me-3">
                                Edit
                            </button>
                            <button onclick="hapusTransaksi(<?= $transaction['id'] ?>)" 
                                    class="font-medium text-red-500 hover:underline">
                                Hapus
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="max-w-6xl mx-auto mt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-gray-800 p-6 rounded-lg">
            <h4 class="text-xl font-semibold mb-2">Total Pemasukan</h4>
            <p class="text-2xl text-green-400">Rp <?= number_format($totalPemasukan, 0, ',', '.') ?></p>
        </div>
        <div class="bg-gray-800 p-6 rounded-lg">
            <h4 class="text-xl font-semibold mb-2">Total Pengeluaran</h4>
            <p class="text-2xl text-red-400">Rp <?= number_format($totalPengeluaran, 0, ',', '.') ?></p>
        </div>
        <div class="bg-gray-800 p-6 rounded-lg">
            <h4 class="text-xl font-semibold mb-2">Saldo</h4>
            <p class="text-2xl <?= ($saldo >= 0) ? 'text-green-400' : 'text-red-400' ?>">
                Rp <?= number_format($saldo, 0, ',', '.') ?>
            </p>
        </div>
    </div>

    <script>
        function editTransaksi(id) {
            // Implement edit functionality
            if (confirm('Edit transaksi ini?')) {
                window.location.href = `/dashboard/edit/${id}`;
            }
        }

        function hapusTransaksi(id) {
            // Implement delete functionality
            if (confirm('Yakin ingin menghapus transaksi ini?')) {
                // Add AJAX call or form submission for deletion
                fetch(`/dashboard/delete/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert('Gagal menghapus transaksi');
                    }
                });
            }
        }

        // Add event listeners for filters
        document.getElementById('filterJenis').addEventListener('change', applyFilters);
        document.getElementById('startDate').addEventListener('change', applyFilters);
        document.getElementById('endDate').addEventListener('change', applyFilters);

        function applyFilters() {
            const jenis = document.getElementById('filterJenis').value;
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;

            // Add AJAX call to refresh table with filters
            window.location.href = `/dashboard/riwayat?jenis=${jenis}&start=${startDate}&end=${endDate}`;
        }
    </sc
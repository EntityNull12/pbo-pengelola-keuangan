<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Catatan</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        #editModal {
            z-index: 9999;
            display: none; /* Ubah dari flex ke none */
        }
        #editModal.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        #editModal .relative {
            z-index: 10000;
        }
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9998;
        }
    </style>
</head>
<body class="bg-gray-900 text-yellow-200 min-h-screen p-4">
    <h3 class="font-bold text-center my-12 text-6xl">Riwayat Catatan</h3>
    
    <a href="/dashboard" class="block w-fit mx-auto text-gray-200 bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mb-8">
        Kembali ke Dashboard
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
                        <th scope="col" class="px-6 py-4">Jumlah</th>
                        <th scope="col" class="px-6 py-4">Tanggal</th>
                        <th scope="col" class="px-6 py-4">Deskripsi</th>
                        <th scope="col" class="px-6 py-4">Kategori</th>
                        <th scope="col" class="px-6 py-4">Tipe Catatan</th>
                        <th scope="col" class="px-6 py-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $user_id = session()->get('user_id');
                    foreach($transactions as $transaction): 
                        if($transaction['pengelola'] == $user_id):
                    ?>
                    <tr class="border-b bg-gray-800 border-gray-700 hover:bg-gray-700">
                        <td class="px-6 py-4 <?= $transaction['tipe_catatan'] === 'pemasukan' ? 'text-green-400' : 'text-red-400' ?>">
                            Rp <?= number_format($transaction['jumlah'], 0, ',', '.') ?>
                        </td>
                        <td class="px-6 py-4"><?= date('d/m/Y', strtotime($transaction['tanggal'])) ?></td>
                        <td class="px-6 py-4"><?= $transaction['deskripsi'] ?></td>
                        <td class="px-6 py-4"><?= $transaction['kategori_transaksi'] ?? '-' ?></td>
                        <td class="px-6 py-4">
                            <span class="<?= $transaction['tipe_catatan'] === 'pemasukan' ? 'bg-green-900 text-green-300' : 'bg-red-900 text-red-300' ?> px-2 py-1 rounded-full text-sm">
                                <?= ucfirst($transaction['tipe_catatan']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <button onclick="editTransaksi(<?= $transaction['id'] ?>)" 
                                    class="focus:outline-none text-white bg-yellow-700 hover:bg-yellow-800 focus:ring-4 focus:ring-yellow-600 font-medium rounded-lg text-sm px-5 py-2.5 me-3 mb-2">
                                Edit
                            </button>
                            <button onclick="hapusTransaksi(<?= $transaction['id'] ?>)" 
                                    class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2">
                                Hapus
                            </button>
                        </td>
                    </tr>
                    <?php 
                        endif;
                    endforeach; 
                    ?>
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

    <!-- Modal Edit -->
    <div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-gray-900 z-50">
            <div class="flex flex-col gap-4">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-bold">Edit Transaksi</h3>
                    <button onclick="closeModal()" class="text-yellow-200 hover:text-yellow-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form method="POST" id="editForm" class="mt-4" onsubmit="return validateEditForm()">
                    <?= csrf_field() ?>
                    <input type="hidden" name="_method" value="PUT">
                    
                    <!-- Jenis Transaksi -->
                    <div class="mb-5">
                        <label for="edit_jenis" class="block mb-2 text-lg font-semibold">Kategori Catatan</label>
                        <select name="jenis" id="edit_jenis" onchange="updateEditForm()" class="bg-gray-900 border border-yellow-200 text-yellow-200 text-lg rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5">
                            <option value="pemasukan">Pemasukan</option>
                            <option value="pengeluaran">Pengeluaran</option>
                        </select>
                    </div>

                    <!-- Nominal -->
                    <div class="mb-5">
                        <label for="edit_nominal" class="block mb-2 text-lg font-semibold">Nominal</label>
                        <input type="text" 
                               name="nominal" 
                               id="edit_nominal" 
                               class="bg-transparent border border-yellow-200 text-yellow-200 text-lg rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5" 
                               onkeyup="formatRibuan(this)"
                               required>
                    </div>

                    <!-- Tanggal -->
                    <div class="mb-5">
                        <label for="edit_tanggal" class="block mb-2 text-lg font-semibold">Tanggal</label>
                        <input type="datetime-local" 
                               name="tanggal" 
                               id="edit_tanggal" 
                               class="bg-transparent border border-yellow-200 text-yellow-200 text-lg rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5" 
                               required>
                    </div>

                    <!-- Deskripsi Pemasukan -->
                    <div id="edit_deskripsiContainer" class="mb-5">
                        <label for="edit_deskripsi" class="block mb-2 text-lg font-semibold">Deskripsi</label>
                        <input type="text" 
                               name="deskripsi" 
                               id="edit_deskripsi" 
                               class="bg-transparent border border-yellow-200 text-yellow-200 text-lg rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5" 
                               required>
                    </div>

                    <!-- Kategori Pengeluaran -->
                    <div id="edit_kategoriContainer" class="mb-5 hidden">
                        <label for="edit_kategori" class="block mb-2 text-lg font-semibold">Kategori Transaksi</label>
                        <select name="kategori" 
                                id="edit_kategori" 
                                class="bg-gray-900 border border-yellow-200 text-yellow-200 text-lg rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5">
                            <option value="hiburan">Hiburan</option>
                            <option value="makanan">Makanan</option>
                            <option value="olahraga">Olahraga</option>
                            <option value="edukasi">Edukasi</option>
                            <option value="belanja">Belanja</option>
                            <option value="medis">Medis</option>
                            <option value="transportasi">Transportasi</option>
                        </select>
                    </div>

                    <!-- Deskripsi Pengeluaran -->
                    <div id="edit_deskripsiPengeluaranContainer" class="mb-5 hidden">
                        <label for="edit_deskripsiPengeluaran" class="block mb-2 text-lg font-semibold">Deskripsi Pengeluaran</label>
                        <input type="text" 
                               name="deskripsiPengeluaran" 
                               id="edit_deskripsiPengeluaran" 
                               class="bg-transparent border border-yellow-200 text-yellow-200 text-lg rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5">
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            class="text-yellow-200 bg-yellow-700 hover:bg-yellow-800 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">
                        Update Transaksi
                    </button>
                </form>
            </div>
        </div>
        <div class="modal-overlay" onclick="closeModal()"></div>
    </div>

    <script>
        // Fungsi untuk membuka modal dan mengisi data
        function editTransaksi(id) {
            console.log('Edit clicked for ID:', id);
            
            fetch(`/dashboard/riwayat/${id}/edit`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(response => {
                if (response.data) {
                    const data = response.data;
                    console.log('Data received:', data);

                    document.getElementById('editForm').action = `/dashboard/riwayat/${id}/update`;
                    document.getElementById('edit_jenis').value = data.tipe_catatan;
                    document.getElementById('edit_nominal').value = formatNumber(data.jumlah);
                    document.getElementById('edit_tanggal').value = formatDateTime(data.tanggal);
                    
                    if (data.tipe_catatan === 'pemasukan') {
                        document.getElementById('edit_deskripsi').value = data.deskripsi
                        document.getElementById('edit_deskripsiPengeluaran').value = '';
                       document.getElementById('edit_kategori').value = '';
                   } else {
                       document.getElementById('edit_kategori').value = data.kategori_transaksi || 'hiburan';
                       document.getElementById('edit_deskripsiPengeluaran').value = data.deskripsi;
                       document.getElementById('edit_deskripsi').value = '';
                   }
                   
                   // Update form visibility
                   updateEditForm();
                   
                   // Tampilkan modal
                   const modal = document.getElementById('editModal');
                   modal.classList.remove('hidden');
                   modal.classList.add('active'); // Tambahkan class active
                   
               } else {
                   console.error('Invalid response format:', response);
                   alert('Gagal mengambil data transaksi');
               }
           })
           .catch(error => {
               console.error('Error:', error);
               alert('Terjadi kesalahan saat mengambil data');
           });
       }

       // Fungsi untuk menutup modal
       function closeModal() {
           const modal = document.getElementById('editModal');
           modal.classList.add('hidden');
           modal.classList.remove('active');
       }

       // Fungsi untuk memformat angka dengan pemisah ribuan
       function formatNumber(number) {
           return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
       }

       // Fungsi untuk memformat datetime untuk input datetime-local
       function formatDateTime(dateString) {
           console.log('Formatting date:', dateString);
           const date = new Date(dateString);
           const formatted = date.toISOString().slice(0, 16);
           console.log('Formatted date:', formatted);
           return formatted;
       }

       // Fungsi untuk mengupdate tampilan form edit
       function updateEditForm() {
           const jenisTransaksi = document.getElementById('edit_jenis').value;
           const deskripsiContainer = document.getElementById('edit_deskripsiContainer');
           const kategoriContainer = document.getElementById('edit_kategoriContainer');
           const deskripsiPengeluaranContainer = document.getElementById('edit_deskripsiPengeluaranContainer');
           const deskripsi = document.getElementById('edit_deskripsi');
           const deskripsiPengeluaran = document.getElementById('edit_deskripsiPengeluaran');
           const kategori = document.getElementById('edit_kategori');

           if (jenisTransaksi === 'pemasukan') {
               deskripsiContainer.classList.remove('hidden');
               kategoriContainer.classList.add('hidden');
               deskripsiPengeluaranContainer.classList.add('hidden');
               deskripsi.required = true;
               kategori.required = false;
               deskripsiPengeluaran.required = false;
           } else {
               deskripsiContainer.classList.add('hidden');
               kategoriContainer.classList.remove('hidden');
               deskripsiPengeluaranContainer.classList.remove('hidden');
               deskripsi.required = false;
               kategori.required = true;
               deskripsiPengeluaran.required = true;
           }
       }

       // Event listener untuk form submit
       document.getElementById('editForm').addEventListener('submit', function(e) {
           e.preventDefault();
           
           if (validateEditForm()) {
               // Ambil semua data form
               const formData = new FormData(this);
               const id = this.action.split('/').pop();
               
               fetch(this.action, {
                   method: 'POST',
                   body: formData
               })
               .then(response => response.json())
               .then(data => {
                   if (data.success) {
                       alert('Transaksi berhasil diupdate');
                       window.location.reload();
                   } else {
                       alert(data.message || 'Gagal mengupdate transaksi');
                   }
               })
               .catch(error => {
                   console.error('Error:', error);
                   alert('Terjadi kesalahan saat mengupdate data');
               });
           }
       });

       // Fungsi untuk validasi form edit
       function validateEditForm() {
           const nominal = document.getElementById('edit_nominal').value;
           const cleanNominal = nominal.replace(/\./g, '');

           if (!cleanNominal) {
               alert('Nominal harus diisi!');
               return false;
           }
           
           if (!/^\d+$/.test(cleanNominal)) {
               alert('Format nominal tidak valid! Gunakan hanya angka.');
               return false;
           }

           return true;
       }

       // Fungsi untuk format ribuan
       function formatRibuan(input) {
           // Hapus semua karakter selain angka
           let value = input.value.replace(/\D/g, '');
           
           // Format angka dengan pemisah titik setiap tiga digit
           input.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
       }

       // Event listener untuk filter
       document.getElementById('filterJenis').addEventListener('change', applyFilters);
       document.getElementById('startDate').addEventListener('change', applyFilters);
       document.getElementById('endDate').addEventListener('change', applyFilters);

       function applyFilters() {
           const jenis = document.getElementById('filterJenis').value;
           const startDate = document.getElementById('startDate').value;
           const endDate = document.getElementById('endDate').value;
           window.location.href = `/dashboard/riwayat?jenis=${jenis}&start=${startDate}&end=${endDate}`;
       }

       // Event listener untuk menutup modal saat klik di luar
       document.addEventListener('click', function(event) {
           if (event.target.classList.contains('modal-overlay')) {
               closeModal();
           }
       });

       // Fungsi untuk delete transaksi
       function hapusTransaksi(id) {
           if (confirm('Yakin ingin menghapus transaksi ini?')) {
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
   </script>
</body>
</html>
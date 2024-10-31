<?php
// Proses form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $jenis = $_POST['jenis'];

    // Hapus titik dari nominal untuk mengubahnya menjadi angka
    $nominal = str_replace('.', '', $_POST['nominal']);

    $tanggal = $_POST['tanggal'];

    if ($jenis === 'pemasukan') {
        $deskripsi = $_POST['deskripsi'];
    } else {
        $kategori = $_POST['kategori'];
        $deskripsiPengeluaran = $_POST['deskripsiPengeluaran'];
    }

    // Di sini Anda bisa menambahkan logika untuk menyimpan ke database
    // Contoh: 
    // $sql = "INSERT INTO transaksi (jenis, nominal, tanggal, deskripsi, kategori) VALUES (?, ?, ?, ?, ?)";
    // ...
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Transaksi</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 text-yellow-200 min-h-screen p-4">
    <h3 class="font-bold text-center my-12 text-6xl">Selamat Datang</h3>
<div class="max-w-2xl mx-auto container">

    <a href="/dashboard" class="block w-fit text-gray-200 bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-left mb-8">
        Keluar
        </a>
</div>

    <!-- Rest of your HTML remains the same until the form tag -->
    <form method="POST" action="<?= base_url('dashboard/savePengelola') ?>" class="max-w-2xl mx-auto mt-4" id="transaksiForm" onsubmit="return validateForm()">
        <?= csrf_field() ?>

        <!-- Flash messages -->
        <?php if (session()->getFlashdata('success')) : ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')) : ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <!-- Jenis Transaksi -->
        <div class="mb-5">
            <label for="jenis" class="block mb-2 text-lg font-semibold">Kategori Catatan</label>
            <select name="jenis" id="jenis" onchange="updateForm()" class="bg-gray-900 border border-yellow-200 text-yellow-200 text-lg rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5">
                <option value="pemasukan">Pemasukan</option>
                <option value="pengeluaran">Pengeluaran</option>
            </select>
        </div>

        <!-- Nominal -->
        <div class="mb-5">
            <label for="nominal" class="block mb-2 text-lg font-semibold">Nominal</label>
            <input type="text"
                name="nominal"
                id="nominal"
                class="bg-transparent border border-yellow-200 text-yellow-200 text-lg rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5"
                onkeyup="formatRibuan(this)"
                required>
        </div>

        <!-- Tanggal -->
        <div class="mb-5">
            <label for="tanggal" class="block mb-2 text-lg font-semibold">Tanggal</label>
            <input type="datetime-local" name="tanggal" id="tanggal" data-date-format="DD/MMM/YYYY" min="2015-06-01T08:30"
                max="2099-06-30T16:30" class="tm bg-transparent border border-yellow-200 text-yellow-200 text-lg rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5" required>
        </div>

        <!-- Deskripsi Pemasukan -->
        <div id="deskripsiContainer" class="mb-5">
            <label for="deskripsi" class="block mb-2 text-lg font-semibold">Deskripsi</label>
            <input type="text" name="deskripsi" id="deskripsi" class="bg-transparent border border-yellow-200 text-yellow-200 text-lg rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5" required>
        </div>

        <!-- Kategori Pengeluaran -->
        <div id="kategoriContainer" class="mb-5 hidden">
            <label for="kategori" class="block mb-2 text-lg font-semibold">Kategori Transaksi</label>
            <select name="kategori" id="kategori" class="bg-gray-900 border border-yellow-200 text-yellow-200 text-lg rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5">
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
        <div id="deskripsiPengeluaranContainer" class="mb-5 hidden">
            <label for="deskripsiPengeluaran" class="block mb-2 text-lg font-semibold">Deskripsi Pengeluaran</label>
            <input type="text" name="deskripsiPengeluaran" id="deskripsiPengeluaran" class="bg-transparent border border-yellow-200 text-yellow-200 text-lg rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5">
        </div>

        <!-- Submit Button -->
        <button type="submit" id="submitBtn" class="text-yellow-200 bg-yellow-700 hover:bg-yellow-800 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">
            Submit Pemasukan
        </button>
    </form>

    <!-- Your existing JavaScript remains the same -->
    <script>
        const date = new Date(this.value);
        const day = ("0" + date.getDate()).slice(-2); // memastikan 2 digit
        const month = ("0" + (date.getMonth() + 1)).slice(-2); // memastikan 2 digit
        const year = date.getFullYear();
        const hours = ("0" + date.getHours()).slice(-2); // memastikan 2 digit
        const minutes = ("0" + date.getMinutes()).slice(-2); // memastikan 2 digit
        const formattedDateTime = `${day}/${month}/${year}, ${hours}:${minutes}`;
        console.log(formattedDateTime); // Cek format di konsol atau tampilkan ke elemen
        });
    </script>

    <script>
        function formatRibuan(input) {
            // Hapus semua karakter selain angka
            let value = input.value.replace(/\D/g, '');

            // Format angka dengan pemisah titik setiap tiga digit
            input.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        function validateForm() {
            const nominal = document.getElementById('nominal').value;

            // Hapus titik sebelum validasi atau penyimpanan
            const cleanNominal = nominal.replace(/\./g, '');

            if (!cleanNominal) {
                alert('Nominal harus diisi!');
                return false;
            }

            // Pastikan hanya angka yang tersisa
            if (!/^\d+$/.test(cleanNominal)) {
                alert('Format nominal tidak valid! Gunakan hanya angka.');
                return false;
            }

            // Lakukan hal lain sesuai kebutuhan, seperti mengirimkan data
            return true;
        }

        function updateForm() {
            const jenisTransaksi = document.getElementById('jenis').value;
            const submitBtn = document.getElementById('submitBtn');
            const nominal = document.getElementById('nominal');
            const deskripsiContainer = document.getElementById('deskripsiContainer');
            const kategoriContainer = document.getElementById('kategoriContainer');
            const deskripsiPengeluaranContainer = document.getElementById('deskripsiPengeluaranContainer');
            const deskripsi = document.getElementById('deskripsi');
            const deskripsiPengeluaran = document.getElementById('deskripsiPengeluaran');
            const kategori = document.getElementById('kategori');

            if (jenisTransaksi === 'pemasukan') {
                submitBtn.textContent = 'Submit Pemasukan';
                nominal.placeholder = 'Masukkan jumlah pemasukan';
                deskripsiContainer.classList.remove('hidden');
                kategoriContainer.classList.add('hidden');
                deskripsiPengeluaranContainer.classList.add('hidden');
                deskripsi.required = true;
                kategori.required = false;
                deskripsiPengeluaran.required = false;
            } else {
                submitBtn.textContent = 'Submit Pengeluaran';
                nominal.placeholder = 'Masukkan jumlah pengeluaran';
                deskripsiContainer.classList.add('hidden');
                kategoriContainer.classList.remove('hidden');
                deskripsiPengeluaranContainer.classList.remove('hidden');
                deskripsi.required = false;
                kategori.required = true;
                deskripsiPengeluaran.required = true;
            }
        }

        // Panggil updateForm saat halaman dimuat
        document.addEventListener('DOMContentLoaded', updateForm);
    </script>
</body>

</html>
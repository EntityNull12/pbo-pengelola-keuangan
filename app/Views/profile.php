<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo esc($title); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js" defer></script>
</head>

<?php if (session()->getFlashdata('error')): ?>
    <div class="text-red-500 text-sm mt-2">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('success')): ?>
    <div class="text-green-500 text-sm mt-2">
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<body class="bg-gray-800 text-white" x-data="{ open: false, modalOpen: <?= session()->getFlashdata('old_password_error') || session()->getFlashdata('new_password_error') || session()->getFlashdata('confirm_password_error') ? 'true' : 'false' ?>, modalOpenProfilePhoto: false }">
    <div class="container mx-auto py-12">
        <div class="bg-gray-900 rounded-lg shadow-lg p-6 text-center relative">
            <button @click="open = !open" class="absolute top-4 right-4 text-white focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
            </button>
            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-gray-800 rounded-lg shadow-lg z-10" x-transition>
                <button @click="modalOpen = true" class="block px-4 py-2 text-sm hover:bg-indigo-600">Change Password</button>
                <button @click="modalOpenProfilePhoto = true" class="block px-4 py-2 text-sm hover:bg-indigo-600">Change Profile Photo</button>
                <a href="/logout" class="block px-4 py-2 text-sm hover:bg-indigo-600">Logout</a>
            </div>
            <img src="<?= base_url('uploads/' . $profile_photo) ?>" alt="User Photo" class="w-32 h-32 rounded-full object-cover border-4 border-indigo-600 mx-auto mb-4">
            <h1 class="text-4xl font-bold mb-2"><?php echo esc($nama); ?></h1>
            <p class="text-lg text-gray-400 mb-6">Pengguna</p>
            <div>
                <h2 class="text-2xl font-semibold">Bio:</h2>
                <p class="mt-2 text-gray-300">Ini adalah bio pengguna yang dapat ditampilkan di sini. Anda dapat mengubah konten ini sesuai kebutuhan.</p>
            </div>
            <div class="mt-6">
                <a href="/dashboard" class="inline-block bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-2 px-4 rounded-lg shadow-lg transition duration-300">Kembali ke Dashboard</a>
            </div>
        </div>
    </div>

    <!-- Modal untuk Mengganti Password -->
    <div x-show="modalOpen" class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50" x-transition>
        <div class="bg-gray-900 rounded-lg p-6 w-96">
            <h2 class="text-lg font-bold mb-4">Change Password</h2>
            <form method="post" action="/change-password">
                <div class="mb-4">
                    <label for="old_password" class="block mb-2 text-sm">Password Lama</label>
                    <input type="password" name="old_password" id="old_password" class="bg-transparent border border-yellow-200 text-yellow-200 rounded-lg block w-full p-2.5" required>
                    
                    <!-- Error untuk password lama -->
                    <?php if (session()->getFlashdata('old_password_error')): ?>
                        <div class="text-red-500 text-sm mt-1">
                            <?= session()->getFlashdata('old_password_error') ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="mb-4">
                    <label for="new_password" class="block mb-2 text-sm">Password Baru</label>
                    <input type="password" name="new_password" id="new_password" class="bg-transparent border border-yellow-200 text-yellow-200 rounded-lg block w-full p-2.5" required>
                    
                    <!-- Error untuk password baru -->
                    <?php if (session()->getFlashdata('new_password_error')): ?>
                        <div class="text-red-500 text-sm mt-1">
                            <?= session()->getFlashdata('new_password_error') ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="mb-4">
                    <label for="confirm_password" class="block mb-2 text-sm">Konfirmasi Password Baru</label>
                    <input type="password" name="confirm_password" id="confirm_password" class="bg-transparent border border-yellow-200 text-yellow-200 rounded-lg block w-full p-2.5" required>
                    
                    <!-- Error untuk konfirmasi password -->
                    <?php if (session()->getFlashdata('confirm_password_error')): ?>
                        <div class="text-red-500 text-sm mt-1">
                            <?= session()->getFlashdata('confirm_password_error') ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="flex justify-between">
                    <button type="button" @click="modalOpen = false" class="text-gray-400 hover:text-gray-200">Batal</button>
                    <button type="submit" class="text-yellow-200 bg-yellow-700 hover:bg-yellow-800 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-sm px-4 py-2">Ganti Password</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal untuk Mengganti Foto Profil -->
    <div x-show="modalOpenProfilePhoto" class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50" x-transition>
        <div class="bg-gray-900 rounded-lg p-6 w-96">
            <h2 class="text-lg font-bold mb-4">Ganti Foto Profil</h2>
            <form method="post" action="/profile-photo/update" enctype="multipart/form-data">
                <div class="mb-4">
                    <label for="profile_photo" class="block mb-2 text-sm">Pilih Foto Baru</label>
                    <input type="file" name="profile_photo" id="profile_photo" class="bg-transparent border border-yellow-200 text-yellow-200 rounded-lg block w-full p-2.5" required>
                    
                    <!-- Error untuk pengunggahan foto -->
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="text-red-500 text-sm mt-1">
                            <?= session()->getFlashdata('error') ?>
                        </div>  
                    <?php endif; ?>
                </div>
                <div class="flex justify-between">
                    <button type="button" @click="modalOpenProfilePhoto = false" class="text-gray-400 hover:text-gray-200">Batal</button>
                    <button type="submit" class="text-yellow-200 bg-yellow-700 hover:bg-yellow-800 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-sm px-4 py-2">Ganti Foto</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

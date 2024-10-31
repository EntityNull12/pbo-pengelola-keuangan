<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo esc($title); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js" defer></script>
</head>

<body class="bg-gray-800 text-white" x-data="{ 
    open: false, 
    modalOpen: <?= session()->getFlashdata('old_password_error') || session()->getFlashdata('new_password_error') || session()->getFlashdata('confirm_password_error') ? 'true' : 'false' ?>, 
    modalOpenProfilePhoto: false,
    showFlash: <?= session()->has('success') || session()->has('error') ? 'true' : 'false' ?>
}">
    <!-- Flash Message -->
    <div x-show="showFlash"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-2"
         class="fixed top-4 right-4 z-50"
         @click.away="showFlash = false"
         x-init="setTimeout(() => showFlash = false, 3000)">
        
        <?php if(session()->getFlashdata('success')): ?>
        <div class="bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg flex items-center">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span><?= session()->getFlashdata('success') ?></span>
        </div>
        <?php endif; ?>

        <?php if(session()->getFlashdata('error')): ?>
        <div class="bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg flex items-center">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span><?= session()->getFlashdata('error') ?></span>
        </div>
        <?php endif; ?>
    </div>

    <div class="container mx-auto py-12">
        <div class="bg-gray-900 rounded-lg shadow-lg p-6 text-center relative">
            <!-- Menu Button and Dropdown -->
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
            
            <img src="<?= base_url('uploads/' . $profile_photo) ?>?v=<?= time() ?>" alt="User Photo" class="w-32 h-32 rounded-full object-cover border-4 border-indigo-600 mx-auto mb-4">

            <h1 class="text-4xl font-bold mb-2"><?php echo esc($nama); ?></h1>
            <p class="text-lg text-gray-400 mb-6">Pengguna</p>

            <!-- Bio Section with Edit Functionality -->
            <div x-data="{ 
                    isEditing: false, 
                    bioText: '<?= esc($bio) ?>', 
                    csrfToken: '<?= csrf_hash() ?>',
                    csrfName: '<?= csrf_token() ?>',
                    async updateBio() {
                        try {
                            const formData = new FormData();
                            formData.append('bio', this.bioText);
                            formData.append(this.csrfName, this.csrfToken);

                            const response = await fetch('/update-bio', {
                                method: 'POST',
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest'
                                },
                                body: formData
                            });

                            const data = await response.json();
                            
                            // Update CSRF token from response headers
                            const newCsrfToken = response.headers.get('X-CSRF-TOKEN');
                            if (newCsrfToken) {
                                this.csrfToken = newCsrfToken;
                            }

                            if(data.success) {
                                this.isEditing = false;
                                const flashDiv = document.createElement('div');
                                flashDiv.className = 'bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg flex items-center';
                                flashDiv.innerHTML = `
                                    <svg class='w-6 h-6 mr-2' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                                        <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 13l4 4L19 7'/>
                                    </svg>
                                    <span>${data.message}</span>
                                `;
                                document.getElementById('flashMessages').innerHTML = '';
                                document.getElementById('flashMessages').appendChild(flashDiv);
                                setTimeout(() => flashDiv.remove(), 3000);
                            } else {
                                const flashDiv = document.createElement('div');
                                flashDiv.className = 'bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg flex items-center';
                                flashDiv.innerHTML = `
                                    <svg class='w-6 h-6 mr-2' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                                        <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'/>
                                    </svg>
                                    <span>${data.message}</span>
                                `;
                                document.getElementById('flashMessages').appendChild(flashDiv);
                                setTimeout(() => flashDiv.remove(), 3000);
                            }
                        } catch (error) {
                            console.error('Error:', error);
                        }
                    }
                }">
                <div class="text-center mb-100 flex items-center justify-center">
                    <h2 class="text-2xl font-semibold">Bio:</h2>
                    <button 
                        @click="isEditing = true" 
                        class="ml-2 opacity-70 hover:opacity-100 transition-opacity"
                        x-show="!isEditing">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-400 hover:text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                    </button>
                </div>
                <div class="mt-1 max-w-2xl mx-auto">
                    <template x-if="!isEditing">
                        <div class="text-white text-xl font-medium break-words whitespace-pre-wrap" style="max-height: 300px; overflow-y: auto;">
                            <p x-show="bioText.trim()" x-text="bioText"></p>
                            <p x-show="!bioText.trim()" class="text-gray-500">Bio kosong</p>
                        </div>
                    </template>
                    <template x-if="isEditing">
                        <div class="flex flex-col space-y-2">
                            <textarea 
                                x-model="bioText"
                                class="w-full h-32 p-2 bg-gray-800 border border-indigo-500 rounded-lg text-white resize-y"
                                style="min-height: 128px; max-height: 300px;"
                                placeholder="Tulis bio Anda di sini..."></textarea>
                            <div class="flex justify-end space-x-2">
                                <button 
                                    @click="isEditing = false" 
                                    class="px-4 py-2 text-sm bg-gray-700 text-white rounded-lg hover:bg-gray-600">
                                    Batal
                                </button>
                                <button 
                                    @click="updateBio()"
                                    class="px-4 py-2 text-sm bg-indigo-600 text-white rounded-lg hover:bg-indigo-500">
                                    Simpan
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
        </div>
        <div class="mt-4">
            <a href="/dashboard" class="inline-block bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-2 px-4 rounded-lg shadow-lg transition duration-300">Kembali ke Dashboard</a>
        </div>
        </div>
    </div>

    <!-- Flash messages -->
    <div class="fixed top-4 right-4 z-50 w-96" id="flashMessages">
        <?php if (session()->getFlashdata('success')) : ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?= session()->getFlashdata('success') ?></span>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')) : ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?= session()->getFlashdata('error') ?></span>
            </div>
        <?php endif; ?>
    </div>

    <!-- Modal untuk Mengganti Password -->
    <div x-show="modalOpen" class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50" x-transition>
        <div class="bg-gray-900 rounded-lg p-6 w-96">
            <h2 class="text-lg font-bold mb-4">Change Password</h2>
            <form id="changePasswordForm" 
                  @submit.prevent="
                    fetch('/change-password', {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: new FormData($event.target)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if(data.success) {
                            modalOpen = false;
                            // Tambahkan flash message
                            const flashDiv = document.createElement('div');
                            flashDiv.className = 'bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4';
                            flashDiv.setAttribute('role', 'alert');
                            flashDiv.innerHTML = '<span class=\'block sm:inline\'>' + data.message + '</span>';
                            document.getElementById('flashMessages').innerHTML = '';
                            document.getElementById('flashMessages').appendChild(flashDiv);
                            // Hapus flash message setelah 3 detik
                            setTimeout(() => {
                                flashDiv.remove();
                            }, 3000);
                        } else {
                            // Tampilkan pesan error
                            const flashDiv = document.createElement('div');
                            flashDiv.className = 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4';
                            flashDiv.setAttribute('role', 'alert');
                            flashDiv.innerHTML = '<span class=\'block sm:inline\'>' + data.message + '</span>';
                            document.getElementById('flashMessages').innerHTML = '';
                            document.getElementById('flashMessages').appendChild(flashDiv);
                        }
                    })">
                <?= csrf_field() ?>
                
                <div class="mb-4">
                    <label for="old_password" class="block mb-2 text-sm">Password Lama</label>
                    <input type="password" 
                           name="old_password" 
                           id="old_password" 
                           class="bg-transparent border border-yellow-200 text-yellow-200 rounded-lg block w-full p-2.5" 
                           required>
                </div>

                <div class="mb-4">
                    <label for="new_password" class="block mb-2 text-sm">Password Baru</label>
                    <input type="password" 
                           name="new_password" 
                           id="new_password" 
                           class="bg-transparent border border-yellow-200 text-yellow-200 rounded-lg block w-full p-2.5" 
                           required>
                </div>

                <div class="mb-4">
                    <label for="confirm_password" class="block mb-2 text-sm">Konfirmasi Password Baru</label>
                    <input type="password" 
                           name="confirm_password" 
                           id="confirm_password" 
                           class="bg-transparent border border-yellow-200 text-yellow-200 rounded-lg block w-full p-2.5" 
                           required>
                </div>

                <div class="flex justify-between">
                    <button type="button" @click="modalOpen = false" 
                            class="text-gray-400 hover:text-gray-200">
                        Batal
                    </button>
                    <button type="submit" 
                            class="text-yellow-200 bg-yellow-700 hover:bg-yellow-800 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-sm px-4 py-2">
                        Ganti Password
                    </button>
                </div>
            </form>
        </div>
    </div>


    <!-- Modal untuk Mengganti Foto Profil -->
    <div x-show="modalOpenProfilePhoto" class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50" x-transition>
        <div class="bg-gray-900 rounded-lg p-6 w-96">
            <h2 class="text-lg font-bold mb-4">Ganti Foto Profil</h2>
            <form method="post" action="/profile-photo/update" enctype="multipart/form-data">
                <?= csrf_field() ?>
                
                <div class="mb-4">
                    <label for="profile_photo" class="block mb-2 text-sm">Pilih Foto Baru</label>
                    <input type="file" name="profile_photo" id="profile_photo" class="bg-transparent border border-yellow-200 text-yellow-200 rounded-lg block w-full p-2.5" required>
                    
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
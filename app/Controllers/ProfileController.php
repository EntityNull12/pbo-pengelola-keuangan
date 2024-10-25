<?php
namespace App\Controllers;

use App\Models\UserModel; // Pastikan Anda menggunakan model yang tepat

class ProfileController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel(); // Inisialisasi model
    }

    // Method untuk menampilkan halaman profil
    public function profile()
    {
        $userId = session()->get('user_id'); // Sesuaikan dengan cara Anda mendapatkan ID pengguna
        if (empty($userId)) {
            session()->setFlashdata('error', 'Anda harus login terlebih dahulu.');
            return redirect()->to('/login');
        }
        $user = $this->userModel->find($userId);
        $data = [
            'title' => 'Profil Pengguna',
            'nama' => $user['nama'],
            'profile_photo' => $user['profile_photo']?? 'profile.jpg', // Ambil nama file foto dari database
        ];
        return view('profile', $data);
    }

    // Method untuk mengupdate foto profil
    // Method untuk mengupdate foto profil
    public function updateProfilePhoto()
{
    $userId = session()->get('user_id'); // Ambil user_id dari sesi

    // Periksa apakah user_id ada
    if (empty($userId)) {
        session()->setFlashdata('error', 'ID pengguna tidak valid.');
        return redirect()->to('/profile'); // Sesuaikan dengan route ke halaman profil
    }

    $file = $this->request->getFile('profile_photo');
    if ($file->isValid() && !$file->hasMoved()) {
        // Mendapatkan nama file baru
        $newName = $file->getRandomName();
        // Memindahkan file ke folder uploads
        $file->move(FCPATH . 'uploads', $newName);

        $this->userModel->update($userId, ['profile_photo' => $newName]);
        // Mengupdate nama file di database dengan where() dan set()
        $this->userModel->where('id', $userId)->set(['profile_photo' => $newName])->update();

        session()->set('foto', $newName);

        // Set flashdata untuk notifikasi sukses
        session()->setFlashdata('success', 'Foto profil berhasil diubah!');
    } else {
        session()->setFlashdata('error', 'Gagal mengunggah foto.');
    }

    return redirect()->to('/profile'); // Sesuaikan dengan route ke halaman profil
}


}

<?php

namespace App\Controllers;

use App\Models\UserModel;

class ChangePassword extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        // Redirect ke halaman profil jika tidak ada sesi pengguna
        if (!session()->get('user')) {
            return redirect()->to('/login');
        }
        return view('template/header', ['title' => 'Change Password']);
    }

    public function update()
{
    $user = session()->get('user');
    $oldPassword = $this->request->getPost('old_password');
    $newPassword = $this->request->getPost('new_password');
    $confirmPassword = $this->request->getPost('confirm_password');

    // Cek jika password lama cocok
    if (!password_verify($oldPassword, $user['password'])) {
        session()->setFlashdata('old_password_error', 'Password lama salah.');
        return redirect()->back()->withInput();
    }

    // Validasi jika password baru dan konfirmasi password cocok
    if ($newPassword !== $confirmPassword) {
        session()->setFlashdata('confirm_password_error', 'Password baru dan konfirmasi tidak cocok.');
        return redirect()->back()->withInput();
    }

    // Simpan password baru ke database
    $this->userModel->update($user['id'], [
        'password' => password_hash($newPassword, PASSWORD_DEFAULT),
    ]);

    // Update session dengan password baru
    $user['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
    session()->set('user', $user);

    // Redirect ke dashboard dengan pesan sukses
    return redirect()->to('/dashboard')->with('success', 'Password berhasil diubah.');
}

}

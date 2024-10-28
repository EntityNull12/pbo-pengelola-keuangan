<?php

namespace App\Controllers;

use App\Models\UserModel;

class Register extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        // Debugging - cek status session
        log_message('debug', 'Session status: ' . (session()->get('logged_in') ? 'logged in' : 'not logged in'));
        
        // Jika user sudah login, redirect ke dashboard
        if (session()->get('logged_in')) {
            log_message('debug', 'User sudah login, redirect ke dashboard');
            return redirect()->to('/dashboard');
        }

        // Jika belum login, tampilkan halaman register
        $data = [
            'title' => 'Daftar'
        ];
        
        return view('template/header', $data)
             . view('register')
             . view('template/footer');
    }

    public function save()
{
    // Validasi input
    if (!$this->validate([
        'nama'     => 'required|min_length[3]|max_length[254]',
        'username' => 'required|min_length[3]|max_length[50]|is_unique[user.username]',
        'password' => 'required|min_length[6]',
    ])) {
        // Jika validasi gagal, arahkan kembali dan kirimkan pesan kesalahan
        $errors = $this->validator->getErrors();
        
        // Tambahkan pesan kesalahan khusus untuk username
        if (isset($errors['username'])) {
            $errors['username'] = 'Username sudah ada!';
        }

        return redirect()->back()->withInput()->with('errors', $errors);
    }

    // Simpan data ke database
    $this->userModel->save([
        'nama'     => $this->request->getPost('nama'),
        'username' => $this->request->getPost('username'),
        'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
    ]);

    // Arahkan ke halaman login setelah berhasil registrasi
    return redirect()->to('/login')->with('success', 'Pendaftaran berhasil! Silakan login.');
    }
}
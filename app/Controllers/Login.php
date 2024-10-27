<?php

namespace App\Controllers;

use App\Models\UserModel;

class Login extends BaseController
{
    protected $userModel;
    protected $session;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        // Jika user sudah login, redirect ke dashboard
        if (session()->get('user_id')) {
            return redirect()->to('/dashboard');
        }

        $data = [
            'title' => 'Masuk',
        ];
        echo view('template/header', $data);
        echo view('login', $data);
        echo view('template/footer');
    }
    
    public function authenticate()
    {
        // Validasi input
        $rules = [
            'username' => 'required|min_length[4]',
            'password' => 'required|min_length[6]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->with('error', 'Username atau password tidak valid.')
                ->withInput();
        }

        // Mendapatkan input dari form
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // Mencari pengguna berdasarkan username
        $user = $this->userModel->where('username', $username)->first();

        // Validasi username dan password
        if ($user && password_verify($password, $user['password'])) {
            // Set session data
            $sessionData = [
                'user_id'    => $user['id'],            // untuk identifikasi user
                'username'   => $user['username'],      // untuk tampilan
                'nama'       => $user['nama'],          // untuk tampilan
                'foto'       => $user['profile_photo'], // untuk tampilan
                'logged_in'  => true                    // untuk cek status login
            ];
            
            // Simpan data pengguna ke session
            session()->set($sessionData);

            // Log aktivitas login (opsional)
            log_message('info', 'User {username} logged in successfully', ['username' => $user['username']]);

            // Set flash message
            session()->setFlashdata('success', 'Berhasil login! Selamat datang ' . $user['nama']);
            
            return redirect()->to('/dashboard');
        }

        // Jika login gagal
        log_message('notice', 'Failed login attempt for username: {username}', ['username' => $username]);
        return redirect()->back()
            ->with('error', 'Username atau password salah.')
            ->withInput();
    }

    public function logout()
    {
        // Log aktivitas logout (opsional)
        if (session()->get('username')) {
            log_message('info', 'User {username} logged out', ['username' => session()->get('username')]);
        }

        // Hapus semua data sesi
        session()->destroy();

        // Set flash message
        session()->setFlashdata('success', 'Berhasil logout!');

        // Arahkan kembali ke halaman login
        return redirect()->to('/login');
    }
}
<?php

namespace App\Controllers;

use App\Models\UserModel;

class Login extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Masuk'
        ];

        echo view('template/header', $data);
        echo view('login');
        echo view('template/footer');
    }
    
    public function authenticate()
    {
        $userModel = new UserModel();

        // Mendapatkan input dari form
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // Mencari pengguna berdasarkan username
        $user = $userModel->where('username', $username)->first();

        // Validasi username dan password
        if ($user && password_verify($password, $user['password'])) {
            // Simpan data pengguna ke session
            session()->set('user', $user);
            return redirect()->to('/dashboard'); // Arahkan ke halaman dashboard
        } else {
            return redirect()->back()->with('error', 'Username atau password salah.')->withInput();
        }
    }
}

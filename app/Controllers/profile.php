<?php

namespace App\Controllers;

class Profile extends BaseController
{
    public function index()
    {
        // Mengambil data pengguna dari sesi
  
        $data = [
            'title' => 'Profile',
            'nama'  => session()->get('nama'), // Mengambil nama dari sesi
            'profile_photo' => session()->get('foto') ?? 'profile.jpg',
        ];
        echo view('template/header', $data);
        echo view('profile', $data);
        echo view('template/footer');
    }
}

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

        $userModel = new \App\Models\UserModel();
        $user = $userModel->findAll();
        dd($user);
        echo view('template/header', $data);
        echo view('login');
        echo view('template/footer');
    }
}

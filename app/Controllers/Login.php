<?php

namespace App\Controllers;

use App\Models\UserModel;

class Login extends BaseController
{
    protected $userModel;
    public function __construct()
    {
        $this->userModel = new UserModel();
    }
    public function index()
    {  
        $user = $this->userModel->findAll();
        $data = [
            'title' => 'Masuk',
            'user' => $user
        ];

        // $userModel = new \App\Models\UserModel();
      
        // dd($user);
        echo view('template/header', $data);
        echo view('login', $data);
        echo view('template/footer');
    }
}

<?php

namespace App\Controllers;

class Register extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Daftar'
        ];
        echo view('template/header', $data);
        echo view('register');
        echo view('template/footer');
    }
}

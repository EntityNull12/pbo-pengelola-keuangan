<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Home'
        ];
        echo view('template/header', $data);
        echo view('welcome');
        echo view('template/footer');
    }
}

<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Dashboard'
        ];
        echo view('template/header', $data);
        echo view('dashboard/dashboard');
        echo view('template/footer');
    }
    public function pengelola()
    {
        $data = [
            'title' => 'Pengelola'
        ];
        echo view('template/header', $data);
        echo view('dashboard/pengelola');
        echo view('template/footer');
    }
}

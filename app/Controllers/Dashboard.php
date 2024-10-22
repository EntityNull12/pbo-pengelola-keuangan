<?php

namespace App\Controllers;
use App\Models\PengelolaModel;

class Dashboard extends BaseController
{
    protected $pengelolaModel;
    public function __construct()
    {
        $this->pengelolaModel = new PengelolaModel();
    }
    public function index()
    {
        $pengelola = $this->pengelolaModel->findAll();
        $data = [
            'title' => 'Dashboard',
            'pengelola' => $pengelola
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

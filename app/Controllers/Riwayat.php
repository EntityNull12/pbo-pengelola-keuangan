<?php

namespace App\Controllers;

use App\Models\RiwayatModel; // Assume you have a RiwayatModel

class Riwayat extends BaseController
{
    protected $riwayatModel;

    public function __construct()
    {
        $this->riwayatModel = new RiwayatModel();
    }

    public function index()
    {
        // Fetch transaction history data
        $riwayat = $this->riwayatModel->findAll();

        $data = [
            'title' => 'Riwayat Transaksi',
            'riwayat' => $riwayat
        ];

        return view('template/header', $data)
             . view('riwayat/index')
             . view('template/footer');
    }

    public function detail($id)
    {
        // Fetch transaction detail by ID
        $riwayat = $this->riwayatModel->find($id);

        if (!$riwayat) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Transaksi dengan ID $id tidak ditemukan.");
        }

        $data = [
            'title' => 'Detail Transaksi',
            'riwayat' => $riwayat
        ];

        return view('template/header', $data)
             . view('riwayat/detail')
             . view('template/footer');
    }
}

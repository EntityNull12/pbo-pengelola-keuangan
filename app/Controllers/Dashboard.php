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
        return view('template/header', $data)
             . view('dashboard/dashboard')
             . view('template/footer');
    }

    public function pengelola()
    {
        $data = [
            'title' => 'Form Transaksi',
            'validation' => \Config\Services::validation()
        ];
        return view('template/header', $data)
             . view('dashboard/pengelola')
             . view('template/footer');
    }

    public function savePengelola()
    {
        // Validasi input
        if (!$this->validate([
            'jenis' => 'required',
            'nominal' => 'required',
            'tanggal' => 'required'
        ])) {
            session()->setFlashdata('error', 'Validasi gagal. Silakan cek kembali input Anda.');
            return redirect()->back()->withInput();
        }

        try {
            // Sanitasi dan persiapan data
            $nominal = str_replace('.', '', $this->request->getPost('nominal'));
            
            $data = [
                'pengelola' => 'Admin', // atau sesuai dengan pengelola yang login
                'jumlah' => $nominal,
                'tanggal' => $this->request->getPost('tanggal'),
                'deskripsi' => $this->request->getPost('jenis') === 'pemasukan' 
                    ? $this->request->getPost('deskripsi')
                    : $this->request->getPost('deskripsiPengeluaran'),
                'kategori_transaksi' => $this->request->getPost('jenis') === 'pengeluaran' 
                    ? $this->request->getPost('kategori')
                    : null,
                'tipe_catatan' => $this->request->getPost('jenis')
            ];

            // Debug: print data yang akan disimpan
            // log_message('debug', print_r($data, true));

            // Simpan ke database
            if ($this->pengelolaModel->insert($data)) {
                session()->setFlashdata('success', 'Data berhasil disimpan');
                return redirect()->to('/pengelola');
            } else {
                session()->setFlashdata('error', 'Gagal menyimpan data');
                return redirect()->back()->withInput();
            }
        } catch (\Exception $e) {
            log_message('error', $e->getMessage());
            session()->setFlashdata('error', 'Terjadi kesalahan sistem');
            return redirect()->back()->withInput();
        }
    }

    public function riwayat()
    {
        $pengelolaModel = new \App\Models\PengelolaModel();
        
        // Get all transactions
        $transactions = $pengelolaModel->findAll();
        
        // Calculate total pemasukan and total pengeluaran
        $totalPemasukan = $pengelolaModel->getTotalPemasukan();
        $totalPengeluaran = $pengelolaModel->getTotalPengeluaran();
        $saldo = $pengelolaModel->getSaldo();
    
        $data = [
            'title' => 'Riwayat Transaksi',
            'transactions' => $transactions,
            'totalPemasukan' => $totalPemasukan,
            'totalPengeluaran' => $totalPengeluaran,
            'saldo' => $saldo
        ];
        
        return view('template/header', $data)
             . view('dashboard/riwayat') // Make sure this view is correctly set up
             . view('template/footer');
    }    
}

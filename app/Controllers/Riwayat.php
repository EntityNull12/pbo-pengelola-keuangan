<?php

namespace App\Controllers;

use App\Models\RiwayatModel;

class Riwayat extends BaseController
{
    protected $riwayatModel;
    protected $session;

    public function __construct()
    {
        $this->riwayatModel = new RiwayatModel();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        // Cek apakah user sudah login
        if (!session()->get('user_id')) {
            return redirect()->to('/login');
        }

        // Ambil parameter filter
        $jenis = $this->request->getGet('jenis');
        $startDate = $this->request->getGet('start');
        $endDate = $this->request->getGet('end');

        // Jika tidak ada tanggal yang dipilih, gunakan rentang bulan ini
        if (!$startDate) {
            $startDate = date('Y-m-01'); // Awal bulan ini
        }
        if (!$endDate) {
            $endDate = date('Y-m-t');    // Akhir bulan ini
        }

        // Ambil data transaksi sesuai filter
        $transactions = $this->riwayatModel->getFilteredTransactions(
            session()->get('user_id'),
            $jenis,
            $startDate,
            $endDate
        );

        // Hitung total
        $totalPemasukan = $this->riwayatModel->getTotalPemasukan(
            session()->get('user_id'),
            $startDate,
            $endDate
        );
        $totalPengeluaran = $this->riwayatModel->getTotalPengeluaran(
            session()->get('user_id'),
            $startDate,
            $endDate
        );
        $saldo = $totalPemasukan - $totalPengeluaran;

        $data = [
            'title' => 'Riwayat Transaksi',
            'transactions' => $transactions,
            'totalPemasukan' => $totalPemasukan,
            'totalPengeluaran' => $totalPengeluaran,
            'saldo' => $saldo
        ];

        return view('template/header', $data)
            . view('riwayat/index', $data)
            . view('template/footer');
    }

    public function delete($id)
    {
        // Cek apakah user sudah login
        if (!session()->get('user_id')) {
            return $this->response->setJSON(['success' => false]);
        }

        // Verifikasi bahwa transaksi ini milik user yang sedang login
        $transaction = $this->riwayatModel->where('id', $id)
            ->where('pengelola', session()->get('user_id'))
            ->first();

        if (!$transaction) {
            return $this->response->setJSON(['success' => false]);
        }

        // Hapus transaksi
        if ($this->riwayatModel->delete($id)) {
            return $this->response->setJSON(['success' => true]);
        }

        return $this->response->setJSON(['success' => false]);
    }

    public function edit($id)
{
    // Cek apakah user sudah login
    if (!session()->get('user_id')) {
        return $this->response->setJSON(['error' => 'Unauthorized']);
    }

    // Ambil data transaksi
    $transaction = $this->riwayatModel->where('id', $id)
        ->where('pengelola', session()->get('user_id'))
        ->first();

    if (!$transaction) {
        return $this->response->setJSON(['error' => 'Not found']);
    }

    // Jika request adalah AJAX, return JSON
    if ($this->request->isAJAX()) {
        return $this->response->setJSON($transaction);
    }

    // Jika bukan AJAX, tampilkan view seperti biasa
    $data = [
        'title' => 'Edit Transaksi',
        'transaction' => $transaction
    ];

    return view('template/header', $data)
        . view('riwayat/edit', $data)
        . view('template/footer');
}

public function update($id)
{
    // Cek apakah user sudah login
    if (!session()->get('user_id')) {
        return redirect()->to('/login');
    }

    // Verifikasi bahwa transaksi ini milik user yang sedang login
    $transaction = $this->riwayatModel->where('id', $id)
        ->where('pengelola', session()->get('user_id'))
        ->first();

    if (!$transaction) {
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    }

    // Update transaksi
    $data = [
        'jumlah' => str_replace('.', '', $this->request->getPost('nominal')),
        'tanggal' => $this->request->getPost('tanggal'),
        'tipe_catatan' => $this->request->getPost('jenis')
    ];

    // Set deskripsi berdasarkan jenis transaksi
    if ($data['tipe_catatan'] === 'pemasukan') {
        $data['deskripsi'] = $this->request->getPost('deskripsi');
        $data['kategori_transaksi'] = null;
    } else {
        $data['deskripsi'] = $this->request->getPost('deskripsiPengeluaran');
        $data['kategori_transaksi'] = $this->request->getPost('kategori');
    }

    if ($this->riwayatModel->update($id, $data)) {
        session()->setFlashdata('success', 'Transaksi berhasil diperbarui');
    } else {
        session()->setFlashdata('error', 'Gagal memperbarui transaksi');
    }

    return redirect()->to('/dashboard/riwayat');
}

    public function update($id)
    {
        // Cek apakah user sudah login
        if (!session()->get('user_id')) {
            return redirect()->to('/login');
        }

        // Verifikasi bahwa transaksi ini milik user yang sedang login
        $transaction = $this->riwayatModel->where('id', $id)
            ->where('pengelola', session()->get('user_id'))
            ->first();

        if (!$transaction) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Update transaksi
        $data = [
            'jumlah' => str_replace('.', '', $this->request->getPost('jumlah')),
            'tanggal' => $this->request->getPost('tanggal'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'kategori_transaksi' => $this->request->getPost('kategori'),
            'tipe_catatan' => $this->request->getPost('tipe_catatan')
        ];

        if ($this->riwayatModel->update($id, $data)) {
            session()->setFlashdata('success', 'Transaksi berhasil diperbarui');
        } else {
            session()->setFlashdata('error', 'Gagal memperbarui transaksi');
        }

        return redirect()->to('/riwayat');
    }public function update($id)
    {
        // Cek apakah user sudah login
        if (!session()->get('user_id')) {
            return redirect()->to('/login');
        }
    
        // Verifikasi bahwa transaksi ini milik user yang sedang login
        $transaction = $this->riwayatModel->where('id', $id)
            ->where('pengelola', session()->get('user_id'))
            ->first();
    
        if (!$transaction) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    
        // Update transaksi
        $data = [
            'jumlah' => str_replace('.', '', $this->request->getPost('nominal')),
            'tanggal' => $this->request->getPost('tanggal'),
            'tipe_catatan' => $this->request->getPost('jenis')
        ];
    
        // Set deskripsi berdasarkan jenis transaksi
        if ($data['tipe_catatan'] === 'pemasukan') {
            $data['deskripsi'] = $this->request->getPost('deskripsi');
            $data['kategori_transaksi'] = null;
        } else {
            $data['deskripsi'] = $this->request->getPost('deskripsiPengeluaran');
            $data['kategori_transaksi'] = $this->request->getPost('kategori');
        }
    
        if ($this->riwayatModel->update($id, $data)) {
            session()->setFlashdata('success', 'Transaksi berhasil diperbarui');
        } else {
            session()->setFlashdata('error', 'Gagal memperbarui transaksi');
        }
    
        return redirect()->to('/dashboard/riwayat');
    }
}
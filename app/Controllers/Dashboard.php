<?php

namespace App\Controllers;

use App\Models\PengelolaModel;

class Dashboard extends BaseController
{
    protected $pengelolaModel;
    protected $session;

    public function __construct()
    {
        $this->pengelolaModel = new PengelolaModel();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        // Cek apakah user sudah login
        if (!session()->get('user_id')) {
            return redirect()->to('/login');
        }
    
        // Ambil data untuk dashboard
        $startDate = date('Y-m-01'); // Awal bulan ini
        $endDate = date('Y-m-t');    // Akhir bulan ini
    
        // Hitung total pemasukan, pengeluaran, dan saldo berdasarkan user yang login
        $user_id = session()->get('user_id');
        
        // Ambil data transaksi sesuai filter
        $recentTransactions = $this->pengelolaModel->getFilteredTransactions(
            $user_id,
            null, // jenis transaksi (null untuk semua)
            $startDate,
            $endDate
        );
    
        // Hitung total berdasarkan user yang login
        $totalPemasukan = $this->pengelolaModel->getTotalPemasukan($startDate, $endDate);
        $totalPengeluaran = $this->pengelolaModel->getTotalPengeluaran($startDate, $endDate);
        $saldo = $totalPemasukan - $totalPengeluaran;
    
        // Data yang akan dikirim ke view
        $data = [
            'title' => 'Dashboard',
            'user' => session()->get('nama'),
            'saldo' => $saldo,
            'total_pemasukan' => $totalPemasukan,
            'total_pengeluaran' => $totalPengeluaran,
            'recent_transactions' => $recentTransactions
        ];
    
        // Load view
        return view('template/header', $data)
            . view('dashboard/dashboard', $data)
            . view('template/footer');
    }

    public function pengelola()
    {
        // Cek apakah user sudah login
        if (!session()->get('user_id')) {
            return redirect()->to('/login');
        }

        $data = [
            'title' => 'Pengelola Keuangan',
            'user' => session()->get('nama')
        ];

        return view('template/header', $data)
            . view('dashboard/pengelola', $data)
            . view('template/footer');
    }

    public function savePengelola()
    {
        // Cek apakah user sudah login
        if (!session()->get('user_id')) {
            return redirect()->to('/login');
        }

        try {
            // Get the logged in user's ID
            $pengelolaId = session()->get('user_id');

            // Clean the nominal value (remove dots)
            $nominal = str_replace('.', '', $this->request->getPost('nominal'));

            // Prepare the data
            $data = [
                'pengelola' => $pengelolaId,
                'jumlah' => $nominal,
                'tanggal' => $this->request->getPost('tanggal'),
                'tipe_catatan' => $this->request->getPost('jenis')
            ];

            // Add appropriate description based on transaction type
            if ($data['tipe_catatan'] === 'pemasukan') {
                $data['deskripsi'] = $this->request->getPost('deskripsi');
                $data['kategori_transaksi'] = null; // No category for income
            } else {
                $data['deskripsi'] = $this->request->getPost('deskripsiPengeluaran');
                $data['kategori_transaksi'] = $this->request->getPost('kategori');
            }

            // Debug log
            log_message('debug', 'Attempting to save transaction data: ' . json_encode($data));

            // Save to database
            if ($this->pengelolaModel->save($data)) {
                session()->setFlashdata('success', 'Transaksi berhasil disimpan');
            } else {
                session()->setFlashdata('error', 'Gagal menyimpan transaksi: ' . implode(', ', $this->pengelolaModel->errors()));
            }

        } catch (\Exception $e) {
            log_message('error', 'Error saving transaction: ' . $e->getMessage());
            session()->setFlashdata('error', 'Terjadi kesalahan saat menyimpan transaksi');
        }

        return redirect()->back();
    }

    public function riwayat()
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

        // Ambil user_id dari session
        $user_id = session()->get('user_id');

        // Ambil data transaksi sesuai filter
        $transactions = $this->pengelolaModel->getFilteredTransactions(
            $user_id,
            $jenis,
            $startDate,
            $endDate
        );

        // Hitung total berdasarkan user yang login
        $totalPemasukan = $this->pengelolaModel->getTotalPemasukan($startDate, $endDate);
        $totalPengeluaran = $this->pengelolaModel->getTotalPengeluaran($startDate, $endDate);
        $saldo = $totalPemasukan - $totalPengeluaran;

        $data = [
            'title' => 'Riwayat Transaksi',
            'transactions' => $transactions,
            'totalPemasukan' => $totalPemasukan,
            'totalPengeluaran' => $totalPengeluaran,
            'saldo' => $saldo
        ];

        return view('template/header', $data)
            . view('dashboard/riwayat', $data)
            . view('template/footer');
    }

    public function deleteRiwayat($id)
    {
        // Cek apakah user sudah login
        if (!session()->get('user_id')) {
            return $this->response->setJSON(['success' => false]);
        }

        // Verifikasi bahwa transaksi ini milik user yang sedang login
        $transaction = $this->pengelolaModel->where('id', $id)
            ->where('pengelola', session()->get('user_id'))
            ->first();

        if (!$transaction) {
            return $this->response->setJSON(['success' => false]);
        }

        // Hapus transaksi
        if ($this->pengelolaModel->delete($id)) {
            return $this->response->setJSON(['success' => true]);
        }

        return $this->response->setJSON(['success' => false]);
    }

    public function editRiwayat($id)
    {
        // Cek apakah user sudah login
        if (!session()->get('user_id')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }
    
        // Verifikasi bahwa transaksi ini milik user yang sedang login
        $transaction = $this->pengelolaModel->where('id', $id)
            ->where('pengelola', session()->get('user_id'))
            ->first();
    
        if (!$transaction) {
            return $this->response->setJSON(['success' => false, 'message' => 'Data tidak ditemukan']);
        }
    
        // Jika request adalah AJAX, return JSON
        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => true,
                'data' => $transaction
            ]);
        }
    
        // Jika bukan AJAX, tampilkan view seperti biasa
        $data = [
            'title' => 'Edit Transaksi',
            'transaction' => $transaction
        ];
    
        return view('template/header', $data)
            . view('dashboard/edit', $data)
            . view('template/footer');
    }
    
    public function updateRiwayat($id)
    {
        // Cek apakah user sudah login
        if (!session()->get('user_id')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }
    
        // Verifikasi bahwa transaksi ini milik user yang sedang login
        $transaction = $this->pengelolaModel->where('id', $id)
            ->where('pengelola', session()->get('user_id'))
            ->first();
    
        if (!$transaction) {
            return $this->response->setJSON(['success' => false, 'message' => 'Data tidak ditemukan']);
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
    
        if ($this->pengelolaModel->update($id, $data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Transaksi berhasil diperbarui'
            ]);
        } 
    
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Gagal memperbarui transaksi'
        ]);
    }
}

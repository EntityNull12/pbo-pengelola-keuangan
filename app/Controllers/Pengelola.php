<?php

namespace App\Controllers;

use App\Models\PengelolaModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Pengelola extends BaseController
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
        $currentPage = $this->request->getVar('page_pengelola') ?? 1;

        $keyword = $this->request->getVar('keyword');
        if ($keyword) {
            $pengelola = $this->pengelolaModel->search($keyword);
        } else {
            $pengelola = $this->pengelolaModel;
        }

        $data = [
            'title' => 'Daftar Transaksi',
            'pengelola' => $pengelola->paginate(10, 'pengelola'),
            'pager' => $this->pengelolaModel->pager,
            'currentPage' => $currentPage
        ];

        return view('template/header', $data)
            . view('pengelola/index')
            . view('template/footer');
    }


    public function save()
    {
        // Validasi input
        if (!$this->validate([
            'jumlah' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Jumlah harus diisi.'
                ]
            ],
            'tanggal' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Tanggal harus diisi.'
                ]
            ]
        ])) {
            $validation = \Config\Services::validation();
            return redirect()->back()->withInput()->with('validation', $validation);
        }

        try {
            // Persiapkan data untuk disimpan
            $data = [
                'jumlah' => $this->request->getPost('jumlah'),
                'tanggal' => $this->request->getPost('tanggal'),
                'deskripsi' => $this->request->getPost('deskripsi'),
                'kategori_transaksi' => $this->request->getPost('kategori_transaksi'),
                'tipe_catatan' => $this->request->getPost('tipe_catatan')
            ];

            // Debug: Tampilkan data yang akan disimpan
            // log_message('debug', 'Data yang akan disimpan: ' . print_r($data, true));

            // Simpan data
            if ($this->pengelolaModel->insert($data)) {
                session()->setFlashdata('success', 'Data transaksi berhasil ditambahkan.');
                return redirect()->to('/pengelola');
            } else {
                session()->setFlashdata('error', 'Gagal menambahkan data transaksi.');
                return redirect()->back()->withInput();
            }
        } catch (\Exception $e) {
            log_message('error', 'Error saat menyimpan data: ' . $e->getMessage());
            session()->setFlashdata('error', 'Terjadi kesalahan saat menyimpan data.');
            return redirect()->back()->withInput();
        }
    }

    public function update($id)
    {
        // Validasi input
        if (!$this->validate([
            'jumlah' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Jumlah harus diisi.'
                ]
            ],
            'tanggal' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Tanggal harus diisi.'
                ]
            ]
        ])) {
            return redirect()->back()->withInput();
        }

        try {
            $data = [
                'id' => $id,
                'jumlah' => $this->request->getPost('jumlah'),
                'tanggal' => $this->request->getPost('tanggal'),
                'deskripsi' => $this->request->getPost('deskripsi'),
                'kategori_transaksi' => $this->request->getPost('kategori_transaksi'),
                'tipe_catatan' => $this->request->getPost('tipe_catatan')
            ];

            if ($this->pengelolaModel->save($data)) {
                session()->setFlashdata('success', 'Data transaksi berhasil diperbarui.');
                return redirect()->to('/pengelola');
            }
        } catch (\Exception $e) {
            log_message('error', 'Error saat update data: ' . $e->getMessage());
            session()->setFlashdata('error', 'Terjadi kesalahan saat memperbarui data.');
        }

        return redirect()->back()->withInput();
    }

    // ... (method lain tetap sama)
}
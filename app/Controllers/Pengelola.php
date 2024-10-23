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

<<<<<<< Tabnine <<<<<<<
    /**//+
     * Displays the list of transactions.//+
     *//+
     * @return string The rendered view with the list of transactions.//+
     *///+
    public function index()
    {
        // Get the current page number from the request query parameter//+
        $currentPage = $this->request->getVar('page_pengelola') ?? 1;

        // Get the search keyword from the request query parameter//+
        $keyword = $this->request->getVar('keyword');
        if ($keyword) {
            // If a keyword is provided, search for transactions matching the keyword//+
            $pengelola = $this->pengelolaModel->search($keyword);
        } else {
            // If no keyword is provided, retrieve all transactions//+
            $pengelola = $this->pengelolaModel;
        }

        // Prepare the data for the view//+
        $data = [
            'title' => 'Daftar Transaksi',
            'pengelola' => $pengelola->paginate(10, 'pengelola'),
            'pager' => $this->pengelolaModel->pager,
            'currentPage' => $currentPage
        ];

        // Render the views with the data and return the result as a string//+
        return view('template/header', $data)
            . view('pengelola/index')
            . view('template/footer');
    }
>>>>>>> Tabnine >>>>>>>// {"conversationId":"2efedfdb-f812-451c-aa08-efa507361c58","source":"instruct"}

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
<?php

namespace App\Models;

use CodeIgniter\Model;

class PengelolaModel extends Model
{
    protected $table = 'pengelola_uang';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'pengelola',
        'jumlah',
        'tanggal',
        'deskripsi',
        'kategori_transaksi',
        'tipe_catatan'
    ];
    
    protected $useTimestamps = false;

    // Validasi
    protected $validationRules = [
        'pengelola' => 'required|numeric',
        'jumlah' => 'required|numeric',
        'tanggal' => 'required',
        'tipe_catatan' => 'required|in_list[pemasukan,pengeluaran]'
    ];

    protected $validationMessages = [
        'pengelola' => [
            'required' => 'ID pengelola harus ada.',
            'numeric' => 'ID pengelola harus berupa angka.'
        ],
        'jumlah' => [
            'required' => 'Jumlah harus diisi.',
            'numeric' => 'Jumlah harus berupa angka.'
        ],
        'tanggal' => [
            'required' => 'Tanggal harus diisi.'
        ],
        'tipe_catatan' => [
            'required' => 'Tipe catatan harus diisi.',
            'in_list' => 'Tipe catatan harus pemasukan atau pengeluaran.'
        ]
    ];

    // Add the missing getFilteredTransactions method
    public function getFilteredTransactions($userId, $jenis = null, $startDate = null, $endDate = null)
    {
        $builder = $this->builder();
        
        // Filter berdasarkan user ID
        $builder->where('pengelola', $userId);
        
        // Filter berdasarkan jenis transaksi
        if ($jenis && $jenis !== 'semua') {
            $builder->where('tipe_catatan', $jenis);
        }
        
        // Filter berdasarkan tanggal
        if ($startDate) {
            $builder->where('DATE(tanggal) >=', $startDate);
        }
        if ($endDate) {
            $builder->where('DATE(tanggal) <=', $endDate);
        }

        // Urutkan berdasarkan tanggal terbaru
        $builder->orderBy('tanggal', 'DESC');

        return $builder->get()->getResultArray();
    }

    // Existing methods remain the same
    public function getTotalPemasukan($startDate = null, $endDate = null)
    {
        $builder = $this->builder();
        $builder->selectSum('jumlah');
        $builder->where('tipe_catatan', 'pemasukan');
        
        // Filter berdasarkan user yang sedang login
        if (session()->get('user_id')) {
            $builder->where('pengelola', session()->get('user_id'));
        }
        
        if ($startDate) {
            $builder->where('DATE(tanggal) >=', $startDate);
        }
        if ($endDate) {
            $builder->where('DATE(tanggal) <=', $endDate);
        }

        $result = $builder->get()->getRow();
        return $result->jumlah ?? 0;
    }

    public function getTotalPengeluaran($startDate = null, $endDate = null)
    {
        $builder = $this->builder();
        $builder->selectSum('jumlah');
        $builder->where('tipe_catatan', 'pengeluaran');
        
        // Filter berdasarkan user yang sedang login
        if (session()->get('user_id')) {
            $builder->where('pengelola', session()->get('user_id'));
        }
        
        if ($startDate) {
            $builder->where('DATE(tanggal) >=', $startDate);
        }
        if ($endDate) {
            $builder->where('DATE(tanggal) <=', $endDate);
        }

        $result = $builder->get()->getRow();
        return $result->jumlah ?? 0;
    }

    public function getSaldo($startDate = null, $endDate = null)
    {
        $pemasukan = $this->getTotalPemasukan($startDate, $endDate);
        $pengeluaran = $this->getTotalPengeluaran($startDate, $endDate);
        return $pemasukan - $pengeluaran;
    }

    public function getRiwayat($startDate = null, $endDate = null)
    {
        $builder = $this->builder();
        
        // Filter berdasarkan user yang sedang login
        if (session()->get('user_id')) {
            $builder->where('pengelola', session()->get('user_id'));
        }

        if ($startDate) {
            $builder->where('DATE(tanggal) >=', $startDate);
        }
        if ($endDate) {
            $builder->where('DATE(tanggal) <=', $endDate);
        }

        // Urutkan berdasarkan tanggal terbaru
        $builder->orderBy('tanggal', 'DESC');
        
        // Batasi hanya 10 transaksi terakhir untuk dashboard
        $builder->limit(10);

        return $builder->get()->getResultArray();
    }

    public function beforeInsert(array $data)
    {
        // Debug log before insert
        log_message('debug', 'Data before insert: ' . json_encode($data));
        return $data;
    }

    // Method untuk pencarian
    public function search($keyword)
    {
        $builder = $this->builder();
        
        // Filter berdasarkan user yang sedang login
        if (session()->get('user_id')) {
            $builder->where('pengelola', session()->get('user_id'));
        }

        return $builder->like('deskripsi', $keyword)
                      ->orLike('kategori_transaksi', $keyword)
                      ->orLike('jumlah', $keyword);
    }
}
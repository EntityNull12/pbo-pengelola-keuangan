<?php

namespace App\Models;

use CodeIgniter\Model;

class RiwayatModel extends Model
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

    // Method untuk mendapatkan transaksi berdasarkan filter
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

    // Method untuk mendapatkan total pemasukan
    public function getTotalPemasukan($userId, $startDate = null, $endDate = null)
    {
        $builder = $this->builder();
        $builder->selectSum('jumlah');
        $builder->where('tipe_catatan', 'pemasukan');
        $builder->where('pengelola', $userId);
        
        if ($startDate) {
            $builder->where('DATE(tanggal) >=', $startDate);
        }
        if ($endDate) {
            $builder->where('DATE(tanggal) <=', $endDate);
        }

        $result = $builder->get()->getRow();
        return $result->jumlah ?? 0;
    }

    // Method untuk mendapatkan total pengeluaran
    public function getTotalPengeluaran($userId, $startDate = null, $endDate = null)
    {
        $builder = $this->builder();
        $builder->selectSum('jumlah');
        $builder->where('tipe_catatan', 'pengeluaran');
        $builder->where('pengelola', $userId);
        
        if ($startDate) {
            $builder->where('DATE(tanggal) >=', $startDate);
        }
        if ($endDate) {
            $builder->where('DATE(tanggal) <=', $endDate);
        }

        $result = $builder->get()->getRow();
        return $result->jumlah ?? 0;
    }
}
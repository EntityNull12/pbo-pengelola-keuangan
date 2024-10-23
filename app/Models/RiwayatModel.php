<?php

namespace App\Models;

use CodeIgniter\Model;

class RiwayatModel extends Model
{
    protected $table = 'pengelola_keuangan';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields = [
        'pengelola',
        'jumlah',
        'tanggal',
        'deskripsi',
        'kategori_transaksi',
        'tipe_catatan'
    ];
    protected $useTimestamps = false;

    // Method to get all transaction history
    public function getAllRiwayat($startDate = null, $endDate = null)
    {
        $builder = $this->builder();
        
        if ($startDate) {
            $builder->where('DATE(tanggal) >=', $startDate);
        }
        if ($endDate) {
            $builder->where('DATE(tanggal) <=', $endDate);
        }

        // Order by latest transaction date
        $builder->orderBy('tanggal', 'DESC');

        return $builder->get()->getResultArray();
    }

    // Method to get detailed transaction history by ID
    public function getRiwayatById($id)
    {
        return $this->find($id);
    }

    // Method to filter transaction history by type (income/expense)
    public function getFilteredRiwayat($jenis = null, $startDate = null, $endDate = null)
    {
        $builder = $this->builder();

        if ($jenis && $jenis !== 'semua') {
            $builder->where('tipe_catatan', $jenis);
        }
        if ($startDate) {
            $builder->where('DATE(tanggal) >=', $startDate);
        }
        if ($endDate) {
            $builder->where('DATE(tanggal) <=', $endDate);
        }

        // Order by latest transaction date
        $builder->orderBy('tanggal', 'DESC');

        return $builder->get()->getResultArray();
    }
}

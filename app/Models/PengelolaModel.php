<?php

namespace App\Models;

use CodeIgniter\Model;

class PengelolaModel extends Model
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

    // Validasi
    protected $validationRules = [
        'jumlah' => 'required',
        'tanggal' => 'required'
    ];

    protected $validationMessages = [
        'jumlah' => [
            'required' => 'Jumlah harus diisi.'
        ],
        'tanggal' => [
            'required' => 'Tanggal harus diisi.'
        ]
    ];

    // Method untuk mendapatkan transaksi dengan filter
    public function getFilteredTransactions($jenis = null, $startDate = null, $endDate = null)
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

        // Urutkan berdasarkan tanggal terbaru
        $builder->orderBy('tanggal', 'DESC');

        return $builder->get()->getResultArray();
    }

    // Method untuk mendapatkan total pemasukan
    public function getTotalPemasukan($startDate = null, $endDate = null)
    {
        $builder = $this->builder();
        $builder->selectSum('jumlah');
        $builder->where('tipe_catatan', 'pemasukan');
        
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
    public function getTotalPengeluaran($startDate = null, $endDate = null)
    {
        $builder = $this->builder();
        $builder->selectSum('jumlah');
        $builder->where('tipe_catatan', 'pengeluaran');
        
        if ($startDate) {
            $builder->where('DATE(tanggal) >=', $startDate);
        }
        if ($endDate) {
            $builder->where('DATE(tanggal) <=', $endDate);
        }

        $result = $builder->get()->getRow();
        return $result->jumlah ?? 0;
    }

    // Method untuk mendapatkan saldo
    public function getSaldo($startDate = null, $endDate = null)
    {
        $pemasukan = $this->getTotalPemasukan($startDate, $endDate);
        $pengeluaran = $this->getTotalPengeluaran($startDate, $endDate);
        return $pemasukan - $pengeluaran;
    }

    // Method untuk mendapatkan transaksi berdasarkan ID
    public function getTransaksiById($id)
    {
        return $this->find($id);
    }

    // Method untuk mencari transaksi
    public function search($keyword)
    {
        return $this->table($this->table)
            ->like('deskripsi', $keyword)
            ->orLike('kategori_transaksi', $keyword)
            ->orLike('jumlah', $keyword);
    }

    // Method untuk mendapatkan ringkasan per kategori
    public function getRingkasanKategori($startDate = null, $endDate = null)
    {
        $builder = $this->builder();
        $builder->select('kategori_transaksi, SUM(jumlah) as total');
        $builder->where('tipe_catatan', 'pengeluaran');
        $builder->whereNotNull('kategori_transaksi');
        
        if ($startDate) {
            $builder->where('DATE(tanggal) >=', $startDate);
        }
        if ($endDate) {
            $builder->where('DATE(tanggal) <=', $endDate);
        }

        $builder->groupBy('kategori_transaksi');
        return $builder->get()->getResultArray();
    }
    // Method for getting all transaction history
public function getRiwayat($startDate = null, $endDate = null, $jenis = null)
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

    // Order by most recent first
    $builder->orderBy('tanggal', 'DESC');
    
    return $builder->get()->getResultArray();
}
// Method to search through transaction history with optional date range
public function searchRiwayat($keyword, $startDate = null, $endDate = null)
{
    $builder = $this->table($this->table);
    
    if ($startDate) {
        $builder->where('DATE(tanggal) >=', $startDate);
    }
    
    if ($endDate) {
        $builder->where('DATE(tanggal) <=', $endDate);
    }

    return $builder->like('deskripsi', $keyword)
                   ->orLike('kategori_transaksi', $keyword)
                   ->orLike('jumlah', $keyword)
                   ->get()
                   ->getResultArray();
}

}
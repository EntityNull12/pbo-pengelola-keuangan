<?php // Pastikan ini adalah baris pertama dalam file

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'user'; // Nama tabel
    protected $primaryKey = 'id'; // Kolom primary key

    // Kolom yang boleh diisi secara massal
    protected $allowedFields = ['nama', 'username', 'password'];

    // Opsi untuk otomatis mencatat timestamp
    protected $useTimestamps = true;
}

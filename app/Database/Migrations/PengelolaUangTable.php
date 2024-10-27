<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePengelolaUangTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'pengelola' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'jumlah' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
            ],
            'tanggal' => [
                'type' => 'DATETIME',
            ],
            'deskripsi' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'kategori_transaksi' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'tipe_catatan' => [
                'type' => 'ENUM',
                'constraint' => ['pemasukan', 'pengeluaran'],
                'default' => 'pemasukan',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('pengelola', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('pengelola_uang');
    }

    public function down()
    {
        $this->forge->dropTable('pengelola_uang');
    }
}
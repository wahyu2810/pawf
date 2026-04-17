<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'title' => 'Sistem Internal Bawaslu Kabupaten Bogor',
                'slug' => 'sistem-internal-bawaslu-kabupaten-bogor',
                'content' => 'Sistem ini saya buat sewaktu magang di kantor Bawaslu Kabupaten Bogor. Sistem ini digunakan untuk membantu kinerja internal Bawaslu Kabupaten Bogor.',
                'image' => 'sistem-internal-bawaslu-kabupaten-bogor.png',
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];
        // Insert batch
        $this->db->table('posts')->insertBatch($data);
    }
}

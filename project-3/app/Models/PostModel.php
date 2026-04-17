<?php

namespace App\Models;

use CodeIgniter\Model;

class PostModel extends Model
{
    protected $table            = 'posts';
    protected $primaryKey       = 'id';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $useSoftDeletes   = false;

    // WAJIB diisi agar bisa insert/update
    protected $allowedFields    = [
        'title',
        'slug',
        'content',
        'image',
        'created_at'
    ];

    // Aktifkan timestamps otomatis
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = null; // kita tidak pakai updated_at

    protected $dateFormat    = 'datetime';

    // Optional (biar lebih aman & rapi)
    protected $validationRules = [
        'title' => 'required|min_length[3]',
        'content' => 'required'
    ];

    protected $validationMessages = [
        'title' => [
            'required' => 'Judul wajib diisi',
        ],
        'content' => [
            'required' => 'Konten tidak boleh kosong',
        ]
    ];
}
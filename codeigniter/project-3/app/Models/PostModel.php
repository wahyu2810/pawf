<?php

namespace App\Models;

use CodeIgniter\Model;

class PostModel extends Model
{
    protected $table      = 'posts';
    protected $primaryKey = 'id';

    protected $returnType = 'array';

    // ✅ Field yang boleh diinsert
    protected $allowedFields = [
        'title',
        'slug',
        'content',
        'image',
        'created_at'
    ];
    protected $useTimestamps = false;

    // Optional validation
    protected $validationRules = [
        'title' => 'required|min_length[3]',
        'content' => 'required'
    ];
}
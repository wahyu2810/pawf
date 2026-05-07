<?php

namespace App\Models;

use CodeIgniter\Model;

class PostModel extends Model
{
    protected $table = 'posts';

    protected $allowedFields = [
        'content',
        'image',
        'status',
        'user_id'
    ];

    protected $useTimestamps = true;
}
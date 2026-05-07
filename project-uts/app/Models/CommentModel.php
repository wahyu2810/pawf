<?php

namespace App\Models;

use CodeIgniter\Model;

class CommentModel extends Model
{
    protected $table = 'comments';

    protected $allowedFields = [
        'post_id',
        'user_id',
        'comment'
    ];
}
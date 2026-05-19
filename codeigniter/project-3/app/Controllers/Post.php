<?php

namespace App\Controllers;

use App\Models\PostModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Post extends BaseController
{
    protected $postModel;

    public function __construct()
    {
        $this->postModel = new PostModel();
    }

    // Halaman utama (list semua post)
    public function index()
    {
        $data = [
            'posts' => $this->postModel
                ->orderBy('id', 'DESC')
                ->findAll()
        ];

        return view('post/index', $data);
    }

    // Halaman detail (Read More)
    public function detail($slug)
    {
        $post = $this->postModel
            ->where('slug', $slug)
            ->first();

        // Jika data tidak ditemukan → 404
        if (!$post) {
            throw PageNotFoundException::forPageNotFound('Post tidak ditemukan');
        }

        return view('post/detail', [
            'post' => $post
        ]);
    }
}
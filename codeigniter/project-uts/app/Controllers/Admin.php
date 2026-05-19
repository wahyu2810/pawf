<?php

namespace App\Controllers;

use App\Models\PostModel;
use App\Models\CommentModel;
use App\Models\LikeModel;

class Admin extends BaseController
{
    protected $postModel;
    protected $commentModel;
    protected $likeModel;

    public function __construct()
    {
        $this->postModel = new PostModel();
        $this->commentModel = new CommentModel();
        $this->likeModel = new LikeModel();
    }

    public function index()
    {
        $data = [
            'total_post' => $this->postModel->countAll(),
            'total_comment' => $this->commentModel->countAll(),
            'total_like' => $this->likeModel->countAll(),
        ];

        return view('admin/dashboard', $data);
    }

    public function posts()
    {
        $posts = $this->postModel
            ->select('posts.*, users.username')
            ->join('users', 'users.id = posts.user_id')
            ->orderBy('posts.id', 'DESC')
            ->findAll();

        return view('admin/posts', ['posts' => $posts]);
    }

    public function deletePost($id)
    {
        $this->postModel->delete($id);
        return redirect()->back();
    }
}
<?php

namespace App\Controllers;

use App\Models\PostModel;
use App\Models\LikeModel;
use App\Models\CommentModel;

class Post extends BaseController
{
    protected $postModel;
    protected $likeModel;
    protected $commentModel;

    public function __construct()
    {
        helper('auth');

        $this->postModel = new PostModel();
        $this->likeModel = new LikeModel();
        $this->commentModel = new CommentModel();
    }

    // ===============================
    // HALAMAN UTAMA (FEED)
    // ===============================
    public function index()
    {
        $data['posts'] = $this->postModel
            ->select('posts.*, users.username, 
                COUNT(DISTINCT likes.id) as like_count, 
                COUNT(DISTINCT comments.id) as comment_count')
            ->join('users', 'users.id = posts.user_id', 'left')
            ->join('likes', 'likes.post_id = posts.id', 'left')
            ->join('comments', 'comments.post_id = posts.id', 'left')
            ->where('posts.status', 'published')
            ->groupBy('posts.id')
            ->orderBy('posts.id', 'DESC')
            ->findAll();

        return view('posts/index', $data);
    }

    // ===============================
    // HALAMAN CREATE POST
    // ===============================
    public function createPage()
    {
        if (!logged_in()) {
            return redirect()->to('/login');
        }

        return view('posts/create');
    }

    // ===============================
    // SIMPAN POST
    // ===============================
    public function create()
    {
        if (!logged_in()) {
            return redirect()->to('/login');
        }

        $image = $this->request->getFile('image');
        $newName = null;

        if ($image && $image->isValid()) {
            $newName = $image->getRandomName();
            $image->move('uploads', $newName);
        }

        $this->postModel->save([
            'content' => $this->request->getPost('content'),
            'image'   => $newName,
            'status'  => $this->request->getPost('status'),
            'user_id' => user_id(),
        ]);

        // support AJAX
        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'success']);
        }

        return redirect()->to('/');
    }

    // ===============================
// HALAMAN DRAFT
// ===============================
public function drafts()
{
    if (!logged_in()) {
        return redirect()->to('/login');
    }

    $posts = $this->postModel
        ->where('user_id', user_id())
        ->where('status', 'draft')
        ->orderBy('id', 'DESC')
        ->findAll();

    return view('posts/drafts', [
        'posts' => $posts
    ]);
}

// ===============================
// HALAMAN EDIT DRAFT
// ===============================
public function edit($id)
{
    if (!logged_in()) {
        return redirect()->to('/login');
    }

    $post = $this->postModel->find($id);

    // 🔒 hanya pemilik post
    if (!$post || $post['user_id'] != user_id()) {
        return redirect()->to('/');
    }

    return view('posts/edit', [
        'post' => $post
    ]);
}

// ===============================
// UPDATE DRAFT
// ===============================
public function update($id)
{
    if (!logged_in()) {
        return redirect()->to('/login');
    }

    $post = $this->postModel->find($id);

    // 🔒 hanya pemilik post
    if (!$post || $post['user_id'] != user_id()) {
        return redirect()->to('/');
    }

    $image = $this->request->getFile('image');

    // gunakan gambar lama jika tidak upload baru
    $newName = $post['image'];

    // upload gambar baru
    if ($image && $image->isValid() && !$image->hasMoved()) {

        // hapus gambar lama
        if (!empty($post['image'])) {

            $oldPath = FCPATH . 'uploads/' . $post['image'];

            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }

        // simpan gambar baru
        $newName = $image->getRandomName();
        $image->move('uploads', $newName);
    }

    // update database
    $this->postModel->update($id, [
        'content' => $this->request->getPost('content'),
        'status'  => $this->request->getPost('status'),
        'image'   => $newName
    ]);

    return redirect()->to('/post/drafts');
}

// ===============================
// PUBLISH DRAFT
// ===============================
public function publish($id)
{
    if (!logged_in()) {
        return redirect()->to('/login');
    }

    $post = $this->postModel->find($id);

    // 🔒 hanya pemilik
    if (!$post || $post['user_id'] != user_id()) {
        return redirect()->to('/');
    }

    $this->postModel->update($id, [
        'status' => 'published'
    ]);

    return redirect()->to('/');
}

// ===============================
// UNPUBLISH (PUBLISHED -> DRAFT)
// ===============================
public function unpublish($id)
{
    if (!logged_in()) {
        return redirect()->to('/login');
    }

    $post = $this->postModel->find($id);

    // 🔒 hanya pemilik post
    if (!$post || $post['user_id'] != user_id()) {
        return redirect()->to('/');
    }

    // ubah status menjadi draft
    $this->postModel->update($id, [
        'status' => 'draft'
    ]);

    return redirect()->back();
}

// ===============================
// HAPUS DRAFT / POST
// ===============================
public function delete($id)
{
    if (!logged_in()) {
        return redirect()->to('/login');
    }

    $post = $this->postModel->find($id);

    // 🔒 hanya pemilik
    if (!$post || $post['user_id'] != user_id()) {
        return redirect()->to('/');
    }

    // hapus gambar jika ada
    if (!empty($post['image'])) {

        $path = FCPATH . 'uploads/' . $post['image'];

        if (file_exists($path)) {
            unlink($path);
        }
    }

    // hapus post
    $this->postModel->delete($id);

    return redirect()->back();
}

    // ===============================
    // LIKE / UNLIKE (TOGGLE + AJAX READY)
    // ===============================
    public function like($id)
    {
        if (!logged_in()) {
            return $this->response->setJSON(['error' => 'login']);
        }

        $check = $this->likeModel
            ->where('post_id', $id)
            ->where('user_id', user_id())
            ->first();

        if ($check) {
            $this->likeModel->delete($check['id']);
            $isLiked = false;
        } else {
            $this->likeModel->save([
                'post_id' => $id,
                'user_id' => user_id()
            ]);
            $isLiked = true;
        }

        $count = $this->likeModel
            ->where('post_id', $id)
            ->countAllResults();

        return $this->response->setJSON([
            'like_count' => $count,
            'is_liked' => $isLiked
        ]);
    }

    // ===============================
    // DETAIL POST + KOMENTAR
    // ===============================
    public function detail($id)
    {
        // 🔥 ambil data post + jumlah like
        $post = $this->postModel
            ->select('posts.*, users.username, COUNT(DISTINCT likes.id) as like_count')
            ->join('users', 'users.id = posts.user_id', 'left')
            ->join('likes', 'likes.post_id = posts.id', 'left')
            ->where('posts.id', $id)
            ->groupBy('posts.id')
            ->first();

        if (!$post) {
            return $this->response->setJSON([
                'error' => 'Post tidak ditemukan'
            ]);
        }

        // 🔥 cek apakah user sudah like
        $isLiked = false;

        if (logged_in()) {
            $check = $this->likeModel
                ->where('post_id', $id)
                ->where('user_id', user_id())
                ->first();

            $isLiked = $check ? true : false;
        }

        // 🔥 ambil komentar
        $comments = $this->commentModel
            ->select('comments.*, users.username')
            ->join('users', 'users.id = comments.user_id', 'left')
            ->where('comments.post_id', $id)
            ->orderBy('comments.id', 'ASC')
            ->findAll();

        // 🔥 response JSON FINAL
        return $this->response->setJSON([
            'id'         => $post['id'],
            'content'    => $post['content'],
            'image'      => $post['image'],
            'username'   => $post['username'],
            'like_count' => (int) $post['like_count'],
            'is_liked'   => $isLiked,
            'comments'   => $comments
        ]);
    }

    // ===============================
    // TAMBAH KOMENTAR
    // ===============================
    public function comment($id)
    {
        if (!logged_in()) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Harus login'
            ]);
        }

        $comment = trim($this->request->getPost('comment'));

        if ($comment == '') {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Komentar kosong'
            ]);
        }

        // 🔥 SIMPAN KE DATABASE
        $this->commentModel->save([
            'post_id' => $id,
            'user_id' => user_id(),
            'comment' => $comment
        ]);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Komentar berhasil'
        ]);
    }

    // ===============================
    // HAPUS KOMENTAR (OWNER ONLY)
    // ===============================
    public function deleteComment($id)
    {
        if (!logged_in()) {
            return redirect()->to('/login');
        }

        $comment = $this->commentModel->find($id);

        if ($comment && $comment['user_id'] == user_id()) {
            $this->commentModel->delete($id);
        }

        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'deleted']);
        }

        return redirect()->back();
    }

    // ===============================
    // LOAD MORE POST (INFINITE SCROLL)
    // ===============================
    public function loadMore($offset = 0)
    {
        $posts = $this->postModel
            ->orderBy('id', 'DESC')
            ->findAll(5, $offset);

        return view('posts/partials/post_items', ['posts' => $posts]);
    }
}
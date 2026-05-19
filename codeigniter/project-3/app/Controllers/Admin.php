<?php

namespace App\Controllers;

use App\Models\PostModel;

class Admin extends BaseController
{
    protected $postModel;

    public function __construct()
    {
        $this->postModel = new PostModel();
        helper(['url', 'text']);
    }

    // DASHBOARD ADMIN
    public function index()
    {
        $data = [
            'posts' => $this->postModel->orderBy('id', 'DESC')->findAll()
        ];

        return view('admin/index', $data);
    }

    // FORM CREATE
    public function create()
    {
        return view('admin/create');
    }

    // ✅ INSERT DATA + UPLOAD IMAGE (FINAL)
    public function store()
    {
        $file = $this->request->getFile('image');
        $namaFile = null;

        // Upload jika ada file
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $namaFile = $file->getRandomName();
            $file->move(FCPATH . 'uploads', $namaFile);
        }

        $data = [
            'title' => $this->request->getPost('title'),
            'slug' => url_title($this->request->getPost('title'), '-', true),
            'content' => $this->request->getPost('content'),
            'image' => $namaFile,
            'created_at' => date('Y-m-d H:i:s')
        ];

        // Debug kalau masih error
        // dd($data);

        $this->postModel->insert($data);

        return redirect()->to('/admin');
    }

    // FORM EDIT
    public function edit($id)
    {
        $post = $this->postModel->find($id);

        if (!$post) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Data tidak ditemukan");
        }

        return view('admin/edit', ['post' => $post]);
    }

    // ✅ UPDATE DATA + UPLOAD BARU (FINAL)
    public function update($id)
    {
        $file = $this->request->getFile('image');
        $post = $this->postModel->find($id);

        $namaFile = $post['image']; // default pakai lama

        if ($file && $file->isValid() && !$file->hasMoved()) {

            // hapus file lama (opsional tapi bagus)
            if ($post['image'] && file_exists(FCPATH . 'uploads/' . $post['image'])) {
                unlink(FCPATH . 'uploads/' . $post['image']);
            }

            $namaFile = $file->getRandomName();
            $file->move(FCPATH . 'uploads', $namaFile);
        }

        $this->postModel->update($id, [
            'title' => $this->request->getPost('title'),
            'slug' => url_title($this->request->getPost('title'), '-', true),
            'content' => $this->request->getPost('content'),
            'image' => $namaFile,
        ]);

        return redirect()->to('/admin');
    }

    // DELETE DATA
    public function delete($id)
    {
        $post = $this->postModel->find($id);

        // hapus file gambar juga
        if ($post && $post['image'] && file_exists(FCPATH . 'uploads/' . $post['image'])) {
            unlink(FCPATH . 'uploads/' . $post['image']);
        }

        $this->postModel->delete($id);

        return redirect()->to('/admin');
    }
}
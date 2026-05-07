<?= view('layout/header'); ?>

<div class="d-flex justify-content-between align-items-center mb-4">

    <h4>Draft Saya</h4>

    <a href="/post/create-page" class="btn btn-primary">
        + Buat Post
    </a>

</div>

<?php if(empty($posts)): ?>

    <div class="alert alert-info">
        Belum ada draft.
    </div>

<?php endif; ?>

<?php foreach($posts as $post): ?>

<div class="card mb-3">

    <div class="card-body">

        <p><?= esc($post['content']); ?></p>

        <?php if(!empty($post['image'])): ?>

            <img src="/uploads/<?= esc($post['image']); ?>"
                 class="img-fluid rounded mb-3"
                 style="max-height:300px;">

        <?php endif; ?>

        <div class="d-flex gap-2">

            <a href="/post/edit/<?= $post['id']; ?>"
               class="btn btn-warning btn-sm">
                Edit
            </a>

            <a href="/post/publish/<?= $post['id']; ?>"
               class="btn btn-success btn-sm">
                Publish
            </a>

            <a href="/post/delete/<?= $post['id']; ?>"
               class="btn btn-danger btn-sm"
               onclick="return confirm('Hapus draft?')">
                Hapus
            </a>

        </div>

    </div>

</div>

<?php endforeach; ?>

<?= view('layout/footer'); ?>
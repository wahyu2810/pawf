<?= view('layout/header'); ?>

<h3 class="mb-4">Edit Post</h3>

<div class="card shadow-sm">
    <div class="card-body">

        <form action="/admin/update/<?= $post['id']; ?>" method="post" enctype="multipart/form-data">
            
            <?= csrf_field(); ?>

            <!-- TITLE -->
            <div class="mb-3">
                <label class="form-label">Judul</label>
                <input 
                    type="text" 
                    name="title" 
                    class="form-control" 
                    value="<?= esc($post['title']); ?>" 
                    required>
            </div>

            <!-- CONTENT -->
            <div class="mb-3">
                <label class="form-label">Konten</label>
                <textarea 
                    name="content" 
                    class="form-control" 
                    rows="5" 
                    required><?= esc($post['content']); ?></textarea>
            </div>

            <!-- IMAGE -->
            <div class="mb-3">
                <label class="form-label">Gambar Saat Ini</label><br>

                <?php if ($post['image']): ?>
                    <img src="/uploads/<?= esc($post['image']); ?>" 
                         width="120" 
                         class="mb-2 rounded">
                <?php else: ?>
                    <p class="text-muted">Tidak ada gambar</p>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label class="form-label">Ganti Gambar (Opsional)</label>
                <input type="file" name="image" class="form-control">
            </div>

            <!-- BUTTON -->
            <div class="d-flex justify-content-between">
                <a href="/admin" class="btn btn-secondary">← Kembali</a>
                <button type="submit" class="btn btn-success">Update</button>
            </div>

        </form>

    </div>
</div>

<?= view('layout/footer'); ?>
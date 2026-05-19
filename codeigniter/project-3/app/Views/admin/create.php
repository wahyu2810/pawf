<?= view('layout/header'); ?>

<h3 class="mb-4">Tambah Post</h3>

<div class="card shadow-sm">
    <div class="card-body">

        <!-- FORM -->
        <form action="/admin/store" method="post" enctype="multipart/form-data">

            <!-- ✅ CSRF (WAJIB di CI4) -->
            <?= csrf_field(); ?>

            <!-- TITLE -->
            <div class="mb-3">
                <label class="form-label">Judul</label>
                <input 
                    type="text" 
                    name="title" 
                    class="form-control" 
                    placeholder="Masukkan judul"
                    required>
            </div>

            <!-- CONTENT -->
            <div class="mb-3">
                <label class="form-label">Konten</label>
                <textarea 
                    name="content" 
                    class="form-control" 
                    rows="5" 
                    placeholder="Masukkan konten"
                    required></textarea>
            </div>

            <!-- IMAGE UPLOAD -->
            <div class="mb-3">
                <label class="form-label">Upload Gambar</label>
                <input 
                    type="file" 
                    name="image" 
                    class="form-control" 
                    accept="image/*">

                <small class="text-muted">
                    Format: JPG, PNG, JPEG (opsional)
                </small>
            </div>

            <!-- PREVIEW IMAGE (OPSIONAL BONUS) -->
            <div class="mb-3">
                <img id="preview" src="#" class="img-fluid rounded d-none" style="max-height:200px;">
            </div>

            <!-- BUTTON -->
            <div class="d-flex justify-content-between">
                <a href="/admin" class="btn btn-secondary">← Kembali</a>
                <button type="submit" class="btn btn-success">Simpan</button>
            </div>

        </form>

    </div>
</div>

<!-- ✅ SCRIPT PREVIEW IMAGE -->
<script>
document.querySelector('input[name="image"]').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('preview');

    if (file) {
        preview.src = URL.createObjectURL(file);
        preview.classList.remove('d-none');
    }
});
</script>

<?= view('layout/footer'); ?>
<?= view('layout/header'); ?>

<div class="card">
    <div class="card-body">

        <h5>Buat Postingan</h5>

        <form action="/post/create" method="post" enctype="multipart/form-data">

            <textarea name="content" class="form-control mb-3" placeholder="Apa yang kamu pikirkan?"></textarea>

            <input type="file" name="image" class="form-control mb-3">

            <select name="status" class="form-control mb-3">
                <option value="published">Publish</option>
                <option value="draft">Draft</option>
            </select>

            <button class="btn btn-success">Simpan</button>
            <a href="/" class="btn btn-secondary">Kembali</a>

        </form>

    </div>
</div>

<?= view('layout/footer'); ?>
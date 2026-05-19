<?= view('layout/header'); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0">Admin Panel</h3>

    <?php if (logged_in()) : ?>

        <div class="d-flex align-items-center gap-2">
            <span class="text-muted">
                Halo, <?= user()->username; ?>
            </span>

            <a href="<?= base_url('logout'); ?>" 
               class="btn btn-outline-danger">
                Logout
            </a>
        </div>

    <?php else: ?>

        <div class="d-flex gap-2">
            <a href="<?= base_url('login'); ?>" 
               class="btn btn-outline-success">
                Login
            </a>

            <a href="<?= base_url('register'); ?>" 
               class="btn btn-success">
                Register
            </a>
        </div>

    <?php endif; ?>
</div>

<a href="/admin/create" class="btn btn-primary mb-3">
    + Tambah Post
</a>

<div class="table-responsive">
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark text-center">
            <tr>
                <th width="20%">Title</th>
                <th width="15%">Slug</th>
                <th width="25%">Content</th>
                <th width="15%">Image</th>
                <th width="15%">Created At</th>
                <th width="10%">Aksi</th>
            </tr>
        </thead>

        <tbody>
            <?php if (!empty($posts)): ?>

                <?php foreach ($posts as $post): ?>
                <tr>

                    <!-- TITLE -->
                    <td>
                        <?= esc($post['title']); ?>
                    </td>

                    <!-- SLUG -->
                    <td>
                        <?= esc($post['slug']); ?>
                    </td>

                    <!-- CONTENT -->
                    <td>
                        <?= esc(substr(strip_tags($post['content']), 0, 80)); ?>...
                    </td>

                    <!-- IMAGE -->
                    <td class="text-center">

                        <?php if (!empty($post['image'])): ?>

                            <img src="/uploads/<?= esc($post['image']); ?>"
                                 width="80"
                                 height="60"
                                 class="img-thumbnail"
                                 style="object-fit: cover; cursor: pointer;"
                                 data-bs-toggle="modal"
                                 data-bs-target="#imageModal"
                                 onclick="showImage('/uploads/<?= esc($post['image']); ?>')">

                        <?php else: ?>

                            <span class="text-muted">
                                No Image
                            </span>

                        <?php endif; ?>

                    </td>

                    <!-- CREATED AT -->
                    <td class="text-center">
                        <?= date('d M Y', strtotime($post['created_at'])); ?>
                    </td>

                    <!-- AKSI -->
                    <td class="text-center">

                        <a href="/admin/edit/<?= $post['id']; ?>"
                           class="btn btn-warning btn-sm mb-1">
                            Edit
                        </a>

                        <a href="/admin/delete/<?= $post['id']; ?>"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Yakin hapus data?')">
                            Hapus
                        </a>

                    </td>

                </tr>
                <?php endforeach; ?>

            <?php else: ?>

                <tr>
                    <td colspan="6" class="text-center text-muted">
                        Tidak ada data
                    </td>
                </tr>

            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- MODAL PREVIEW IMAGE -->
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    Preview Image
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>
            </div>

            <div class="modal-body text-center">
                <img id="previewImage"
                     src=""
                     class="img-fluid rounded">
            </div>

        </div>
    </div>
</div>

<!-- SCRIPT -->
<script>
function showImage(src) {
    document.getElementById('previewImage').src = src;
}
</script>

<?= view('layout/footer'); ?>
<?= view('layout/header'); ?>

<h4>Kelola Post</h4>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>User</th>
            <th>Konten</th>
            <th>Aksi</th>
        </tr>
    </thead>

    <tbody>
    <?php foreach($posts as $p): ?>
        <tr>
            <td><?= esc($p['username']); ?></td>
            <td><?= esc($p['content']); ?></td>
            <td>
                <a href="/admin/delete-post/<?= $p['id']; ?>" 
                   class="btn btn-danger btn-sm">
                   Hapus
                </a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?= view('layout/footer'); ?>
<?= view('layout/header'); ?>

<h4>Admin Dashboard</h4>

<div class="row">

    <div class="col-md-4">
        <div class="card p-3 text-center">
            <h5>Total Post</h5>
            <h2><?= $total_post; ?></h2>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card p-3 text-center">
            <h5>Total Komentar</h5>
            <h2><?= $total_comment; ?></h2>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card p-3 text-center">
            <h5>Total Like</h5>
            <h2><?= $total_like; ?></h2>
        </div>
    </div>

</div>

<a href="/admin/posts" class="btn btn-dark mt-3">Kelola Post</a>

<?= view('layout/footer'); ?>
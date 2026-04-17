<!DOCTYPE html>
<html>
<head>
    <title><?= $post['title']; ?></title>
    <link href="/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand">Blog Wahyu</a>
    </div>
</nav>

<div class="container mt-5">

    <h2><?= $post['title']; ?></h2>

    <p class="text-muted">
        BY ADMIN | <?= date('d M Y', strtotime($post['created_at'])); ?>
    </p>

    <img src="/uploads/<?= $post['image']; ?>" class="img-fluid mb-3">

    <p><?= $post['content']; ?></p>

    <a href="/" class="btn btn-secondary mt-3">← Kembali</a>

</div>

</body>
</html>
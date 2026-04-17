<!DOCTYPE html>
<html>
<head>
    <title>Blog Wahyu</title>

    <link href="/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f5f7fa;
            font-family: 'Segoe UI', sans-serif;
        }

        .navbar {
            background: #000;
        }

        .navbar a {
            color: white !important;
            letter-spacing: 2px;
        }

        .hero {
            background: url('/assets/img/header.jpg') center/cover;
            height: 200px;
        }

        .card-post {
            border: none;
            transition: 0.3s;
        }

        .card-post:hover {
            transform: translateY(-5px);
        }

        .post-title {
            font-weight: 600;
            font-size: 14px;
            letter-spacing: 1px;
        }

        .meta {
            font-size: 12px;
            color: gray;
        }
    </style>

</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand text-white" href="#">Blog Wahyu</a>
    </div>
</nav>

<!-- HERO -->
<div class="hero"></div>

<!-- CONTENT -->
<div class="container mt-5">
    <div class="row">

        <?php foreach($posts as $p): ?>
        <div class="col-md-4 mb-4">
            <div class="card card-post">
                <img src="/uploads/<?= $p['image']; ?>" class="card-img-top">

                <div class="card-body">
                    <h6 class="post-title"><?= strtoupper($p['title']); ?></h6>

                    <p class="meta">
                        BY ADMIN | <?= date('d M Y', strtotime($p['created_at'])); ?>
                    </p>

                    <p>Text...</p>

                    <a href="/post/<?= $p['slug']; ?>" class="text-decoration-none">
                        Read more »
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>

    </div>
</div>

<script src="/assets/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>
</html>
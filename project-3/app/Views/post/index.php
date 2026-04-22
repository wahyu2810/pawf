<?= view('layout/header'); ?>

<!-- HERO -->
<div class="hero mb-4"></div>

<div class="container">

    <h4 class="mb-4">Latest Posts</h4>

    <div class="row">

        <?php foreach($posts as $p): ?>
        <div class="col-md-4 mb-4">
            <div class="card card-post shadow-sm h-100">

                <img src="/uploads/<?= $p['image']; ?>" class="card-img-top" style="height:200px; object-fit:cover;">

                <div class="card-body d-flex flex-column">

                    <h6 class="post-title"><?= strtoupper($p['title']); ?></h6>

                    <p class="meta">
                        BY ADMIN | <?= date('d M Y', strtotime($p['created_at'])); ?>
                    </p>

                    <p class="flex-grow-1">
                        <?= substr(strip_tags($p['content']), 0, 100); ?>...
                    </p>

                    <a href="/post/<?= $p['slug']; ?>" class="btn btn-outline-dark btn-sm mt-auto">
                        Read More
                    </a>

                </div>
            </div>
        </div>
        <?php endforeach; ?>

    </div>
</div>

<?= view('layout/footer'); ?>
<?php foreach($posts as $post): ?>

<div class="card mb-4 shadow-sm" style="max-width:600px; margin:auto;">

    <!-- HEADER -->
    <div class="card-header bg-white d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">

            <!-- FOTO PROFILE (dummy) -->
            <img src="https://i.pravatar.cc/40?u=<?= $post['user_id']; ?>"
                 class="rounded-circle"
                 width="40" height="40">

            <div>
                <strong><?= esc($post['username'] ?? 'User'); ?></strong><br>
                <small class="text-muted">
                    <?= date('d M Y', strtotime($post['created_at'] ?? 'now')); ?>
                </small>
            </div>

        </div>

        <span>⋮</span>
    </div>

    <!-- IMAGE -->
    <?php if(!empty($post['image'])): ?>
        <img src="/uploads/<?= esc($post['image']); ?>" 
             class="img-fluid"
             style="max-height:500px; object-fit:cover;">
    <?php endif; ?>

    <!-- BODY -->
    <div class="card-body pt-2">

        <!-- ACTION -->
        <div class="d-flex gap-3 mb-2">

            <a href="#" class="like-btn" data-id="<?= $post['id']; ?>" style="text-decoration:none;">
                <span id="like-icon-<?= $post['id']; ?>" class="<?= ($post['is_liked'] ?? false) ? 'text-danger' : ''; ?>">
                    ❤️
                </span>
                <span id="like-count-<?= $post['id']; ?>">
                    <?= $post['like_count'] ?? 0; ?>
                </span>
            </a>

            <a href="/post/detail/<?= $post['id']; ?>" style="text-decoration:none;">
                💬 <?= $post['comment_count'] ?? 0; ?>
            </a>

        </div>

        <!-- CAPTION -->
        <p class="mb-1">
            <strong><?= esc($post['username'] ?? 'User'); ?></strong>
            <?= esc($post['content']); ?>
        </p>

        <!-- LINK KOMENTAR -->
        <a href="/post/detail/<?= $post['id']; ?>" class="text-muted" style="text-decoration:none;">
            Lihat semua komentar
        </a>

    </div>

</div>

<?php endforeach; ?>
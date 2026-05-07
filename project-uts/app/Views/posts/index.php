<?= view('layout/header'); ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Feed</h4>

    <?php if(logged_in()): ?>
        <a href="/post/create-page" class="btn btn-primary btn-sm">+ Buat Post</a>
    <?php endif; ?>
</div>

<!-- POST CONTAINER -->
<div id="post-container">

<?php foreach($posts as $post): ?>

<div class="card mb-4 shadow-sm" style="max-width: 600px; margin:auto;">

    <!-- HEADER -->
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
            <img src="https://i.pravatar.cc/40?u=<?= $post['user_id']; ?>"
                 class="rounded-circle"
                 width="40" height="40">

            <div>
                <strong><?= esc($post['username']); ?></strong><br>
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

            <!-- LIKE -->
            <a href="#" 
               class="like-btn" 
               data-id="<?= $post['id']; ?>">

                <span id="like-icon-<?= $post['id']; ?>"
                      class="like-icon <?= ($post['is_liked'] ?? false) ? 'text-danger' : ''; ?>">
                    ❤️
                </span>

                <span id="like-count-<?= $post['id']; ?>">
                    <?= $post['like_count']; ?>
                </span>
            </a>

            <!-- COMMENT -->
            <a href="#" class="open-comment" data-id="<?= $post['id']; ?>">
                💬
            </a>

        </div>

        <strong><?= $post['like_count']; ?> suka</strong>

        <strong><?= $post['like_count']; ?> suka</strong>

<!-- ========================= -->
<!-- 🔥 OWNER ACTION -->
<!-- ========================= -->
<?php if(logged_in() && $post['user_id'] == user_id()): ?>

    <div class="mt-2 d-flex gap-2 flex-wrap">

        <!-- EDIT -->
        <a href="/post/edit/<?= $post['id']; ?>" 
           class="btn btn-outline-warning btn-sm">
            Edit
        </a>

        <!-- STATUS -->
        <?php if($post['status'] == 'published'): ?>

            <a href="/post/unpublish/<?= $post['id']; ?>" 
               class="btn btn-outline-secondary btn-sm">
                Jadikan Draft
            </a>

        <?php else: ?>

            <a href="/post/publish/<?= $post['id']; ?>" 
               class="btn btn-outline-success btn-sm">
                Publish
            </a>

        <?php endif; ?>

        <!-- DELETE -->
        <a href="/post/delete/<?= $post['id']; ?>" 
           class="btn btn-outline-danger btn-sm"
           onclick="return confirm('Yakin ingin menghapus post ini?')">
            Hapus
        </a>

    </div>

<?php endif; ?>

        <p class="mb-1 mt-1">
            <strong><?= esc($post['username']); ?></strong>
            <?= esc($post['content']); ?>
        </p>

        <a href="#" class="text-muted open-comment" data-id="<?= $post['id']; ?>">
            Lihat semua <?= $post['comment_count']; ?> komentar
        </a>

    </div>

</div>

<?php endforeach; ?>

</div>

<!-- ========================= -->
<!-- 🔥 MODAL KOMENTAR -->
<!-- ========================= -->
<div id="commentModal" class="d-none">

    <div class="overlay"></div>

    <div class="modal-box">

        <button id="closeModal" class="btn btn-danger btn-sm float-end">X</button>

        <h6 id="modal-username"></h6>

        <img id="modal-image" class="img-fluid mb-3">

        <div id="modal-comments"></div>

        <form id="modal-comment-form" class="mt-3">
            <?= csrf_field(); ?>
            <input type="hidden" id="modal-post-id">

            <div class="input-group">
                <input type="text" name="comment" class="form-control" placeholder="Tulis komentar..." required>
                <button class="btn btn-dark btn-sm">Kirim</button>
            </div>
        </form>

    </div>

</div>

<!-- ========================= -->
<!-- 🔥 AJAX LIKE -->
<!-- ========================= -->
<script>
$(document).on('click', '.like-btn', function(e){
    e.preventDefault();

    let postId = $(this).data('id');
    let icon = $('#like-icon-' + postId);
    let count = $('#like-count-' + postId);

    $.get('/post/like/' + postId, function(res){

        count.text(res.like_count);

        if(res.is_liked){
            icon.addClass('text-danger');
        } else {
            icon.removeClass('text-danger');
        }

        icon.css('transform','scale(1.3)');
        setTimeout(()=> icon.css('transform','scale(1)'),150);

    });
});
</script>

<!-- ========================= -->
<!-- 🔥 LOAD MODAL KOMENTAR -->
<!-- ========================= -->
<script>
$(document).on('click', '.open-comment', function(e){
    e.preventDefault();

    let postId = $(this).data('id');

    $.get('/post/detail/' + postId, function(res){

        $('#modal-username').text(res.username);
        $('#modal-image').attr('src', '/uploads/' + res.image);
        $('#modal-post-id').val(postId);

        let html = '';

        res.comments.forEach(c => {
            html += `
                <div class="mb-2 border-bottom pb-1">
                    <strong>${c.username}</strong>
                    <div>${c.comment}</div>
                </div>
            `;
        });

        $('#modal-comments').html(html);
        $('#commentModal').removeClass('d-none');

    });
});

$(document).on('click', '#closeModal', function(){
    $('#commentModal').addClass('d-none');
});
</script>

<!-- ========================= -->
<!-- 🔥 AJAX KOMENTAR -->
<!-- ========================= -->
<script>
$(document).on('submit', '#modal-comment-form', function(e){
    e.preventDefault();

    let postId = $('#modal-post-id').val();
    let input = $(this).find('input[name="comment"]');
    let text = input.val();

    $.ajax({
        url: '/post/comment/' + postId,
        type: 'POST',
        data: {
            comment: text,
            '<?= csrf_token(); ?>': '<?= csrf_hash(); ?>'
        },
        success: function(){

            $('#modal-comments').append(`
                <div class="mb-2 border-bottom pb-1">
                    <strong>Anda</strong>
                    <div>${text}</div>
                </div>
            `);

            input.val('');
        }
    });
});
</script>

<!-- ========================= -->
<!-- 🔥 INFINITE SCROLL -->
<!-- ========================= -->
<script>
let offset = <?= count($posts); ?>;
let loading = false;

$(window).scroll(function(){

    if(loading) return;

    if($(window).scrollTop() + $(window).height() >= $(document).height() - 100){

        loading = true;

        $.get('/post/load-more/' + offset, function(data){

            if(data.trim() !== ''){
                $('#post-container').append(data);
                offset += 5;
                loading = false;
            }

        });

    }

});
</script>

<!-- ========================= -->
<!-- STYLE FINAL -->
<!-- ========================= -->
<style>
.like-icon {
    transition: 0.2s;
}

#commentModal {
    z-index: 9999;
}

.overlay {
    position: fixed;
    width:100%;
    height:100%;
    background: rgba(0,0,0,0.7);
    top:0;
    left:0;
}

.modal-box {
    position: fixed;
    top:50%;
    left:50%;
    transform: translate(-50%, -50%);
    background:white;
    padding:20px;
    border-radius:12px;
    width:90%;
    max-width:700px;
    max-height:90%;
    overflow:auto;
}
</style>

<?= view('layout/footer'); ?>
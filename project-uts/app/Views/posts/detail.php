<?= view('layout/header'); ?>

<div class="card mb-4">
    <div class="card-body">

        <!-- ERROR MESSAGE -->
        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('error'); ?>
            </div>
        <?php endif; ?>

        <!-- POST -->
        <h6><?= esc($post['username']); ?></h6>
        <p><?= esc($post['content']); ?></p>

        <?php if(!empty($post['image'])): ?>
            <img src="/uploads/<?= esc($post['image']); ?>" class="img-fluid mb-3">
        <?php endif; ?>

        <!-- LIKE -->
        <a href="#" 
           class="btn btn-outline-primary btn-sm like-btn" 
           data-id="<?= $post['id']; ?>">

            <span id="like-icon-<?= $post['id']; ?>" 
                  class="<?= ($post['is_liked'] ?? false) ? 'text-danger' : ''; ?>">
                ❤️
            </span>

            Like (<span id="like-count-<?= $post['id']; ?>">
                <?= $post['like_count'] ?? 0; ?>
            </span>)
        </a>

        <hr>

        <!-- KOMENTAR -->
        <h6>Komentar</h6>

        <div id="comment-list">
        <?php foreach($comments as $c): ?>
            <div class="mb-2 d-flex justify-content-between align-items-start comment-item">
                <div>
                    <strong><?= esc($c['username']); ?></strong>
                    <p class="mb-0"><?= esc($c['comment']); ?></p>
                </div>

                <?php if(logged_in() && $c['user_id'] == user_id()): ?>
                    <a href="/post/comment/delete/<?= $c['id']; ?>" 
                       class="btn btn-sm btn-danger">
                        Hapus
                    </a>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
        </div>

        <!-- FORM KOMENTAR -->
        <form class="comment-form mt-3" data-id="<?= $post['id']; ?>">
            <?= csrf_field(); ?>

            <div class="input-group">
                <input type="text" 
                       name="comment" 
                       class="form-control" 
                       placeholder="Tulis komentar..." 
                       required>

                <button class="btn btn-dark" type="submit">
                    Kirim
                </button>
            </div>
        </form>

    </div>
</div>

<!-- ===================== -->
<!-- 🔥 AJAX LIKE -->
<!-- ===================== -->
<script>
$(document).on('click', '.like-btn', function(e){
    e.preventDefault();

    let btn = $(this);
    let postId = btn.data('id');

    btn.css('pointer-events', 'none');

    $.get('/post/like/' + postId, function(res){

        $('#like-count-' + postId).text(res.like_count);

        if(res.is_liked){
            $('#like-icon-' + postId).addClass('text-danger');
        } else {
            $('#like-icon-' + postId).removeClass('text-danger');
        }

    }).always(function(){
        btn.css('pointer-events', 'auto');
    });
});
</script>

<!-- ===================== -->
<!-- 🔥 AJAX KOMENTAR (REALTIME TANPA RELOAD) -->
<!-- ===================== -->
<script>
$(document).on('submit', '.comment-form', function(e){
    e.preventDefault();

    let form = $(this);
    let postId = form.data('id');
    let input = form.find('input[name="comment"]');
    let btn = form.find('button');

    let commentText = input.val();

    if(commentText.trim() === ''){
        return;
    }

    btn.prop('disabled', true).text('Mengirim...');

    $.ajax({
        url: '/post/comment/' + postId,
        type: 'POST',
        data: {
            comment: commentText,
            '<?= csrf_token(); ?>': '<?= csrf_hash(); ?>'
        },
        success: function(res){

            // 🔥 Tambahkan komentar langsung ke UI (tanpa reload)
            $('#comment-list').append(`
                <div class="mb-2">
                    <strong>Anda</strong>
                    <p class="mb-0">${commentText}</p>
                </div>
            `);

            input.val('');

        },
        error: function(){
            alert('Gagal komentar');
        },
        complete: function(){
            btn.prop('disabled', false).text('Kirim');
        }
    });
});
</script>

<?= view('layout/footer'); ?>
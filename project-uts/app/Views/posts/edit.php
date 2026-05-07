<?= view('layout/header'); ?>

<div class="card">

    <div class="card-body">

        <h4>Edit Draft</h4>

        <form action="/post/update/<?= $post['id']; ?>"
              method="post"
              enctype="multipart/form-data">

            <textarea name="content"
                      class="form-control mb-3"
                      rows="5"><?= esc($post['content']); ?></textarea>

            <?php if(!empty($post['image'])): ?>

                <img src="/uploads/<?= esc($post['image']); ?>"
                     class="img-fluid rounded mb-3"
                     style="max-height:300px;">

            <?php endif; ?>

            <input type="file"
                   name="image"
                   class="form-control mb-3">

            <select name="status" class="form-control mb-3">

                <option value="draft"
                    <?= $post['status'] == 'draft' ? 'selected' : ''; ?>>
                    Draft
                </option>

                <option value="published"
                    <?= $post['status'] == 'published' ? 'selected' : ''; ?>>
                    Publish
                </option>

            </select>

            <button class="btn btn-success">
                Update
            </button>

            <a href="/post/drafts"
               class="btn btn-secondary">
                Kembali
            </a>

        </form>

    </div>

</div>

<?= view('layout/footer'); ?>
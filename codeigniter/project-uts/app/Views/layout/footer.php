<!-- MODAL KOMENTAR -->
<div id="commentModal" class="comment-modal d-none">

    <div class="comment-overlay"></div>

    <div class="comment-box">

        <!-- KIRI (GAMBAR) -->
        <div class="comment-left">
            <img id="modal-image" src="" class="img-fluid">
        </div>

        <!-- KANAN -->
        <div class="comment-right">

            <div class="comment-header">
                <strong id="modal-username"></strong>
                <span id="closeModal" style="cursor:pointer;">✖</span>
            </div>

            <div id="modal-comments" class="comment-list"></div>

            <form id="modalCommentForm">
                <input type="hidden" id="modal-post-id">

                <div class="d-flex mt-2">
                    <input type="text" id="modal-comment-input" 
                           class="form-control" 
                           placeholder="Tambahkan komentar...">
                    <button class="btn btn-primary">Kirim</button>
                </div>
            </form>

        </div>

    </div>

</div>

</body>
</html>
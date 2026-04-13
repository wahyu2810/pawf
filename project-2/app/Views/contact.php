<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="container">
    <h2 class="text-center mb-4">Contact Me</h2>

    <form class="col-md-6 mx-auto" data-aos="fade-up">
        <input class="form-control mb-3" type="text" placeholder="Nama">
        <input class="form-control mb-3" type="email" placeholder="Email">
        <textarea class="form-control mb-3" rows="5" placeholder="Pesan"></textarea>
        <button class="btn btn-primary w-100">Kirim</button>
    </form>
</div>

<?= $this->endSection() ?>
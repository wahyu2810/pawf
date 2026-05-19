<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<section>
<div class="container">

    <!-- Title -->
    <div class="section-title" data-aos="fade-up">
        <h2>Portfolio</h2>
        <p>Beberapa project yang pernah saya kerjakan</p>
    </div>

    <!-- Grid -->
    <div class="row">

        <div class="col-md-4" data-aos="zoom-in">
            <div class="card shadow-sm">
                <img src="https://via.placeholder.com/400x250" class="card-img-top">
                <div class="card-body">
                    <h5>Sistem Tugas</h5>
                    <p class="text-muted">Laravel + MySQL</p>
                </div>
            </div>
        </div>

        <div class="col-md-4" data-aos="zoom-in">
            <div class="card shadow-sm">
                <img src="https://via.placeholder.com/400x250" class="card-img-top">
                <div class="card-body">
                    <h5>Absensi</h5>
                    <p class="text-muted">Laravel + MySQL</p>
                </div>
            </div>
        </div>

        <div class="col-md-4" data-aos="zoom-in">
            <div class="card shadow-sm">
                <img src="https://via.placeholder.com/400x250" class="card-img-top">
                <div class="card-body">
                    <h5>Sistem Pengelolaan Barang</h5>
                    <p class="text-muted">Laravel + MySQL</p>
                </div>
            </div>
        </div>

    </div>

</div>
</section>

<?= $this->endSection() ?>
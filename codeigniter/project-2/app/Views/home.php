<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<section class="hero-geeky d-flex align-items-center">
    <div class="container">
        <div class="row align-items-center">

            <!-- TEXT -->
            <div class="col-md-6 text-white" data-aos="fade-right">
                <h1 class="hero-title">
                    Welcome<span class="text-success">!</span>
                </h1>

                <h2 class="hero-subtitle">
                    Saya Wahyu
                </h2>

                <p class="hero-desc">
                    Web Developer.
                    Saya membangun aplikasi modern berbasis web yang scalable dan user-friendly.
                </p>

                <a href="/about" class="btn btn-success btn-lg mt-3">
                    Tentang saya
                </a>
            </div>

            <!-- IMAGE -->
            <div class="col-md-6 text-center" data-aos="fade-left">
                <img src="<?= base_url('img/profile.jpeg') ?>" 
                     class="hero-img"
                     alt="profile">
            </div>

        </div>
    </div>
</section>

<?= $this->endSection() ?>
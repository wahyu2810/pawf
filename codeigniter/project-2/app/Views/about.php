<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<!-- HERO IMAGE -->
<section class="about-hero">
    <div class="container text-center" data-aos="fade-up">
        <img src="<?= base_url('img/profile.jpeg') ?>" class="about-img" alt="profile">
    </div>
</section>

<!-- ABOUT TEXT -->
<section class="about-content">
    <div class="container">

        <h1 class="about-title" data-aos="fade-up">About Me</h1>

        <p class="about-text" data-aos="fade-up">
            Saya adalah mahasiswa Teknik Informatika yang memiliki minat besar dalam pengembangan web modern. 
            Saya berfokus pada pembuatan aplikasi yang scalable, efisien, dan user-friendly.
        </p>

        <p class="about-text" data-aos="fade-up">
            Saya memiliki pengalaman menggunakan CodeIgniter, Laravel, serta berbagai teknologi frontend modern. 
            Saya terus belajar untuk meningkatkan kemampuan dalam membangun sistem yang berkualitas tinggi.
        </p>

    </div>
</section>

<!-- EDUCATION & EXPERIENCE -->
<section class="about-box">
    <div class="container">
        <div class="row">

            <!-- EDUCATION -->
            <div class="col-md-6" data-aos="fade-right">
                <div class="box-card">

                    <h3>Formal Education</h3>

                    <div class="item">
                        <h5>Universitas Nahdlatul Ulama Indonesia</h5>
                        <p>2023 - Sekarang | Teknik Informatika</p>
                    </div>

                    <div class="item">
                        <h5>SMKN 2 Kota Depok</h5>
                        <p>2018 - 2021 | Teknik Komputer Jaringan</p>
                    </div>

                </div>
            </div>

            <!-- EXPERIENCE -->
            <div class="col-md-6" data-aos="fade-left">
                <div class="box-card">

                    <h3>Work Experience</h3>

                    <ul class="experience-list">
                        <li>Web Developer Project</li>
                        <li>Sistem Absensi Berbasis Web</li>
                        <li>Sistem Pengelolaan Barang</li>
                        <li>Portfolio Website</li>
                    </ul>

                </div>
            </div>

        </div>
    </div>
</section>

<?= $this->endSection() ?>
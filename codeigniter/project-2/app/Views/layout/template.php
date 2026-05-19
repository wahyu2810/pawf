<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Portfolio' ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- AOS -->
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
  <div class="container">
    
    <a class="navbar-brand fw-bold" href="/">Wahyu</a>

    <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav ms-auto">

        <li class="nav-item">
            <a class="nav-link <?= uri_string()=='' ? 'active':'' ?>" href="/">Home</a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= uri_string()=='about' ? 'active':'' ?>" href="/about">About</a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= uri_string()=='portfolio' ? 'active':'' ?>" href="/portfolio">Portfolio</a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= uri_string()=='contact' ? 'active':'' ?>" href="/contact">Contact</a>
        </li>

      </ul>
    </div>

  </div>
</nav>

<!-- CONTENT -->
<main class="main-content">
    <?= $this->renderSection('content') ?>
</main>

<!-- FOOTER -->
<footer class="footer">
    <p>© <?= date('Y') ?> Wahyu | Web Developer</p>
</footer>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>

<script>
    AOS.init({
        duration: 1000,
        once: true
    });
</script>

</body>
</html>
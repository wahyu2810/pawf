<!DOCTYPE html>
<html>
<head>
    <title>Social Mini</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <style>
    body {
        background: #fafafa;
    }

    .card {
        border-radius: 12px;
        border: 1px solid #ddd;
    }

    .navbar {
        border-bottom: 1px solid #ddd;
        position: sticky;
        top: 0;
        z-index: 999;
    }

    .like-icon {
        transition: 0.2s ease;
        display: inline-block;
        font-size: 18px;
    }
    </style>
</head>

<body>

<nav class="navbar navbar-dark bg-dark">
    <div class="container d-flex justify-content-between align-items-center">

        <!-- LOGO -->
        <a class="navbar-brand fw-bold" href="/">Social Mini</a>

        <!-- MENU KANAN -->
        <div>

            <?php if(logged_in()): ?>

                <!-- ADMIN BUTTON -->
                <?php if(in_groups('admin')): ?>
                    <a href="/admin" class="btn btn-warning btn-sm me-2">
                        Admin
                    </a>
                <?php endif; ?>

                <!-- USER INFO -->
                <span class="text-white me-3">
                    Halo, <strong><?= user()->username; ?></strong>
                </span>

                <a href="/post/drafts"
                    class="btn btn-outline-light btn-sm me-2">
                    Draft
                </a>

                <!-- LOGOUT -->
                <a href="/logout" class="btn btn-outline-light btn-sm">
                    Logout
                </a>

            <?php else: ?>

                <!-- LOGIN -->
                <a href="/login" class="btn btn-outline-light btn-sm me-2">
                    Login
                </a>

                <!-- REGISTER -->
                <a href="/register" class="btn btn-light btn-sm">
                    Register
                </a>

            <?php endif; ?>

        </div>

    </div>
</nav>

<div class="container mt-4">
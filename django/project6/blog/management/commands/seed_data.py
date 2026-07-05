"""Mengisi database dengan data contoh untuk Blog Wahyu.

Jalankan: python manage.py seed_data
"""

from django.contrib.auth.models import User
from django.core.management.base import BaseCommand

from blog.models import Category, Comment, Post


class Command(BaseCommand):
    help = "Mengisi database dengan kategori, postingan, dan komentar contoh."

    def handle(self, *args, **options):
        # --- Superuser ---
        if not User.objects.filter(username="admin").exists():
            User.objects.create_superuser(
                username="admin", email="admin@blogwahyu.id", password="admin12345"
            )
            self.stdout.write(
                self.style.SUCCESS("Superuser 'admin' dibuat (password: admin12345).")
            )
        admin = User.objects.get(username="admin")

        # --- Pengguna biasa ---
        wahyu, created = User.objects.get_or_create(
            username="wahyu", defaults={"email": "wahyu@blogwahyu.id"}
        )
        if created:
            wahyu.set_password("wahyu12345")
            wahyu.save()
            self.stdout.write(
                self.style.SUCCESS("Pengguna 'wahyu' dibuat (password: wahyu12345).")
            )

        # --- Kategori ---
        categories = {}
        for name in ["Teknologi", "Pemrograman", "Tutorial", "Cerita"]:
            cat, _ = Category.objects.get_or_create(name=name)
            categories[name] = cat
        self.stdout.write(self.style.SUCCESS(f"{len(categories)} kategori siap."))

        # --- Postingan ---
        posts_data = [
            {
                "title": "Selamat Datang di Blog Wahyu",
                "category": categories["Cerita"],
                "author": admin,
                "body": (
                    "Halo! Selamat datang di Blog Wahyu. Blog ini dibangun dengan "
                    "Django 6 sebagai project lanjutan dari project-1 hingga project-5.\n\n"
                    "Di sini Anda bisa membaca postingan, berkomentar, dan jika mendaftar, "
                    "menulis postingan Anda sendiri."
                ),
            },
            {
                "title": "Mengenal Class-Based Views di Django",
                "category": categories["Pemrograman"],
                "author": wahyu,
                "body": (
                    "Class-Based Views (CBV) membuat kode Django lebih ringkas. "
                    "ListView, DetailView, CreateView, UpdateView, dan DeleteView "
                    "menyediakan operasi CRUD lengkap dengan kode minimal.\n\n"
                    "Blog ini menggunakan kelima view tersebut untuk mengelola postingan."
                ),
            },
            {
                "title": "Tutorial: Membuat Sistem Komentar",
                "category": categories["Tutorial"],
                "author": wahyu,
                "body": (
                    "Sistem komentar dibuat dengan model Comment yang memiliki relasi "
                    "ForeignKey ke Post dan User. Form komentar hanya tampil bagi "
                    "pengguna yang sudah masuk.\n\n"
                    "Coba masuk dan tinggalkan komentar pada postingan ini!"
                ),
            },
            {
                "title": "Kekuatan Template Inheritance",
                "category": categories["Teknologi"],
                "author": admin,
                "body": (
                    "Dengan base.html dan blok {% block content %}, semua halaman "
                    "berbagi navbar, footer, dan gaya yang sama. Ini menerapkan "
                    "prinsip DRY (Don't Repeat Yourself)."
                ),
            },
            {
                "title": "Catatan Pengembangan (Draft)",
                "category": categories["Cerita"],
                "author": wahyu,
                "status": Post.Status.DRAFT,
                "body": "Ini adalah contoh postingan draft yang hanya terlihat oleh penulisnya.",
            },
        ]

        created_posts = []
        for data in posts_data:
            status = data.pop("status", Post.Status.PUBLISHED)
            post, was_created = Post.objects.get_or_create(
                title=data["title"],
                defaults={**data, "status": status},
            )
            if was_created:
                created_posts.append(post)
        self.stdout.write(
            self.style.SUCCESS(f"{len(created_posts)} postingan baru dibuat.")
        )

        # --- Komentar contoh ---
        published = Post.objects.filter(status=Post.Status.PUBLISHED).first()
        if published and not published.comments.exists():
            Comment.objects.create(
                post=published, author=wahyu, body="Postingan yang bagus, terima kasih!"
            )
            Comment.objects.create(
                post=published, author=admin, body="Sama-sama, semoga bermanfaat."
            )
            self.stdout.write(self.style.SUCCESS("Komentar contoh dibuat."))

        self.stdout.write(self.style.SUCCESS("\nSeed data selesai!"))

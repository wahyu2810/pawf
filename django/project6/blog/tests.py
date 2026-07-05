from django.contrib.auth.models import User
from django.test import TestCase
from django.urls import reverse

from .models import Category, Comment, Post


class BlogModelTests(TestCase):
    @classmethod
    def setUpTestData(cls):
        cls.user = User.objects.create_user(username="penulis", password="rahasia123")
        cls.category = Category.objects.create(name="Teknologi")
        cls.post = Post.objects.create(
            title="Postingan Pertama",
            author=cls.user,
            category=cls.category,
            body="Ini isi postingan pertama.",
            status=Post.Status.PUBLISHED,
        )

    def test_post_str(self):
        self.assertEqual(str(self.post), "Postingan Pertama")

    def test_slug_otomatis(self):
        self.assertEqual(self.post.slug, "postingan-pertama")

    def test_category_slug_otomatis(self):
        self.assertEqual(self.category.slug, "teknologi")

    def test_get_absolute_url(self):
        self.assertEqual(self.post.get_absolute_url(), "/postingan-pertama/")

    def test_is_published(self):
        self.assertTrue(self.post.is_published)


class BlogViewTests(TestCase):
    @classmethod
    def setUpTestData(cls):
        cls.user = User.objects.create_user(username="penulis", password="rahasia123")
        cls.published = Post.objects.create(
            title="Postingan Terbit",
            author=cls.user,
            body="Isi terbit.",
            status=Post.Status.PUBLISHED,
        )
        cls.draft = Post.objects.create(
            title="Postingan Draft",
            author=cls.user,
            body="Isi draft.",
            status=Post.Status.DRAFT,
        )

    def test_homepage_status_dan_template(self):
        response = self.client.get(reverse("post_list"))
        self.assertEqual(response.status_code, 200)
        self.assertTemplateUsed(response, "blog/post_list.html")

    def test_homepage_hanya_tampilkan_published(self):
        response = self.client.get(reverse("post_list"))
        self.assertContains(response, "Postingan Terbit")
        self.assertNotContains(response, "Postingan Draft")

    def test_detail_view(self):
        response = self.client.get(self.published.get_absolute_url())
        self.assertEqual(response.status_code, 200)
        self.assertTemplateUsed(response, "blog/post_detail.html")

    def test_pencarian(self):
        response = self.client.get(reverse("post_list"), {"q": "Terbit"})
        self.assertContains(response, "Postingan Terbit")

    def test_create_butuh_login(self):
        response = self.client.get(reverse("post_create"))
        self.assertEqual(response.status_code, 302)
        self.assertIn("/accounts/login/", response.url)

    def test_create_setelah_login(self):
        self.client.login(username="penulis", password="rahasia123")
        response = self.client.post(
            reverse("post_create"),
            {"title": "Postingan Baru", "body": "Isi baru.", "status": "published"},
        )
        self.assertEqual(Post.objects.filter(title="Postingan Baru").count(), 1)

    def test_hanya_penulis_bisa_hapus(self):
        lain = User.objects.create_user(username="orang_lain", password="rahasia123")
        self.client.login(username="orang_lain", password="rahasia123")
        response = self.client.get(reverse("post_delete", args=[self.published.slug]))
        self.assertEqual(response.status_code, 403)


class CommentTests(TestCase):
    @classmethod
    def setUpTestData(cls):
        cls.user = User.objects.create_user(
            username="komentator", password="rahasia123"
        )
        cls.post = Post.objects.create(
            title="Bisa Dikomentari",
            author=cls.user,
            body="Isi.",
            status=Post.Status.PUBLISHED,
        )

    def test_komentar_butuh_login(self):
        response = self.client.post(
            self.post.get_absolute_url(), {"body": "Komentar anonim"}
        )
        self.assertEqual(response.status_code, 302)
        self.assertEqual(Comment.objects.count(), 0)

    def test_komentar_setelah_login(self):
        self.client.login(username="komentator", password="rahasia123")
        self.client.post(self.post.get_absolute_url(), {"body": "Komentar saya"})
        self.assertEqual(Comment.objects.count(), 1)
        self.assertEqual(Comment.objects.first().author, self.user)


class AccountTests(TestCase):
    def test_signup_page(self):
        response = self.client.get(reverse("signup"))
        self.assertEqual(response.status_code, 200)
        self.assertTemplateUsed(response, "registration/signup.html")

    def test_signup_membuat_user(self):
        response = self.client.post(
            reverse("signup"),
            {
                "username": "pendaftar",
                "email": "pendaftar@email.com",
                "password1": "KataSandi!2026",
                "password2": "KataSandi!2026",
            },
        )
        self.assertTrue(User.objects.filter(username="pendaftar").exists())

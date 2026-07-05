from django.conf import settings
from django.db import models
from django.urls import reverse
from django.utils import timezone
from django.utils.text import slugify


class Category(models.Model):
    """Kategori untuk mengelompokkan postingan blog."""

    name = models.CharField("Nama", max_length=100, unique=True)
    slug = models.SlugField(max_length=120, unique=True, blank=True)

    class Meta:
        verbose_name = "Kategori"
        verbose_name_plural = "Kategori"
        ordering = ["name"]

    def __str__(self):
        return self.name

    def save(self, *args, **kwargs):
        if not self.slug:
            self.slug = slugify(self.name)
        super().save(*args, **kwargs)

    def get_absolute_url(self):
        return reverse("category_posts", kwargs={"slug": self.slug})


class Post(models.Model):
    """Postingan blog dengan dukungan draft/publish, kategori, dan gambar."""

    class Status(models.TextChoices):
        DRAFT = "draft", "Draft"
        PUBLISHED = "published", "Dipublikasikan"

    title = models.CharField("Judul", max_length=200)
    slug = models.SlugField(max_length=220, unique=True, blank=True)
    author = models.ForeignKey(
        settings.AUTH_USER_MODEL,
        on_delete=models.CASCADE,
        related_name="posts",
        verbose_name="Penulis",
    )
    category = models.ForeignKey(
        Category,
        on_delete=models.SET_NULL,
        null=True,
        blank=True,
        related_name="posts",
        verbose_name="Kategori",
    )
    featured_image = models.ImageField(
        "Gambar utama", upload_to="posts/%Y/%m/", blank=True, null=True
    )
    body = models.TextField("Isi")
    status = models.CharField(
        max_length=10, choices=Status.choices, default=Status.DRAFT
    )
    created = models.DateTimeField("Dibuat", default=timezone.now)
    updated = models.DateTimeField("Diperbarui", auto_now=True)

    class Meta:
        verbose_name = "Postingan"
        verbose_name_plural = "Postingan"
        ordering = ["-created"]
        indexes = [models.Index(fields=["-created"])]

    def __str__(self):
        return self.title

    def save(self, *args, **kwargs):
        if not self.slug:
            base_slug = slugify(self.title)
            slug = base_slug
            counter = 1
            while Post.objects.filter(slug=slug).exclude(pk=self.pk).exists():
                slug = f"{base_slug}-{counter}"
                counter += 1
            self.slug = slug
        super().save(*args, **kwargs)

    def get_absolute_url(self):
        return reverse("post_detail", kwargs={"slug": self.slug})

    @property
    def is_published(self):
        return self.status == self.Status.PUBLISHED


class Comment(models.Model):
    """Komentar pembaca pada sebuah postingan."""

    post = models.ForeignKey(
        Post,
        on_delete=models.CASCADE,
        related_name="comments",
        verbose_name="Postingan",
    )
    author = models.ForeignKey(
        settings.AUTH_USER_MODEL,
        on_delete=models.CASCADE,
        related_name="comments",
        verbose_name="Penulis",
    )
    body = models.TextField("Komentar")
    created = models.DateTimeField("Dibuat", default=timezone.now)
    approved = models.BooleanField("Disetujui", default=True)

    class Meta:
        verbose_name = "Komentar"
        verbose_name_plural = "Komentar"
        ordering = ["created"]

    def __str__(self):
        return f"Komentar oleh {self.author} pada {self.post}"

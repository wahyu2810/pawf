import secrets

from django.conf import settings
from django.contrib.auth.models import User
from django.db import models
from django.utils import timezone


class EmailOTP(models.Model):
    """Kode OTP satu kali pakai yang dikirim ke email saat login."""

    user = models.ForeignKey(
        User, on_delete=models.CASCADE, related_name="otps", verbose_name="Pengguna"
    )
    code = models.CharField("Kode", max_length=6)
    created_at = models.DateTimeField("Dibuat", auto_now_add=True)
    expires_at = models.DateTimeField("Kedaluwarsa")
    is_used = models.BooleanField("Terpakai", default=False)
    attempts = models.PositiveSmallIntegerField("Percobaan", default=0)

    class Meta:
        verbose_name = "OTP Email"
        verbose_name_plural = "OTP Email"
        ordering = ["-created_at"]

    def __str__(self):
        return f"OTP {self.user.username} ({'terpakai' if self.is_used else 'aktif'})"

    @property
    def is_expired(self):
        return timezone.now() >= self.expires_at

    def is_valid(self):
        """OTP masih bisa dipakai bila belum terpakai, belum kedaluwarsa,
        dan belum melewati batas percobaan."""
        max_attempts = getattr(settings, "OTP_MAX_ATTEMPTS", 5)
        return not self.is_used and not self.is_expired and self.attempts < max_attempts

    @classmethod
    def generate_for(cls, user):
        """Buat OTP baru untuk user (menonaktifkan OTP lama yang belum dipakai)."""
        cls.objects.filter(user=user, is_used=False).update(is_used=True)

        length = getattr(settings, "OTP_LENGTH", 6)
        code = f"{secrets.randbelow(10 ** length):0{length}d}"
        minutes = getattr(settings, "OTP_EXPIRY_MINUTES", 5)
        return cls.objects.create(
            user=user,
            code=code,
            expires_at=timezone.now() + timezone.timedelta(minutes=minutes),
        )

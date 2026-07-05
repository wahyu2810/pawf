from django.contrib.auth.models import User
from django.core import mail
from django.test import TestCase
from django.urls import reverse
from django.utils import timezone

from .models import EmailOTP


class EmailLoginOTPTests(TestCase):
    @classmethod
    def setUpTestData(cls):
        cls.user = User.objects.create_user(
            username="budi", email="budi@email.com", password="RahasiaKuat!1"
        )

    def test_login_page_pakai_field_email(self):
        response = self.client.get(reverse("login"))
        self.assertEqual(response.status_code, 200)
        self.assertContains(response, 'name="email"')
        self.assertContains(response, 'name="password"')

    def test_login_email_salah_tidak_kirim_otp(self):
        response = self.client.post(
            reverse("login"), {"email": "budi@email.com", "password": "salah"}
        )
        self.assertEqual(response.status_code, 200)
        self.assertEqual(EmailOTP.objects.count(), 0)
        self.assertEqual(len(mail.outbox), 0)

    def test_login_benar_mengirim_otp_dan_redirect(self):
        response = self.client.post(
            reverse("login"),
            {"email": "budi@email.com", "password": "RahasiaKuat!1"},
        )
        self.assertRedirects(response, reverse("otp_verify"))
        self.assertEqual(EmailOTP.objects.filter(user=self.user).count(), 1)
        # Email OTP terkirim (ke outbox saat testing)
        self.assertEqual(len(mail.outbox), 1)
        self.assertIn("OTP", mail.outbox[0].subject)
        # Belum login sampai OTP diverifikasi
        self.assertNotIn("_auth_user_id", self.client.session)

    def test_otp_benar_menyelesaikan_login(self):
        self.client.post(
            reverse("login"),
            {"email": "budi@email.com", "password": "RahasiaKuat!1"},
        )
        otp = EmailOTP.objects.get(user=self.user)
        response = self.client.post(reverse("otp_verify"), {"code": otp.code})
        self.assertRedirects(response, reverse("post_list"))
        # Sekarang sudah login
        self.assertEqual(str(self.client.session["_auth_user_id"]), str(self.user.id))
        otp.refresh_from_db()
        self.assertTrue(otp.is_used)

    def test_otp_salah_tidak_login(self):
        self.client.post(
            reverse("login"),
            {"email": "budi@email.com", "password": "RahasiaKuat!1"},
        )
        response = self.client.post(reverse("otp_verify"), {"code": "000000"})
        self.assertEqual(response.status_code, 200)
        self.assertNotIn("_auth_user_id", self.client.session)

    def test_otp_kedaluwarsa_ditolak(self):
        self.client.post(
            reverse("login"),
            {"email": "budi@email.com", "password": "RahasiaKuat!1"},
        )
        otp = EmailOTP.objects.get(user=self.user)
        otp.expires_at = timezone.now() - timezone.timedelta(minutes=1)
        otp.save()
        response = self.client.post(reverse("otp_verify"), {"code": otp.code})
        self.assertEqual(response.status_code, 200)
        self.assertNotIn("_auth_user_id", self.client.session)

    def test_akses_otp_tanpa_sesi_redirect_ke_login(self):
        response = self.client.get(reverse("otp_verify"))
        self.assertRedirects(response, reverse("login"))

    def test_kirim_ulang_membuat_otp_baru(self):
        self.client.post(
            reverse("login"),
            {"email": "budi@email.com", "password": "RahasiaKuat!1"},
        )
        response = self.client.post(reverse("otp_resend"))
        self.assertRedirects(response, reverse("otp_verify"))
        # Ada OTP baru; total 2 email terkirim (login + kirim ulang)
        self.assertEqual(len(mail.outbox), 2)
        # Hanya satu OTP yang masih aktif (belum terpakai)
        self.assertEqual(
            EmailOTP.objects.filter(user=self.user, is_used=False).count(), 1
        )


class SignUpEmailTests(TestCase):
    def test_signup_wajib_email(self):
        response = self.client.post(
            reverse("signup"),
            {
                "username": "tanpaemail",
                "password1": "KataSandi!2026",
                "password2": "KataSandi!2026",
            },
        )
        self.assertEqual(response.status_code, 200)
        self.assertFalse(User.objects.filter(username="tanpaemail").exists())

    def test_signup_email_unik(self):
        User.objects.create_user(username="lama", email="dupe@email.com", password="x")
        response = self.client.post(
            reverse("signup"),
            {
                "username": "baru",
                "email": "dupe@email.com",
                "password1": "KataSandi!2026",
                "password2": "KataSandi!2026",
            },
        )
        self.assertEqual(response.status_code, 200)
        self.assertFalse(User.objects.filter(username="baru").exists())

from django.conf import settings
from django.contrib import messages
from django.contrib.auth import login
from django.contrib.auth.models import User
from django.core.mail import send_mail
from django.shortcuts import redirect, render
from django.urls import reverse_lazy
from django.views.generic import CreateView

from .forms import EmailLoginForm, OTPForm, SignUpForm
from .models import EmailOTP

SESSION_USER_ID = "otp_user_id"
SESSION_NEXT = "otp_next"
BACKEND = "django.contrib.auth.backends.ModelBackend"


def _find_user_by_credentials(email, password):
    """Cari user berdasarkan email (case-insensitive) yang password-nya cocok."""
    for user in User.objects.filter(email__iexact=email.strip(), is_active=True):
        if user.check_password(password):
            return user
    return None


def _send_otp(request, user):
    """Buat OTP baru dan kirim ke email user."""
    otp = EmailOTP.generate_for(user)
    send_mail(
        subject="Kode OTP Masuk — Blog Wahyu",
        message=(
            f"Halo {user.username},\n\n"
            f"Kode OTP untuk masuk ke Blog Wahyu adalah: {otp.code}\n"
            f"Kode ini berlaku {getattr(settings, 'OTP_EXPIRY_MINUTES', 5)} menit "
            f"dan hanya bisa dipakai sekali.\n\n"
            f"Jika Anda tidak mencoba masuk, abaikan email ini."
        ),
        from_email=settings.DEFAULT_FROM_EMAIL,
        recipient_list=[user.email],
        fail_silently=False,
    )
    return otp


def _mask_email(email):
    """Sembunyikan sebagian email: budi@mail.com -> b••i@mail.com."""
    try:
        name, domain = email.split("@", 1)
    except ValueError:
        return email
    if len(name) <= 2:
        masked = name[0] + "•"
    else:
        masked = name[0] + "•" * (len(name) - 2) + name[-1]
    return f"{masked}@{domain}"


def login_view(request):
    """Langkah 1: verifikasi email + password, lalu kirim OTP."""
    if request.user.is_authenticated:
        return redirect("post_list")

    if request.method == "POST":
        form = EmailLoginForm(request.POST)
        if form.is_valid():
            email = form.cleaned_data["email"]
            password = form.cleaned_data["password"]
            user = _find_user_by_credentials(email, password)
            if user is None:
                messages.error(request, "Email atau password salah.")
            elif not user.email:
                messages.error(request, "Akun ini belum memiliki email terdaftar.")
            else:
                _send_otp(request, user)
                request.session[SESSION_USER_ID] = user.id
                request.session[SESSION_NEXT] = (
                    request.GET.get("next") or request.POST.get("next") or ""
                )
                messages.info(
                    request, f"Kode OTP telah dikirim ke {_mask_email(user.email)}."
                )
                return redirect("otp_verify")
    else:
        form = EmailLoginForm()

    return render(
        request,
        "registration/login.html",
        {"form": form, "next": request.GET.get("next", "")},
    )


def otp_verify_view(request):
    """Langkah 2: verifikasi kode OTP, lalu login."""
    user_id = request.session.get(SESSION_USER_ID)
    if not user_id:
        messages.warning(request, "Sesi login berakhir. Silakan masuk kembali.")
        return redirect("login")

    user = User.objects.filter(id=user_id, is_active=True).first()
    if user is None:
        request.session.pop(SESSION_USER_ID, None)
        return redirect("login")

    if request.method == "POST":
        form = OTPForm(request.POST)
        if form.is_valid():
            code = form.cleaned_data["code"]
            otp = (
                EmailOTP.objects.filter(user=user, is_used=False)
                .order_by("-created_at")
                .first()
            )
            if otp is None or otp.is_expired:
                messages.error(
                    request, "Kode OTP kedaluwarsa. Silakan minta kode baru."
                )
            elif not otp.is_valid():
                messages.error(
                    request, "Kode OTP tidak berlaku. Silakan minta kode baru."
                )
            elif otp.code == code:
                otp.is_used = True
                otp.save(update_fields=["is_used"])
                login(request, user, backend=BACKEND)
                next_url = request.session.pop(SESSION_NEXT, "") or "post_list"
                request.session.pop(SESSION_USER_ID, None)
                messages.success(request, f"Selamat datang, {user.username}!")
                return redirect(next_url)
            else:
                otp.attempts += 1
                otp.save(update_fields=["attempts"])
                sisa = getattr(settings, "OTP_MAX_ATTEMPTS", 5) - otp.attempts
                if sisa <= 0:
                    otp.is_used = True
                    otp.save(update_fields=["is_used"])
                    messages.error(
                        request, "Terlalu banyak percobaan. Silakan minta kode baru."
                    )
                else:
                    messages.error(request, f"Kode OTP salah. Sisa percobaan: {sisa}.")
    else:
        form = OTPForm()

    return render(
        request,
        "registration/otp_verify.html",
        {"form": form, "email": _mask_email(user.email)},
    )


def otp_resend_view(request):
    """Kirim ulang kode OTP ke email yang sama."""
    user_id = request.session.get(SESSION_USER_ID)
    if not user_id:
        return redirect("login")
    user = User.objects.filter(id=user_id, is_active=True).first()
    if user and user.email:
        _send_otp(request, user)
        messages.info(request, f"Kode OTP baru dikirim ke {_mask_email(user.email)}.")
    return redirect("otp_verify")


class SignUpView(CreateView):
    """Pendaftaran pengguna baru (email wajib). Setelah daftar, diarahkan
    ke halaman masuk untuk verifikasi OTP."""

    form_class = SignUpForm
    template_name = "registration/signup.html"
    success_url = reverse_lazy("login")

    def form_valid(self, form):
        response = super().form_valid(form)
        messages.success(
            self.request, "Akun berhasil dibuat. Silakan masuk dengan email Anda."
        )
        return response

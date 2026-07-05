from django import forms
from django.contrib.auth.forms import UserCreationForm
from django.contrib.auth.models import User


class SignUpForm(UserCreationForm):
    """Pendaftaran: email wajib diisi & unik (dipakai untuk login)."""

    email = forms.EmailField(
        required=True,
        widget=forms.EmailInput(attrs={"placeholder": "nama@email.com"}),
    )

    class Meta(UserCreationForm.Meta):
        model = User
        fields = ("username", "email")

    def clean_email(self):
        email = self.cleaned_data["email"].strip().lower()
        if User.objects.filter(email__iexact=email).exists():
            raise forms.ValidationError("Email ini sudah terdaftar.")
        return email

    def save(self, commit=True):
        user = super().save(commit=False)
        user.email = self.cleaned_data["email"]
        if commit:
            user.save()
        return user


class EmailLoginForm(forms.Form):
    """Langkah 1 login: email + password."""

    email = forms.EmailField(
        label="Email",
        widget=forms.EmailInput(
            attrs={"placeholder": "nama@email.com", "autofocus": True}
        ),
    )
    password = forms.CharField(
        label="Password",
        widget=forms.PasswordInput(attrs={"placeholder": "Password"}),
    )


class OTPForm(forms.Form):
    """Langkah 2 login: masukkan kode OTP dari email."""

    code = forms.CharField(
        label="Kode OTP",
        max_length=6,
        widget=forms.TextInput(
            attrs={
                "placeholder": "______",
                "inputmode": "numeric",
                "autocomplete": "one-time-code",
                "autofocus": True,
                "maxlength": "6",
                "class": "otp-input",
            }
        ),
    )

    def clean_code(self):
        code = self.cleaned_data["code"].strip()
        if not code.isdigit():
            raise forms.ValidationError("Kode OTP hanya berupa angka.")
        return code

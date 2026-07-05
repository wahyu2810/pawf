from django.contrib.auth.views import LogoutView
from django.urls import path

from . import views
from .views import SignUpView

urlpatterns = [
    path("login/", views.login_view, name="login"),
    path("login/otp/", views.otp_verify_view, name="otp_verify"),
    path("login/otp/kirim-ulang/", views.otp_resend_view, name="otp_resend"),
    path("logout/", LogoutView.as_view(), name="logout"),
    path("signup/", SignUpView.as_view(), name="signup"),
]

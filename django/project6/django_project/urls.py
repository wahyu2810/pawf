"""
URL configuration for django_project project (Blog Wahyu).
"""

from django.conf import settings
from django.conf.urls.static import static
from django.contrib import admin
from django.urls import include, path

urlpatterns = [
    path("admin/", admin.site.urls),
    # Autentikasi kustom: login via email + OTP, logout, signup
    path("accounts/", include("accounts.urls")),
    # Aplikasi blog (homepage di root)
    path("", include("blog.urls")),
]

# Melayani file media saat pengembangan (DEBUG=True)
if settings.DEBUG:
    urlpatterns += static(settings.MEDIA_URL, document_root=settings.MEDIA_ROOT)
    urlpatterns += static(
        settings.STATIC_URL, document_root=settings.STATICFILES_DIRS[0]
    )

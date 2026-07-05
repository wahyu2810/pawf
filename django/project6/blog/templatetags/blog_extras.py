"""Template tag & filter untuk tampilan bergaya Threads."""

import os

from django import template
from django.conf import settings
from django.templatetags.static import static

register = template.Library()

# Palet warna avatar (gradien awal) — dipilih deterministik dari username.
_AVATAR_COLORS = [
    "#ff6b6b",
    "#f06595",
    "#cc5de8",
    "#845ef7",
    "#5c7cfa",
    "#339af0",
    "#22b8cf",
    "#20c997",
    "#51cf66",
    "#fcc419",
    "#ff922b",
    "#f76707",
]


def _username(value):
    """Ambil username dari objek user atau kembalikan string apa adanya."""
    return getattr(value, "username", str(value or "?"))


@register.filter
def initials(value):
    """Huruf pertama username sebagai inisial avatar."""
    name = _username(value).strip()
    return name[0].upper() if name else "?"


@register.filter
def avatar_color(value):
    """Warna avatar yang konsisten untuk setiap user (deterministik)."""
    name = _username(value)
    total = sum(ord(ch) for ch in name)
    return _AVATAR_COLORS[total % len(_AVATAR_COLORS)]


@register.simple_tag
def static_v(path):
    """Seperti {% static %}, tetapi menambahkan ?v=<mtime> agar browser
    selalu mengambil versi terbaru saat file berubah (anti-cache)."""
    url = static(path)
    file_path = os.path.join(settings.BASE_DIR, "static", *path.split("/"))
    try:
        version = int(os.path.getmtime(file_path))
    except OSError:
        return url
    return f"{url}?v={version}"

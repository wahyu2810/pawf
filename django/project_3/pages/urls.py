from django.urls import path
from .views import about_page_view, home_page_view # menambahkan about_page_view karena kita ingin menampilkan halaman about ketika user mengakses URL /about/ dan home_page_view untuk menampilkan halaman utama ketika user mengakses URL root ("/").

urlpatterns = [
	path("", home_page_view),
	path("about/", about_page_view), # menambahkan URL pattern untuk halaman about dengan path "about/" yang akan memanggil fungsi about_page_view ketika diakses.
]

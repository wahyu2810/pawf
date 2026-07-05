from django.urls import path
# menambahkan path untuk halaman about
from .views import AboutPageView, home_page_view 
# meambahkan url untuk halaman about
urlpatterns = [
	path("about/", AboutPageView.as_view(), name="about"),
	path("", home_page_view, name="home"),
]

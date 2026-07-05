from django.http import HttpResponse
from django.shortcuts import render


def home_page_view(request):
	return HttpResponse("Homepage")


def about_page_view(request):
	return render(request, "pages/about.html") #menambahkan template untuk halaman about, sehingga kita bisa menampilkan informasi lebih lengkap tentang halaman tersebut.
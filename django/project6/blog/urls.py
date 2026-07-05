from django.urls import path

from . import views

urlpatterns = [
    path("", views.PostListView.as_view(), name="post_list"),
    path("posting-saya/", views.MyPostListView.as_view(), name="my_posts"),
    path("buat/", views.PostCreateView.as_view(), name="post_create"),
    path(
        "kategori/<slug:slug>/",
        views.CategoryPostListView.as_view(),
        name="category_posts",
    ),
    path("<slug:slug>/", views.PostDetailView.as_view(), name="post_detail"),
    path("<slug:slug>/sunting/", views.PostUpdateView.as_view(), name="post_update"),
    path("<slug:slug>/hapus/", views.PostDeleteView.as_view(), name="post_delete"),
]

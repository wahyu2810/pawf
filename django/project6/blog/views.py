from django.contrib import messages
from django.contrib.auth.mixins import LoginRequiredMixin, UserPassesTestMixin
from django.db.models import Q
from django.shortcuts import get_object_or_404, redirect
from django.urls import reverse_lazy
from django.views.generic import (
    CreateView,
    DeleteView,
    DetailView,
    ListView,
    UpdateView,
)

from .forms import CommentForm, PostForm
from .models import Category, Post


class PostListView(ListView):
    """Daftar postingan yang dipublikasikan + pencarian + paginasi."""

    model = Post
    template_name = "blog/post_list.html"
    context_object_name = "posts"
    paginate_by = 5

    def get_queryset(self):
        queryset = Post.objects.filter(status=Post.Status.PUBLISHED).select_related(
            "author", "category"
        )
        query = self.request.GET.get("q")
        if query:
            queryset = queryset.filter(
                Q(title__icontains=query) | Q(body__icontains=query)
            )
        return queryset

    def get_context_data(self, **kwargs):
        context = super().get_context_data(**kwargs)
        context["categories"] = Category.objects.all()
        context["query"] = self.request.GET.get("q", "")
        return context


class CategoryPostListView(ListView):
    """Daftar postingan terpublikasi pada satu kategori."""

    model = Post
    template_name = "blog/post_list.html"
    context_object_name = "posts"
    paginate_by = 5

    def get_queryset(self):
        self.category = get_object_or_404(Category, slug=self.kwargs["slug"])
        return Post.objects.filter(
            status=Post.Status.PUBLISHED, category=self.category
        ).select_related("author", "category")

    def get_context_data(self, **kwargs):
        context = super().get_context_data(**kwargs)
        context["categories"] = Category.objects.all()
        context["active_category"] = self.category
        return context


class PostDetailView(DetailView):
    """Detail satu postingan + komentar."""

    model = Post
    template_name = "blog/post_detail.html"
    context_object_name = "post"

    def get_queryset(self):
        # Draft hanya bisa dilihat oleh penulisnya.
        if self.request.user.is_authenticated:
            return Post.objects.filter(
                Q(status=Post.Status.PUBLISHED) | Q(author=self.request.user)
            )
        return Post.objects.filter(status=Post.Status.PUBLISHED)

    def get_context_data(self, **kwargs):
        context = super().get_context_data(**kwargs)
        context["comments"] = self.object.comments.filter(approved=True)
        context["comment_form"] = CommentForm()
        return context

    def post(self, request, *args, **kwargs):
        """Tangani pengiriman komentar (harus login)."""
        self.object = self.get_object()
        if not request.user.is_authenticated:
            messages.warning(request, "Silakan masuk untuk berkomentar.")
            return redirect("login")
        form = CommentForm(request.POST)
        if form.is_valid():
            comment = form.save(commit=False)
            comment.post = self.object
            comment.author = request.user
            comment.save()
            messages.success(request, "Komentar berhasil ditambahkan.")
            return redirect(self.object.get_absolute_url())
        context = self.get_context_data(object=self.object)
        context["comment_form"] = form
        return self.render_to_response(context)


class PostCreateView(LoginRequiredMixin, CreateView):
    """Buat postingan baru (harus login)."""

    model = Post
    form_class = PostForm
    template_name = "blog/post_form.html"

    def form_valid(self, form):
        form.instance.author = self.request.user
        messages.success(self.request, "Postingan berhasil dibuat.")
        return super().form_valid(form)

    def get_context_data(self, **kwargs):
        context = super().get_context_data(**kwargs)
        context["title"] = "Tulis Postingan Baru"
        return context


class PostUpdateView(LoginRequiredMixin, UserPassesTestMixin, UpdateView):
    """Sunting postingan (hanya penulis)."""

    model = Post
    form_class = PostForm
    template_name = "blog/post_form.html"

    def form_valid(self, form):
        messages.success(self.request, "Postingan berhasil diperbarui.")
        return super().form_valid(form)

    def test_func(self):
        return self.get_object().author == self.request.user

    def get_context_data(self, **kwargs):
        context = super().get_context_data(**kwargs)
        context["title"] = "Sunting Postingan"
        return context


class PostDeleteView(LoginRequiredMixin, UserPassesTestMixin, DeleteView):
    """Hapus postingan (hanya penulis)."""

    model = Post
    template_name = "blog/post_confirm_delete.html"
    success_url = reverse_lazy("post_list")

    def test_func(self):
        return self.get_object().author == self.request.user

    def form_valid(self, form):
        messages.success(self.request, "Postingan berhasil dihapus.")
        return super().form_valid(form)


class MyPostListView(LoginRequiredMixin, ListView):
    """Daftar semua postingan milik pengguna (termasuk draft)."""

    model = Post
    template_name = "blog/my_posts.html"
    context_object_name = "posts"
    paginate_by = 10

    def get_queryset(self):
        return Post.objects.filter(author=self.request.user).select_related("category")

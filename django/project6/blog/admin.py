from django.contrib import admin

from .models import Category, Comment, Post


@admin.register(Category)
class CategoryAdmin(admin.ModelAdmin):
    list_display = ("name", "slug")
    search_fields = ("name",)
    prepopulated_fields = {"slug": ("name",)}


class CommentInline(admin.TabularInline):
    model = Comment
    extra = 0
    readonly_fields = ("author", "created")


@admin.register(Post)
class PostAdmin(admin.ModelAdmin):
    list_display = ("title", "author", "category", "status", "created")
    list_filter = ("status", "category", "created", "author")
    search_fields = ("title", "body")
    prepopulated_fields = {"slug": ("title",)}
    date_hierarchy = "created"
    ordering = ("-created",)
    inlines = [CommentInline]


@admin.register(Comment)
class CommentAdmin(admin.ModelAdmin):
    list_display = ("author", "post", "created", "approved")
    list_filter = ("approved", "created")
    search_fields = ("body", "author__username")
    actions = ["setujui_komentar"]

    @admin.action(description="Setujui komentar terpilih")
    def setujui_komentar(self, request, queryset):
        queryset.update(approved=True)

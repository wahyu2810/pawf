from django import forms

from .models import Comment, Post


class PostForm(forms.ModelForm):
    """Form untuk membuat & menyunting postingan."""

    class Meta:
        model = Post
        fields = ["title", "category", "featured_image", "body", "status"]
        widgets = {
            "title": forms.TextInput(
                attrs={"class": "form-control", "placeholder": "Judul postingan"}
            ),
            "category": forms.Select(attrs={"class": "form-select"}),
            "featured_image": forms.ClearableFileInput(attrs={"class": "form-control"}),
            "body": forms.Textarea(
                attrs={
                    "class": "form-control",
                    "rows": 10,
                    "placeholder": "Tulis isi postingan di sini...",
                }
            ),
            "status": forms.Select(attrs={"class": "form-select"}),
        }


class CommentForm(forms.ModelForm):
    """Form untuk menambahkan komentar pada postingan."""

    class Meta:
        model = Comment
        fields = ["body"]
        widgets = {
            "body": forms.Textarea(
                attrs={
                    "class": "form-control",
                    "rows": 3,
                    "placeholder": "Tulis komentar Anda...",
                }
            ),
        }
        labels = {"body": ""}

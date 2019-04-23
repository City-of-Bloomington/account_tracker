"""account_tracker URL Configuration

The `urlpatterns` list routes URLs to views. For more information please see:
    https://docs.djangoproject.com/en/2.1/topics/http/urls/
Examples:
Function views
    1. Add an import:  from my_app import views
    2. Add a URL to urlpatterns:  path('', views.home, name='home')
Class-based views
    1. Add an import:  from other_app.views import Home
    2. Add a URL to urlpatterns:  path('', Home.as_view(), name='home')
Including another URLconf
    1. Import the include() function: from django.urls import include, path
    2. Add a URL to urlpatterns:  path('blog/', include('blog.urls'))
"""
from django.contrib import admin
from django.urls import path, include
from django.views.generic.base import TemplateView
from rest_framework_jwt.views import obtain_jwt_token, refresh_jwt_token
from .routers import router
from django.conf import settings
from django.conf.urls.static import static


# TODO:
# remove pattern to serve static files for production:
# https://docs.djangoproject.com/en/2.1/howto/static-files/
urlpatterns = [
    path('accounts/', include('django.contrib.auth.urls')),
    # path(r'^login/$', auth_views.login, name='login'),
    # path(r'^logout/$', auth_views.logout, name='logout'),
    path('admin/', admin.site.urls),
    # JWT auth
    path('api/auth/obtain_token/', obtain_jwt_token),
    path('api/auth/refresh_token/', refresh_jwt_token),
    path('api/', include(router.urls)),
    path('', TemplateView.as_view(template_name='home.html'), name='home'),
] + static(settings.MEDIA_URL, document_root=settings.MEDIA_ROOT)
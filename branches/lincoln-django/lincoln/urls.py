from django.conf.urls import patterns, include, url

# Uncomment the next two lines to enable the admin:
# from django.contrib import admin
# admin.autodiscover()

from lincoln_app.views import index, overview, contents, searchbox, doc_display, keyword_display, page_image

urlpatterns = patterns('lincoln_app.views',
    url(r'^$', 'index', name='index'),
    url(r'^overview/$', 'overview', name='overview'),
    url(r'^search/$', 'searchbox', name='search'),
    url(r'^overview/$', 'overview', name='overview'),
    url(r'^contents/$', 'contents', name='contents'),
    url(r'^(?P<doc_id>[^/]+)/$', 'doc_display', name="sermon"),
    url(r'^(?P<doc_id>[^/]+)/?keyword$', 'keyword_display', name="keywords"),
    #ex: bullock.001/bullock2
    url(r'^(?P<doc_id>[^/]+)/(?P<image_id>[^/]+)/$', 'page_image', name="pages"),
    # url(r'^lincoln/', include('lincoln.foo.urls')),

    # Uncomment the admin/doc line below to enable admin documentation:
    # url(r'^admin/doc/', include('django.contrib.admindocs.urls')),

    # Uncomment the next line to enable the admin:
    # url(r'^admin/', include(admin.site.urls)),
)

<% if $FilteredImages.Count > 0 %>
<div class="mb-5 module-gallery<% if $Style %> $StyleVariant<% end_if %>">
    <% if $Title && $ShowTitle %>
    <div class="container">
        <h2 class="module-gallery__title">$Title</h2>
    </div>
    <% end_if %>

    <% if $FullWidth %>
    <div class="container-fluid<% if not $ShowThumbnailGaps %> px-0<% else %> px-4<% end_if %> module-gallery__container">
    <% else %>
    <div class="container module-gallery__container">
    <% end_if %>
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4<% if not $ShowThumbnailGaps %> g-0<% end_if %>">

            <% loop FilteredImages %>
            <div class="col<% if $Up.ShowThumbnailGaps %> mb-4<% end_if %>">
                <div class="module-gallery__item">
                    <% if $Up.LightboxEnabled %>
                    <a class="module-gallery__link venobox-{$Up.Anchor}" data-gall="gallery-{$Up.Anchor}"<% if $Up.ShowLightboxTitle && $Title %> title="$Title"<% end_if %> href="{$ResizedImage.URL}">
                        <img src="{$Image.FillMax(600,600).URL}" class="d-block w-100 module-gallery__image" alt="{$Image.Title}">
                    </a>
                    <% else %>
                    <img src="{$Image.FillMax(600,600).URL}" class="d-block w-100 module-gallery__image" alt="{$Image.Title}">
                    <% end_if %>
                    <% if $Up.ShowThumbnailTitle %>
                    <div class="module-gallery__caption">
                        <% if $Title %><p>$Title</p><% end_if %>
                    </div>
                    <% end_if %>
                </div>
            </div>
            <% end_loop %>

        </div>
    </div>
</div>
<% end_if %>

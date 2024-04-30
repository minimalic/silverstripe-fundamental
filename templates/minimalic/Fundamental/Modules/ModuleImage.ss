<div class="mb-5 module-image<% if $Style %> $StyleVariant<% end_if %>">
    <% if $Title && $ShowTitle %>
    <div class="container">
        <h2 class="module-image__title">$Title</h2>
    </div>
    <% end_if %>

    <% if $FullWidth %>
    <div class="container-fluid px-0 module-image__container">
    <% else %>
    <div class="container module-image__container">
    <% end_if %>
        <div class="row<% if $FullWidth %> g-0<% end_if %>">
            <div class="col module-image__image-wrapper">
                <img src="{$ResizedImage.URL}" class="img-fluid<% if $AllowUpscale %> w-100<% end_if %> module-image__image-source" alt="$Image.Title.XML">
            </div>
        </div>
    </div>
</div>

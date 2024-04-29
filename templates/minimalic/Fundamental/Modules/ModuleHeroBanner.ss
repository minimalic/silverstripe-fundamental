<div class="mb-5 module-herobanner<% if $Style %> $StyleVariant<% end_if %>">
    <% if $FullWidth %>
    <div class="container-fluid px-0 text-center module-herobanner__container">
    <% else %>
    <div class="container text-center module-herobanner__container">
    <% end_if %>
        <div class="row<% if $FullWidth %> g-0<% end_if %>">
            <div class="col">
                <% if $DisplayHeight == 'content' %>
                <div class="module-herobanner__image" style="background-image: url('{$ResizedImage.URL}'); background-size: cover;">
                    <div class="p-5 text-light lead module-herobanner__content">
                        <% if $Title && $ShowTitle %>
                        <h1 class="display-1 module-herobanner__title">$Title</h2>
                        <% end_if %>
                        $Content
                    </div>
                </div>
                <% else %>
                <div class="position-relative module-herobanner_image"<% if $DisplayHeight != 'default' %> style="height: {$DisplayHeight}"<% end_if %>>
                    <img src="{$ResizedImage.URL}" class="img-fluid<% if $AllowUpscale %> w-100<% end_if %><% if $DisplayHeight != 'default' %> h-100 object-fit-cover<% end_if %>" alt="$Image.Title.XML">

                    <div class="position-absolute top-50 start-50 translate-middle text-light lead module-herobanner__content">
                        <% if $Title && $ShowTitle %>
                        <h1 class="display-1 module-herobanner__title">$Title</h2>
                        <% end_if %>
                        $Content
                    </div>
                </div>
                <% end_if %>
            </div>
        </div>
    </div>
</div>

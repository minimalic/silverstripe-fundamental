<% if $FilteredSlides.Count > 0 %>
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
            <div id="carousel{$ID}" class="carousel slide<% if $SlideCrossfade %> carousel-fade<% end_if %>"<% if $Autoplay %> data-bs-ride="carousel"<% end_if %>>

                <% if $ShowIndicators && $FilteredSlides.Count > 1 %>
                <div class="carousel-indicators">
                    <% loop FilteredSlides %>
                    <button type="button" data-bs-target="#carousel{$Up.ID}" data-bs-slide-to="{$Pos(0)}"
                        <% if $IsFirst %>
                        class="active" aria-current="true"
                        <% end_if %>
                        aria-label="{$Title}"></button>
                    <% end_loop %>
                </div>
                <% end_if %>

                <div class="carousel-inner">
                    <% loop FilteredSlides %>
                    <div class="carousel-item<% if $IsFirst %> active<% end_if %>"<% if $Up.Autoplay && $Up.AutoplayInterval > 299 %> data-bs-interval="{$Up.AutoplayInterval}"<% else_if $Up.Autoplay %> data-bs-interval="2000"<% end_if %>>
                        <img src="{$ResizedImage.URL}" class="d-block w-100" alt="{$Image.Title}">
                        <% if $Up.ShowCaptions %>
                        <div class="carousel-caption d-none d-md-block">
                            <% if $Title %><h5>$Title</h5><% end_if %>
                            <% if $Content %><p>$Content</p><% end_if %>
                            <% if $Links %>
                            <div class="module-slideshow__item-buttons">
                                <% loop Links %>
                                <a class="btn <% if $Theme %>btn-{$Theme}<% else %>btn-primary<% end_if %>" href="$URL" role="button">$Title</a>
                                <% end_loop %>
                            </div>
                            <% end_if %>
                        </div>
                        <% end_if %>
                    </div>
                    <% end_loop %>
                </div>

                <% if $ShowControls && $FilteredSlides.Count > 1 %>
                <button class="carousel-control-prev" type="button" data-bs-target="#carousel{$ID}" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carousel{$ID}" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
                <% end_if %>
            </div>

        </div>
    </div>
</div>
<% end_if %>

<div class="mb-5 module-herosplit<% if $Style %> $StyleVariant<% end_if %>">
    <% if $Title && $ShowTitle %>
    <div class="container">
        <h2 class="module-herosplit__title">$Title</h2>
    </div>
    <% end_if %>

    <% if $FullWidth %>
    <div class="container-fluid px-0 module-herosplit__container">
    <% else %>
    <div class="container module-herosplit__container">
    <% end_if %>
        <div class="row<% if $FullWidth %> g-0<% end_if %>">
            <div class="col-md-6 module-herosplit__image-wrapper">
                <img src="{$ResizedImage.URL}" class="img-fluid<% if $AllowUpscale %> w-100<% end_if %> module-herosplit__image-source" alt="$Image.Title.XML">
            </div>
            <div class="col-md-6<% if $SwitchOrder %> order-first<% end_if %><% if $FullWidth %> px-5<% end_if %> module-herosplit__content">
                $Content
                <% if $Links %>
                <div class="module-herosplit__item-buttons">
                    <% loop Links %>
                    <a class="btn <% if $Theme %>btn-{$Theme}<% else %>btn-primary<% end_if %>" href="$URL" role="button">$Title</a>
                    <% end_loop %>
                </div>
                <% end_if %>
            </div>
        </div>
    </div>
</div>

<% if $FilteredItems.Count > 0 %>
<div class="mb-5 module-teaser<% if $Style %> $StyleVariant<% end_if %>">
    <% if $Title && $ShowTitle %>
    <div class="container">
        <h2 class="module-teaser__title">$Title</h2>
    </div>
    <% end_if %>

    <% if $FullWidth %>
    <div class="container-fluid<% if not $ShowItemGaps %> px-0<% else %> px-4<% end_if %> module-teaser__container">
    <% else %>
    <div class="container module-teaser__container">
    <% end_if %>
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4<% if not $ShowItemGaps %> g-0<% end_if %>">

            <% loop FilteredItems %>
            <div class="col<% if $Up.ShowItemGaps %> mb-4<% end_if %>">
                <div class="module-teaser__item" style="background-image: url({$Image.FitMax(600,600).URL})">
                    <img src="{$Icon.URL}" class="d-block w-100 module-teaser__icon" alt="{$Icon.Title}">
                    <div class="module-teaser__caption">
                        <% if $Title %><h3>$Title</h3><% end_if %>
                        <% if $Content %>$Content<% end_if %>
                    </div>
                    <% if $Links %>
                    <div class="module-teaser__item-buttons">
                        <% loop Links %>
                        <a class="btn <% if $Theme %>btn-{$Theme}<% else %>btn-primary<% end_if %>" href="$URL" role="button">$Title</a>
                        <% end_loop %>
                    </div>
                    <% end_if %>
                </div>
            </div>
            <% end_loop %>

        </div>
    </div>
</div>
<% end_if %>

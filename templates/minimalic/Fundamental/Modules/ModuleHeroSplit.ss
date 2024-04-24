<div class="mb-5">
    <% if $Title && $ShowTitle %>
    <div class="container">
        <h2 class="">$Title</h2>
    </div>
    <% end_if %>

    <% if $FullWidth %>
    <div class="container-fluid px-0">
    <% else %>
    <div class="container">
    <% end_if %>
        <div class="row<% if $FullWidth %> p-0<% end_if %>">
            <div class="col">
                <img src="{$ResizedImage.Link}" class="img-fluid<% if $AllowUpscale %> w-100<% end_if %>" alt="$Image.Title.XML">
            </div>
            <div class="col<% if $SwitchOrder %> order-first<% end_if %><% if $FullWidth %> px-5<% end_if %>">
                $Content
            </div>
        </div>
    </div>
</div>

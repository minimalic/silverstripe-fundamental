<div class="mb-5">
    <% if $FullWidth %>
    <div class="container-fluid px-0 text-center">
    <% else %>
    <div class="container text-center">
    <% end_if %>
        <div class="row<% if $FullWidth %> g-0<% end_if %>">
            <div class="col">
                <% if $DisplayHeight == 'content' %>
                <div class="" style="background-image: url('{$ResizedImage.Link}'); background-size: cover;">
                    <div class="p-5 text-light lead">
                        <% if $Title && $ShowTitle %>
                        <h1 class="display-1">$Title</h2>
                        <% end_if %>
                        $Content
                    </div>
                </div>
                <% else %>
                <div class="position-relative"<% if $DisplayHeight != 'default' %> style="height: {$DisplayHeight}"<% end_if %>>
                    <img src="{$ResizedImage.Link}" class="img-fluid<% if $AllowUpscale %> w-100<% end_if %><% if $DisplayHeight != 'default' %> h-100 object-fit-cover<% end_if %>" alt="$Image.Title.XML">

                    <div class="position-absolute top-50 start-50 translate-middle text-light lead">
                        <% if $Title && $ShowTitle %>
                        <h1 class="display-1">$Title</h2>
                        <% end_if %>
                        $Content
                    </div>
                </div>
                <% end_if %>
            </div>
        </div>
    </div>
</div>

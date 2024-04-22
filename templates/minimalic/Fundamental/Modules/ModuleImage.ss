<div class="my-5">
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
        <div class="row<% if $FullWidth %> g-0<% end_if %>">
            <div class="col">
                <img src="
                    <% if $Width > 0 && $Height > 0 %>
                        $Image.FillMax($Width,$Height).Link
                    <% else_if $Width > 0 %>
                        $Image.ScaleMaxWidth($Width).Link
                    <% else_if $Height > 0 %>
                        $Image.ScaleMaxHeight($Height).Link
                    <% else %>
                        $Image.FitMax(3840,3840).Link
                    <% end_if %>
                " class="img-fluid<% if $AllowUpscale %> w-100<% end_if %>" alt="$Image.Title.XML">
            </div>
        </div>
    </div>
</div>

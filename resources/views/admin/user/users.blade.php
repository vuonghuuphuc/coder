@extends('templates.admin')

@section('content')
@if (!$users->count())
<div class='well'>
    Results not found.
</div>
@else
<div class="users">
    @include('admin.user.users-ajax')
</div>
@endif
@stop

@section('javascript')
<script>
$(window).on('hashchange', function() {
    if (window.location.hash) {
        var page = window.location.hash.replace('#', '');
        if (page == Number.NaN || page <= 0) {
            return false;
        }
    }
});
if (window.location.hash) {
    var page = window.location.hash.replace('#', '');
    if (page != Number.NaN && page > 0) {
        getPosts(page);
    }
}
$(document).ready(function() {
    $(document).on('click', '.pagination a', function (e) {
        getPosts($(this).attr('href').split('page=')[1]);
        e.preventDefault();
    });
});
function getPosts(page) {
    $.ajax({
        url : '?page=' + page,
        dataType: 'json',
    }).done(function (data) {
        $('.users').html(data);
        location.hash = page;
    }).fail(function () {
        alert('Posts could not be loaded.');
    });
}
</script>
@parent
@stop

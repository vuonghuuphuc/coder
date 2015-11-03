<div class="list-group">
    <a href="{{ route('admin.dashboard') }}" class="list-group-item {{ Route::is('admin.dashboard') ? 'active' : '' }}">
        Main dashboard
    </a>
</div>
<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
    <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="headingOne">
            <h4 class="panel-title">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    User
                </a>
            </h4>
        </div>
        <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
            <div class="list-group">
                <a href="{{ route('admin.users') }}" class="list-group-item {{ Route::is('admin.users') ? 'active' : '' }}" class="list-group-item">All user</a>
            </div>
        </div>
    </div>
</div>
<div class="panel-group" role="tablist" aria-multiselectable="true">
    <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="headingOne">
            <h4 class="panel-title">
                <a role="button" data-toggle="collapse" href="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                    Post
                </a>
            </h4>
        </div>
        <div id="collapseTwo" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
            <div class="list-group">
                <a href="{{ route('admin.posts') }}" class="list-group-item {{ Route::is('admin.posts') ? 'active' : '' }}" class="list-group-item">All post</a>
                <a href="{{ route('admin.post') }}" class="list-group-item {{ Route::is('admin.post') ? 'active' : '' }}">Create a post</a>
            </div>
        </div>
    </div>
</div>

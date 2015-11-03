<div class='col-lg-12'>
{!! str_replace('/?', '?', $files->render()) !!}
</div>

<div class='col-lg-12'>
    @foreach($files as $file)
    <div class="col-lg-3 file-item" data-file-id="{{ $file->id }}" data-file-url="{{ asset($file->directory . $file->thumbnail) }}">
        <div class="thumbnail">
            <a href="{{ $file->url() }}" data-title="{{ $file->original_name }}" data-toggle="lightbox"><img src="{{ $file->thumbnail_url() }}"></a>
            <div class="caption">
                <button class="btn btn-primary btn-select-file" role="button">Select</a>
                <button class="btn btn-link pull-right btn-delete-file" role="button">Delete</a>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class='col-lg-12'>
{!! str_replace('/?', '?', $files->render()) !!}
</div>

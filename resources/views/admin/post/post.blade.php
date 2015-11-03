@extends('templates.admin')

@section('content')
<ol class="breadcrumb">
  <li>Create a post</li>
</ol>
<hr>
<form class="form-vertical form-submit-normal" action="{{ route('admin.post') }}" method="post" enctype="multipart/form-data">
    <div class="form-group  {{ $errors->has('title') ? 'has-error' : '' }}">
        <label for="title" class="control-label">Title</label>
        <input required="required" value="{{ Request::old('title') ?: '' }}" name="title" type="text" class="form-control" id="title">
        @if($errors->has('title'))
        <span class="help-block">{{ $errors->first('title') }}</span>
        @endif
    </div>
    <div class="form-group  {{ $errors->has('title_url') ? 'has-error' : '' }}">
        <label for="title_url" class="control-label">Title URL</label>
        <input required="required" value="{{ Request::old('title_url') ?: '' }}" name="title_url" type="text" class="form-control" id="title_url">
        @if($errors->has('title_url'))
        <span class="help-block">{{ $errors->first('title_url') }}</span>
        @endif
    </div>
    <div class="form-group {{ $errors->has('file_id') ? 'has-error' : '' }}">
        <label for="image" class="control-label">Image</label>
        <div class="row">
            <div class="col-xs-6 col-md-3">
                <a href="#" data-toggle="modal" data-target="#file-manager">
                    <img id="post-image" src="{{ $file_url ?: asset('images/upload-file.png') }}" alt="Upload image" style="height:100px;" />
                </a>
            </div>
        </div>
        <input value="{{ Request::old('file_id') ?: '' }}" name="file_id" type="hidden" class="form-control" id="post-image-id">
        @if($errors->has('file_id'))
        <span class="help-block">{{ $errors->first('file_id') }}</span>
        @endif
    </div>
    <div class="form-group {{ $errors->has('skills') ? 'has-error' : '' }}">
        <label for="skills" class="control-label">Skills</label>
        <input type="text"  name="skills" id='skills' class="form-control" value="">
        @if($errors->has('skills'))
        <span class="help-block">{{ $errors->first('skills') }}</span>
        @endif
    </div>
    <div class="form-group  {{ $errors->has('keywords') ? 'has-error' : '' }}">
        <label for="keywords" class="control-label">Keywords</label>
        <input value="{{ Request::old('keywords') ?: '' }}" name="keywords" type="text" class="form-control" id="keywords">
        @if($errors->has('keywords'))
        <span class="help-block">{{ $errors->first('keywords') }}</span>
        @endif
    </div>
    <div class="form-group  {{ $errors->has('description') ? 'has-error' : '' }}">
        <label for="description" class="control-label">Description</label>
        <textarea name="description" rows="2" class='form-control' id="description">{{ Request::old('description') ?: '' }}</textarea>
        @if($errors->has('description'))
        <span class="help-block">{{ $errors->first('description') }}</span>
        @endif
    </div>
    <div class="form-group  {{ $errors->has('body') ? 'has-error' : '' }}">
        <label for="body" class="control-label">Body</label>
        <textarea id="body" name='body'>{{ Request::old('body') ?: '' }}</textarea>
        @if($errors->has('body'))
        <span class="help-block">{{ $errors->first('body') }}</span>
        @endif
    </div>
    <div class="checkbox">
        <label>
            <input type="checkbox" name='active'> Active this post
        </label>
    </div>
    <button type="submit" class='btn btn-lg btn-default'>Post & View</button>
    <input type="hidden" name="_token" value="{{ Session::token() }}">
</form>
<!-- Modal -->
<div class="modal fade bs-example-modal-lg" id="file-manager" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Image</h4>
            </div>
            <div class="modal-body">
                <form id='form-upload-file' method="post" action="{{ route('file.upload', ['dir' => 'posts']) }}" enctype="multipart/form-data">
                    <div class="form-group form-group-lg form-group-upload-file">
                        <div class="input-group">
                            <input required='required' type="file" name="file_upload" id='file_upload' class="form-control">
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-lg btn-default">Upload</button>
                            </span>
                        </div>
                        <span class="help-block help-block-upload-file"></span>
                    </div>
                    <input type="hidden" name="_token" value="{{ Session::token() }}">
                </form>
                <div class="progress progress-upload">
                    <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                    </div>
                </div>
                <div class="row files">
                    @include('admin.file.post')
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-lg" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@stop


@section('javascript')
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/codemirror.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/mode/xml/xml.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/codemirror/2.36.0/formatting.min.js"></script>

<script src="{{ asset('summernote/summernote.min.js') }}"></script>
<script src="http://malsup.github.com/jquery.form.js"></script>
<script src="{{ asset('select2-3.5.4/select2.min.js') }}"></script>
<script src="{{ asset('lightbox/ekko-lightbox.min.js') }}"></script>


<script type="text/javascript">
$(document).ready(function() {
    $('#body').summernote({
        height: 300,
        codemirror: { // codemirror options
            mode: 'text/html',
            htmlMode: true,
            lineNumbers: true,
            theme: 'monokai'
        }
    });
});
</script>

<script type="text/javascript">
$("#skills").select2({
    tags: true,
    minimumInputLength: 2,
    maximumInputLength: 50,
    maximumSelectionSize: 5,
    ajax: {
        url: "{{ route('remote.skills') }}",
        type: 'post',
        dataType: 'json',
        delay: 250,
        quietMillis: 600,
        data: function (term, page) {
            return {
                q: term,
                _token: '{{ Session::token() }}'
            };
        },
        results: function (data, page) {
            return { results: data };
        },
        cache: true
    },
}).select2("data", {!! $skills !!});
</script>

<script type="text/javascript">
$(document).ready(function() {
    var bar = $('.progress-upload .progress-bar');
    var options = {
        beforeSend: function() {
            var percentVal = '0';
            bar.width(percentVal + "%");
            bar.attr('aria-valuenow', percentVal);
            bar.html(percentVal + "%");

            $('.form-group-upload-file').removeClass('has-error');
            $('.help-block-upload-file').html('');
        },
        uploadProgress: function(event, position, total, percentComplete) {
            var percentVal = percentComplete;
            bar.width(percentVal + "%");
            bar.attr('aria-valuenow', percentVal);
            bar.html(percentVal + "%");
        },
        success: function() {
            var percentVal = '100';
            bar.width(percentVal + "%");
            bar.attr('aria-valuenow', percentVal);
            bar.html(percentVal + "%");
        },
        complete: function(xhr) {
            var percentVal = '0';
            bar.width(percentVal + "%");
            bar.attr('aria-valuenow', percentVal);
            bar.html(percentVal + "%");

            try{
                result = JSON.parse(xhr.responseText);
                if(result.success){
                    $('#form-upload-file').resetForm();
                    getPosts(1);
                }else{
                    $('.form-group-upload-file').addClass('has-error');
                    $('.help-block-upload-file').html(result.message.file);
                }
            }catch(error){

            }
        }
    };
    $('#form-upload-file').ajaxForm(options);
});
</script>
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
    $(document).on('click', '.btn-delete-file',function(e){
        var file_item = $(this).parents('.file-item');
        var id = file_item.attr('data-file-id');
         swal({
             title: "Are you sure?",
             text: "All data using this file will detach",
             type: "warning",
             showCancelButton: true,
             confirmButtonColor: "#DD6B55",
             confirmButtonText: "Yes, delete it!",
             closeOnConfirm: false,
             showLoaderOnConfirm: true
        },function(){
             $.ajax({
                 url: "{{ route('file.delete') }}",
                 type: "post",
                 data: {id: id, _token: "{{ Session::token() }}" }
             }).done(function (data) {
                 swal("Deleted!", "Your imaginary file has been deleted.", "success");
                 file_item.find('button').prop('disabled', true);
             }).fail(function () {
                 alert('Can not delete this file.');
             });
        });
    });
    $(document).on('click', '.btn-select-file',function(e){
        var file_item = $(this).parents('.file-item');
        var id = file_item.attr('data-file-id');
        var url = file_item.attr('data-file-url');
        $('#post-image').attr('src', url);
        $('#file-manager').modal('hide');
        $('#post-image-id').val(id);
    });
});
function getPosts(page) {
    $.ajax({
        url : '?page=' + page,
        dataType: 'json',
    }).done(function (data) {
        $('.files').html(data);
        location.hash = page;
    }).fail(function () {
        alert('Posts could not be loaded.');
    });
}

$(document).delegate('*[data-toggle="lightbox"]', 'click', function(event) {
    event.preventDefault();
    $(this).ekkoLightbox();
});
</script>
@parent
@stop

@section('style')
<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/codemirror.min.css" />
<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/theme/monokai.min.css" />

<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('summernote/summernote.css') }}">
<link rel="stylesheet" href="{{ asset('select2-3.5.4/select2.css') }}">
<link rel="stylesheet" href="{{ asset('select2-3.5.4/select2-bootstrap.css') }}">
<link rel="stylesheet" href="{{ asset('lightbox/ekko-lightbox.min.css') }}">


@parent
@stop

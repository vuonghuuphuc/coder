<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ Config::get('site.name') }} | {{ Config::get('site.about') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/coder.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sweetalert.css') }}">
    @yield("style")
</head>
<body>
    @include('templates.partials.navigation')
    <div class="container" style="margin-top:20px">
        @include('templates.partials.alert')
        <div class="row">
            <div class="col-lg-3">
                @include('templates.partials.admin.menu')
            </div>
            <div class="col-lg-9">
                @yield('content')
            </div>
        </div>
    </div>
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="{{ asset('js/sweetalert.min.js') }}"></script>
    <script src="{{ asset('js/autosize.min.js') }}"></script>
    <script>
		autosize(document.querySelectorAll('textarea'));
	</script>
    <script type="text/javascript">
        @if(notify()->ready())
            swal({
                title: "{!! notify()->message() !!}",
                text: "{!! notify()->option('text') !!}",
                type: "{!! notify()->type() !!}",
            });
        @endif
    </script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('form.form-submit-normal').submit(function(){
                $(this).find('button[type="submit"]').prop('disabled', true);
            });
        });
    </script>
    @yield("javascript")
</body>
</html>

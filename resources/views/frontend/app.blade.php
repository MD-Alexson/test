<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8">
        <meta name="keywords" content="">
        <meta name="description" content="">
        <meta name="format-detection" content="telephone=no">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="{{ asset('favicon.ico') }}" rel="shortcut icon" type="image/x-icon" />
        @if(isset($data['title']))
        <title>{{ $data['title'] }}</title>
        @else
        <title>ABC Кабинет</title>
        @endif
        <link href='https://fonts.googleapis.com/css?family=Russo+One&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="{{ asset('assets/fontawesome/css/font-awesome.min.css') }}">
        @if(isset($data['assets']['css']))
        @foreach($data['assets']['css'] as $css)
        <link media="all" rel="stylesheet" type="text/css" href="{{ $css }}" />
        @endforeach
        @endif
        <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/frontend/css/mdb.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/frontend/css/all.css') }}">
        @if(Auth::guard('frontend')->check())
        <script>
            var dataLayer = [{
                    'user_id':"{{ Auth::guard('frontend')->id() }}",
                    'user_name':"{{ Auth::guard('frontend')->user()->name }}",
                    'user_status':"{{ Auth::guard('frontend')->user()->status }}",
                    'user_ip' : "{{ clientIp() }}"}];
        </script>
        @endif
        <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/bootstrap/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('assets/frontend/js/mdb.min.js') }}"></script>
        <script src="{{ asset('assets/js/moment.min.js') }}"></script>
        <script src="{{ asset('assets/js/moment.ru.js') }}"></script>
        @if(isset($data['assets']['js']))
        @foreach($data['assets']['js'] as $js)
        <script src="{{ $js }}"></script>
        @endforeach
        @endif
        <script src="{{ asset('assets/frontend/js/all.js') }}"></script>
        <?php echo html_entity_decode($project->head_end_user_code); ?>
    </head>
    <body>
        <?php echo html_entity_decode($project->body_start_user_code); ?>
        @yield('content')
    </body>
</html>
<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8">
        <meta name="keywords" content="">
        <meta name="description" content="">
        <meta name="format-detection" content="telephone=no">
        <meta name="viewport" content="width=1120">
        <link href="{{ asset('favicon.ico') }}" rel="shortcut icon" type="image/x-icon" />
        @if(isset($data['title']))
        <title>ABC Кабинет / {{ $data['title'] }}</title>
        @else
        <title>ABC Кабинет</title>
        @endif
        <link media="all" rel="stylesheet" type="text/css" href="{{ asset('assets/css/all.css') }}" />
        <link media="all" rel="stylesheet" type="text/css" href="{{ asset('assets/css/carousel.css') }}" />
        <link media="all" rel="stylesheet" type="text/css" href="{{ asset('assets/css/jquery-ui.css') }}" />
        <link media="all" rel="stylesheet" type="text/css" href="{{ asset('assets/css/datepicker.css') }}" />
        <link media="all" rel="stylesheet" type="text/css" href="{{ asset('assets/css/popover.css') }}" />
        <link media="all" rel="stylesheet" type="text/css" href="{{ asset('assets/css/jquery.fancybox.css') }}" />
        @if(isset($data['assets']['css']))
            @foreach($data['assets']['css'] as $css)
            <link media="all" rel="stylesheet" type="text/css" href="{{ $css }}" />
            @endforeach
        @endif
        <script src="{{ asset('assets/js/jquery-1.9.1.min.js') }}"></script>
        <script src="{{ asset('assets/js/jquery-ui.min.js') }}"></script>
        <script src="{{ asset('assets/js/jquery.uniform.min.js') }}"></script>
        <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('assets/js/jquery.carousel-1.1.min.js') }}"></script>
        <script src="{{ asset('assets/js/jquery.fancybox.js') }}"></script>
        <script src="{{ asset('assets/js/jquery.scrollTo.js') }}"></script>
        <script src="{{ asset('assets/js/tabs.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/locale/ru.js"></script>
        @if(isset($data['assets']['js']))
            @foreach($data['assets']['js'] as $js)
            <script src="{{ $js }}"></script>
            @endforeach
        @endif
        <!--[if lt IE 9]><script type="text/javascript" src="js/html5.js"></script><![endif]-->
        <!--[if lt IE 9]><script type="text/javascript" src="js/placeholder.js"></script><![endif]-->
        <script src="{{ asset('assets/js/all.js') }}"></script>
        @include('backend.inc.metrika')
    </head>
    <body>
        @yield('content')
        @include('backend.inc.popups')
    </body>
</html>
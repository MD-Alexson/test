<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8">
        <meta name="keywords" content="">
        <meta name="description" content="">
        <meta name="format-detection" content="telephone=no">
        <meta name="viewport" content="width=1120">
        <meta name="w1-verification" content="174092976102" />
        <link href="{{ asset('favicon.ico') }}" rel="shortcut icon" type="image/x-icon" />
        <title>ABC Кабинет{{ $data['title'] }}</title>
        <link media="all" rel="stylesheet" type="text/css" href="{{ asset('assets/css/all.css') }}" />
        <link media="all" rel="stylesheet" type="text/css" href="{{ asset('assets/css/carousel.css') }}" />
        <link media="all" rel="stylesheet" type="text/css" href="{{ asset('assets/css/jquery-ui.css') }}" />
        <link media="all" rel="stylesheet" type="text/css" href="{{ asset('assets/css/datepicker.css') }}" />
        <link media="all" rel="stylesheet" type="text/css" href="{{ asset('assets/css/popover.css') }}" />
        <link media="all" rel="stylesheet" type="text/css" href="{{ asset('assets/css/jquery.fancybox.css') }}" />
        <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/js/jquery-ui.min.js') }}"></script>
        <script src="{{ asset('assets/js/jquery.uniform.min.js') }}"></script>
        <script src="{{ asset('assets/bootstrap/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('assets/js/jquery.carousel-1.1.min.js') }}"></script>
        <script src="{{ asset('assets/js/jquery.fancybox.js') }}"></script>
        <script src="{{ asset('assets/js/jquery.scrollTo.js') }}"></script>
        <script src="{{ asset('assets/js/tabs.js') }}"></script>
        <script src="{{ asset('assets/js/moment.min.js') }}"></script>
        <script src="{{ asset('assets/js/moment.ru.js') }}"></script>
        <script src="{{ asset('assets/js/jquery.cookie.min.js') }}"></script>
        <!--[if lt IE 9]><script type="text/javascript" src="js/html5.js"></script><![endif]-->
        <!--[if lt IE 9]><script type="text/javascript" src="js/placeholder.js"></script><![endif]-->
        <script src="{{ asset('assets/js/all.js') }}"></script>
        <script language="javascript" src="https://abckab.e-autopay.com/hit.js"></script>
        @include('backend.inc.metrika')
    </head>
    <body>
        @yield('content')
        
        @if(!empty($data['codes']))
        @foreach($data['codes'] as $code)
        @include("shared.inc.".$code)
        @endforeach
        @endif
    </body>
</html>
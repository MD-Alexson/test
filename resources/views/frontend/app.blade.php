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
        <title>{{ $data['title'] }}</title>
        @else
        <title>ABC Кабинет</title>
        @endif
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
        <link rel="stylesheet" href="{{ asset('assets/frontend/css/clean-blog.min.css') }}">
        <script src="https://use.fontawesome.com/ad63e12ff7.js"></script>
        @if(isset($data['assets']['css']))
        @foreach($data['assets']['css'] as $css)
        <link media="all" rel="stylesheet" type="text/css" href="{{ $css }}" />
        @endforeach
        @endif
        <link rel="stylesheet" href="{{ asset('assets/frontend/css/all.css') }}">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.3/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
        <script src="{{ asset('assets/frontend/js/clean-blog.min.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/locale/ru.js"></script>
        @if(isset($data['assets']['js']))
        @foreach($data['assets']['js'] as $js)
        <script src="{{ $js }}"></script>
        @endforeach
        @endif
        <script src="{{ asset('assets/frontend/js/all.js') }}"></script>
        <?php echo html_entity_decode($project->head_end_user_code); ?>
        
    </head>
    @yield('content')
</html>
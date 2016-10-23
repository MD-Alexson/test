@extends('frontend.app')
@section('content')    
    @if(Auth::guard(Session::get('guard'))->check())
        @include('frontend.inc.nav')
    @else
        @include('frontend.inc.nav2')
    @endif
    <header class="intro-header" style="background-image: url({{ $data['header_bg'] }})">
        @if($web->header_dim)
        <div class='header_dim'></div>
        @endif
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="site-heading">
                        <h1>{{ $web->name }}</h1>
                        @if(!empty($web->sub))
                        <div class="divider"></div>
                        <h3>{{ $web->sub }}</h3>
                        @endif
                        <br/>
                        <span class='toLocalTime'>{{ getDatetime($web->date) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <div class="container" id="content">
        <div class="row">
            <div class="col-lg-10 col-lg-offset-1 col-xs-12">
                @if($web->timer)
                <br/>
                <br/>
                <div id='clock_wrap'>
                    <div id='clock'>
                        <div>
                            <span id='days'></span>
                            <strong id='days_s'>:</strong>
                            <span id='hours'></span>
                            <strong>:</strong>
                            <span id='minutes'></span>
                            <strong>:</strong>
                            <span id='seconds'></span>
                        </div>
                        <div>
                            <span id='s_days'>Дней</span>
                            <strong id='s_days_s'></strong>
                            <span>Часов</span>
                            <strong></strong>
                            <span>Минут</span>
                            <strong></strong>
                            <span>Секунд</span>
                        </div>
                    </div>
                </div>
                <script type='text/javascript'>
                    $(document).ready(function(){
                        $("div#clock")
                            .countdown(new Date('{{ getDatetime($web->date) }} UTC'))
                            .on('update.countdown', function(event) {
                                if(parseInt(event.strftime('%-D')) === 0){
                                    $("#clock #days, #clock #days_s, #clock #s_days, #clock #s_days_s").remove();
                                } else {
                                    $("#clock #days").text(event.strftime('%-D'));
                                }
                                $("#clock #hours").text(event.strftime('%H'));
                                $("#clock #minutes").text(event.strftime('%M'));
                                $("#clock #seconds").text(event.strftime('%S'));
                            }).on('finish.countdown', function(event) {
                                $(this).remove();
                            });
                    });
                </script>
                @endif
                <?php echo html_entity_decode($web->webinar_code); ?>
                <br/>
                <?php echo html_entity_decode($web->content); ?>
                <br/>
                @if($web->comments)
                @include('frontend.inc.disqus')
                @endif
            </div>
        </div>
    </div>
    @include('frontend.inc.footer')
@endsection
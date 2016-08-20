<div id="ent_list">
@foreach($entities as $ent)
<?php $sch2 = getTimePlus(getTime(Auth::guard(Session::get('guard'))->user()->created_at), $ent->sch2num, $ent->sch2type); ?>
@if($ent->parent !== -1)
@continue
@elseif($ent->status === "scheduled")
<a href='javascript:void(0);' class="row post-preview" style="opacity: 0.5">
@elseif($ent->status === "scheduled2" && Session::get('guard') === 'frontend' && $sch2 > getTime())
<a href='javascript:void(0);' class="row post-preview" style="opacity: 0.5">
@else
<a href='/categories/{{ $ent->id }}' class="row post-preview">
@endif
    @if($ent->thumbnail_size)
    <div class='col-sm-12'>
        <h2 class="post-title" style="margin-bottom: 20px">{{ $ent->name }}</h2>
        @if(pathTo($ent->thumbnail_750, 'imagepath'))
        <img src='{{ pathTo($ent->thumbnail_750, 'imagepath') }}' class='img img-responsive img-thumbnail'>
        @endif
        @if(!empty($ent->excerpt))
        <p class='excerpt'>{{ $ent->excerpt }}</p>
        @endif
        @if($ent->status === "scheduled" && $ent->comingsoon)
        <span class="toLocalTime" style="color: #cc0000">{{ getDateTime($ent->scheduled) }}</span>
        @elseif($ent->status === "scheduled2" && Session::get('guard') === 'frontend' && $ent->comingsoon && $sch2 > getTime())
        <span class="toLocalTime" style="color: #cc0000">{{ getDateTime($sch2) }}</span>
        @endif
    </div>
    @else
    <div class='col-sm-2'>
        @if(pathTo($ent->thumbnail_128, 'imagepath'))
        <img src='{{ pathTo($ent->thumbnail_128, 'imagepath') }}' class='img img-responsive img-thumbnail'>
        @else
        <img src='{{ asset('assets/images/thumbnails/categories/1.png') }}' class='img img-responsive img-thumbnail'>
        @endif
    </div>
    <div class='col-sm-10'>
        <h2 class="post-title">{{ $ent->name }}</h2>
        @if(!empty($ent->excerpt))
        <p class='excerpt'>{{ $ent->excerpt }}</p>
        @endif
        @if($ent->status === "scheduled" && $ent->comingsoon)
        <span class="toLocalTime" style="color: #cc0000">{{ getDateTime($ent->scheduled) }}</span>
        @elseif($ent->status === "scheduled2" && Session::get('guard') === 'frontend' && $ent->comingsoon && $sch2 > getTime())
        <span class="toLocalTime" style="color: #cc0000">{{ getDateTime($sch2) }}</span>
        @endif
        @if(!empty($hw))
        <p style='color: #cc0000; font-size: 16px;'>Необходимо выполнение следующих домашних заданий следующих публикаций:<br/>
            @foreach($ent->requiredPosts as $req)
            <strong>-</strong>{{ $req->name }}<br/>
            @endforeach
        </p>
        @endif
    </div>
    @endif
    @foreach($entities as $sub)
    @if($sub->parent === $ent->id)
    <?php $sch2 = getTimePlus(getTime(Auth::guard(Session::get('guard'))->user()->created_at), $sub->sch2num, $sub->sch2type); ?>
    <hr>
        @if($sub->status === "scheduled")
        <a href='javascript:void(0);' class="row post-preview" style="opacity: 0.5">
        @elseif($sub->status === "scheduled2" && Session::get('guard') === 'frontend' && $sch2 > getTime())
        <a href='javascript:void(0);' class="row post-preview" style="opacity: 0.5">
        @else
        <a href='/categories/{{ $sub->id }}' class="row post-preview">
        @endif
            @if($sub->thumbnail_size)
            <div class='col-sm-10 col-sm-offset-2'>
                <h2 class="post-title" style="margin-bottom: 10px; font-size: 20px;">{{ $sub->name }}</h2>
                @if(pathTo($sub->thumbnail_750, 'imagepath'))
                <img src='{{ pathTo($sub->thumbnail_750, 'imagepath') }}' class='img img-responsive img-thumbnail'>
                @endif
                @if(!empty($sub->excerpt))
                <p class='excerpt'>{{ $sub->excerpt }}</p>
                @endif
                @if($sub->status === "scheduled" && $sub->comingsoon)
                <span class="toLocalTime" style="color: #cc0000">{{ getDateTime($sub->scheduled) }}</span>
                @elseif($sub->status === "scheduled2" && Session::get('guard') === 'frontend' && $sub->comingsoon && $sch2 > getTime())
                <span class="toLocalTime" style="color: #cc0000">{{ getDateTime($sch2) }}</span>
                @endif
            </div>
            @else
            <div class='col-sm-2 col-sm-offset-2'>
                @if(pathTo($sub->thumbnail_128, 'imagepath'))
                <img src='{{ pathTo($sub->thumbnail_128, 'imagepath') }}' class='img img-responsive img-thumbnail'>
                @else
                <img src='{{ asset('assets/images/thumbnails/categories/1.png') }}' class='img img-responsive img-thumbnail'>
                @endif
            </div>
            <div class='col-sm-8'>
                <h2 class="post-title" style='font-size: 20px!important;'>{{ $sub->name }}</h2>
                @if(!empty($sub->excerpt))
                <p class='excerpt' style='padding: 8px 0px 0px 0px!important;'>{{ $sub->excerpt }}</p>
                @endif
                @if($sub->status === "scheduled" && $sub->comingsoon)
                <span class="toLocalTime" style="color: #cc0000">{{ getDateTime($sub->scheduled) }}</span>
                @elseif($sub->status === "scheduled2" && Session::get('guard') === 'frontend' && $sub->comingsoon && $sch2 > getTime())
                <span class="toLocalTime" style="color: #cc0000">{{ getDateTime($sch2) }}</span>
                @endif
            </div>
            @endif
        </a>
    @endif
    @endforeach
</a>
<hr>
@endforeach
</div>
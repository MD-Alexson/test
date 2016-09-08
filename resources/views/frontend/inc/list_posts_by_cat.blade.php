<div id="ent_list">
<?php $subs = false; ?>
@foreach($cats as $sub)
@if($sub->parent === $cat_id)
<?php $subs = true; ?>
<?php $sch2 = getTimePlus(getTime(Auth::guard(Session::get('guard'))->user()->created_at), $sub->sch2num, $sub->sch2type); ?>
@if($sub->status === "scheduled")
<a href='javascript:void(0);' class="row post-preview" style="opacity: 0.5">
@elseif($sub->status === "scheduled2" && Session::get('guard') === 'frontend' && $sch2 > getTime())
<a href='javascript:void(0);' class="row post-preview" style="opacity: 0.5">
@else
<a href='/categories/{{ $sub->id }}' class="row post-preview">
@endif
    @if($sub->thumbnail_size)
    <div class='col-sm-12' style="border-left: 2px solid #eee;">
        <h2 class="post-title" style="margin-bottom: 20px">{{ $sub->name }}</h2>
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
    <div class='col-sm-2' style="border-left: 2px solid #aaa;">
        @if(pathTo($sub->thumbnail_128, 'imagepath'))
        <img src='{{ pathTo($sub->thumbnail_128, 'imagepath') }}' class='img img-responsive img-thumbnail'>
        @else
        <img src='{{ asset('assets/images/thumbnails/categories/1.png') }}' class='img img-responsive img-thumbnail'>
        @endif
    </div>
    <div class='col-sm-10'>
        <h2 class="post-title">{{ $sub->name }}</h2>
        @if(!empty($sub->excerpt))
        <p class='excerpt'>{{ $sub->excerpt }}</p>
        @endif
        @if($sub->status === "scheduled" && $sub->comingsoon)
        <span class="toLocalTime" style="color: #cc0000">{{ getDateTime($sub->scheduled) }}</span>
        @elseif($sub->status === "scheduled2" && Session::get('guard') === 'frontend' && $sub->comingsoon && $sch2 > getTime())
        <span class="toLocalTime" style="color: #cc0000">{{ getDateTime($sch2) }}</span>
        @endif
        @if(!empty($hw))
        <p style='color: #cc0000; font-size: 16px;'>Необходимо выполнение следующих домашних заданий следующих публикаций:<br/>
            @foreach($sub->requiredPosts as $req)
            <strong>-</strong>{{ $req->name }}<br/>
            @endforeach
        </p>
        @endif
    </div>
    @endif
</a>
<hr>
@endif
@endforeach

@if($subs)
<br/><hr><br/>
@endif

@foreach($entities as $ent)
<?php $sch2 = getTimePlus(getTime(Auth::guard(Session::get('guard'))->user()->created_at), $ent->sch2num, $ent->sch2type); ?>
@if($ent->status === "scheduled")
<a href='javascript:void(0);' class="row post-preview" style="opacity: 0.5">
@elseif($ent->status === "scheduled2" && Session::get('guard') === 'frontend' && $sch2 > getTime())
<a href='javascript:void(0);' class="row post-preview" style="opacity: 0.5">
@elseif(Session::get('guard') === 'frontend' && !frontendCheckHomeworks($ent))
<a href='javascript:void(0);' class="row post-preview" style="opacity: 0.5">
<?php $hw = true; ?>
@else
<a href='/posts/{{ $ent->id }}' class="row post-preview">
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
        <img src='{{ asset('assets/images/thumbnails/posts/1.png') }}' class='img img-responsive img-thumbnail'>
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
</a>
<hr>
@endforeach
</div>
@if(!empty($subcats))
@include('frontend.inc.lists.cats', ['cats' => $subcats, 'sub' => true])
@endif
<?php $count = 0; ?>
@foreach($posts as $post)
<?php $counter = $count % 3; ?>
@if($counter === 0)
<div class="row cards">
@endif
<?php
$thumbnail = pathTo($post->thumbnail, 'imagepath');
if(!$thumbnail){
    $thumbnail = "/assets/images/thumbnails/posts/1.jpg";
}
?>
 <div class="col-md-4">
    <div class="card hoverable post">
        <div class="card-image">
            <div class="view overlay hm-white-slight z-depth-1">
                <img src="{{ $thumbnail }}" class="img-responsive" alt="">
                @if($post->stripe)
                <div class='stripe stripe_{{ $post->stripe }}'></div>
                @endif
                @if($post->status === "scheduled")
                <a href="javascript: void(0);"><div class="mask waves-effect"></div></a>
                @elseif($post->status === "scheduled2" && Session::get('guard') === 'frontend')
                <a href="javascript: void(0);"><div class="mask waves-effect"></div></a>
                @else
                <a href="/posts/{{ $post->id }}"><div class="mask waves-effect"></div></a>
                @endif
            </div>
        </div>
        <div class="card-content text-center">
            <h5>{{ $post->name }}</h5>
            @if(!empty($post->excerpt))
            <p>{{ $post->excerpt }}</p>
            @endif
            @if($post->status === "scheduled" && $post->comingsoon)
            <span class="toLocalTime" style="color: #cc0000">{{ getDateTime($post->scheduled) }}</span>
            @elseif($post->status === "scheduled2" && Session::get('guard') === 'frontend' && $post->comingsoon)
                <?php $sch2 = getTimePlus(getTime(Auth::guard(Session::get('guard'))->user()->created_at), $post->sch2num, $post->sch2type); ?>
                <span class="toLocalTime" style="color: #cc0000">{{ getDateTime($sch2) }}</span>
            @endif
        </div>
        <div class="card-btn text-center">
            @if($post->status === "scheduled")
            <a href="javascript: void(0);" class="btn btn-primary btn-md waves-effect waves-light disabled">Смотреть</a>
            @elseif($post->status === "scheduled2" && Session::get('guard') === 'frontend')
            <a href="javascript: void(0);" class="btn btn-primary btn-md waves-effect waves-light disabled">Смотреть</a>
            @else
            <a href="/posts/{{ $post->id }}" class="btn btn-primary btn-md waves-effect waves-light">Смотреть</a>
            @endif
        </div>
    </div>
</div>
@if($counter === 2)
</div>
@endif
<?php $count++; ?>
@endforeach
@if(($count-1)%3 !== 2 && $count)
</div>
@endif
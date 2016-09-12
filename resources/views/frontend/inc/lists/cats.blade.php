<?php $count = 0; ?>
@foreach($cats as $cat)
@if($cat->parent !== -1 && !isset($sub))
@continue
@endif
<?php $counter = $count % 3; ?>
@if($counter === 0)
<div class="row cards">
@endif
<?php
$thumbnail = pathTo($cat->thumbnail, 'imagepath');
if(!$thumbnail){
    $thumbnail = "/assets/images/thumbnails/categories/1.jpg";
}
?>
 <div class="col-md-4">
    <div class="card hoverable cat">
        <div class="card-image">
            <div class="view overlay hm-white-slight z-depth-1">
                <img src="{{ $thumbnail }}" class="img-responsive" alt="">
                @if($cat->status === "scheduled")
                <a href="javascript: void(0);"><div class="mask waves-effect"></div></a>
                @elseif($cat->status === "scheduled2" && Session::get('guard') === 'frontend')
                <a href="javascript: void(0);"><div class="mask waves-effect"></div></a>
                @else
                <a href="/categories/{{ $cat->id }}"><div class="mask waves-effect"></div></a>
                @endif
            </div>
        </div>
        <div class="card-content text-center">
            <h5>{{ $cat->name }}</h5>
            @if(!empty($cat->excerpt))
            <p>{{ $cat->excerpt }}</p>
            @endif
            @if($cat->status === "scheduled" && $cat->comingsoon)
            <span class="toLocalTime" style="color: #cc0000">{{ getDateTime($cat->scheduled) }}</span>
            @elseif($cat->status === "scheduled2" && Session::get('guard') === 'frontend' && $cat->comingsoon)
                <?php $sch2 = getTimePlus(getTime(Auth::guard(Session::get('guard'))->user()->created_at), $cat->sch2num, $cat->sch2type); ?>
                <span class="toLocalTime" style="color: #cc0000">{{ getDateTime($sch2) }}</span>
            @endif
        </div>
        <div class="card-btn text-center">
            @if($cat->status === "scheduled")
            <a href="javascript: void(0);" class="btn btn-primary btn-md waves-effect waves-light disabled">Смотреть</a>
            @elseif($cat->status === "scheduled2" && Session::get('guard') === 'frontend')
            <a href="javascript: void(0);" class="btn btn-primary btn-md waves-effect waves-light disabled">Смотреть</a>
            @else
            <a href="/categories/{{ $cat->id }}" class="btn btn-primary btn-md waves-effect waves-light">Смотреть</a>
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

@if(isset($sub))
<hr>
@endif
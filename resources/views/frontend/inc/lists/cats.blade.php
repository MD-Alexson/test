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
$sch2 = getTimePlus(getTime(Auth::guard(Session::get('guard'))->user()->created_at), $cat->sch2num, $cat->sch2type);
?>
@if($cat->status === 'published')
<div class="col-md-4">
@else
<div class="col-md-4 card-disabled">
@endif
    <div class="card hoverable cat">
        <div class="card-image">
            <div class="view overlay hm-white-slight z-depth-1">
                <img src="{{ $thumbnail }}" class="img-responsive" alt="">
                @if(Auth::guard('backend')->check())
                <a href="/categories/{{ $cat->id }}"><div class="mask waves-effect"></div></a>
                @elseif($cat->status === "scheduled")
                <a href="javascript: void(0);"><div class="mask waves-effect"></div></a>
                @elseif($cat->status === "scheduled2" && $sch2 > getTime())
                <a href="javascript: void(0);"><div class="mask waves-effect"></div></a>
                @else
                <a href="/categories/{{ $cat->id }}"><div class="mask waves-effect"></div></a>
                @endif
            </div>
        </div>
        <div class="card-content text-center">
            <div class='card-content-1'>
                <h5>{{ $cat->name }}</h5>
            </div>
            <div class='card-content-2'>
                @if(!empty($cat->excerpt))
                <p>{{ $cat->excerpt }}</p>
                @endif
            </div>
        </div>
        <div class="card-btn text-center">
            @if(Auth::guard('backend')->check())
                @if($cat->status === 'scheduled')
                <a href="/categories/{{ $cat->id }}" class="btn btn-danger btn-md waves-effect waves-light toLocalTime onlydate">{{ getDateTime($cat->scheduled) }}</a>
                @elseif($cat->status === 'scheduled2')
                <a href="/categories/{{ $cat->id }}" class="btn btn-danger btn-md waves-effect waves-light">{{ $cat->sch2num }} {{ $cat->sch2typename }}</a>
                @else
                <a href="/categories/{{ $cat->id }}" class="btn btn-primary btn-md waves-effect waves-light">Смотреть</a>
                @endif
            @elseif($cat->status === "scheduled")
            <a href="javascript: void(0);" class="btn btn-danger btn-md waves-effect waves-light disabled toLocalTime onlydate">{{ getDateTime($cat->scheduled) }}</a>
            @elseif($cat->status === "scheduled2" && $sch2 > getTime())
            <a href="javascript: void(0);" class="btn btn-danger btn-md waves-effect waves-light disabled toLocalTime onlydate">{{ getDateTime($sch2) }}</a>
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
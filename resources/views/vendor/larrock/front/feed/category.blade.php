@extends('larrock::front.main')
@section('title')
    @if($seo_midd['url'])
        {{ $seo_midd['url'] }}
    @else
        {{ $data->title }} {{ $seo_midd['postfix_global'] }}
    @endif
@endsection

@section('content')
    <div class="pageFeedCategory">
        <div class="col-xs-24 row">
            {!! Breadcrumbs::render('feed.category', $data) !!}
        </div>
        @if($data->short)
            <div class="short">{!! $data->short_render !!}</div>
        @endif
        @if($data->description)
            <div class="description">{!! $data->description_render !!}</div>
        @endif
        <div class="clearfix"></div>

        @foreach($data->get_child as $item)
            <div class="pageFeedCategory-item uk-grid">
                <div class="uk-width-1-1 uk-position-relative uk-margin-bottom">
                    @role('Админ|Модератор')
                    <a class="admin_edit" href="/admin/category/{{ $item->id }}/edit">Редактировать</a>
                    @endrole
                    <h2><a href="{{ $item->full_url }}">{{ $item->title }}</a></h2>
                    <div class="pageFeedCategory-item_short">{!! $item->short !!}</div>
                    @if(count($item->get_child) > 0)
                        <ul>
                            @foreach($item->get_child as $child)
                                <li><a href="{{ $child->full_url }}">{{ $child->title }}</a></li>
                            @endforeach
                        </ul>
                    @endif
                    @if(count($item->get_feedActive) > 0)
                        <ul>
                            @foreach($item->get_feedActive as $child_feed)
                                <li><a href="{{ $child_feed->full_url }}">{{ $child_feed->title }}</a></li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        @endforeach

        @if(count($data->get_feedActive) > 0 && $data->get_child)
            <div class="additional_materials">
            <p class="uk-h2">Материалы по теме:</p>
        @endif
        @foreach($data->get_feedActive as $item)
            <div class="pageFeedCategory-item">
                <div class="uk-position-relative">
                    @role('Админ|Модератор')
                    <a class="admin_edit" href="/admin/feed/{{ $item->id }}/edit">Редактировать</a>
                    @endrole
                    <h3><a href="{{ $item->full_url }}">{{ $item->title }}</a></h3>
                    <div class="pageFeedCategory-item_short">{!! $item->short !!}</div>
                </div>
            </div>
        @endforeach
        @if(count($data->get_feedActive) > 0 && $data->get_child)
            </div>
        @endif
    </div>
    {!! $data->get_feedActive->render() !!}
@endsection
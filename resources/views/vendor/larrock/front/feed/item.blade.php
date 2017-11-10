@extends('larrock::front.main')
@section('title')
    @if($seo_midd['url'])
        {{ $seo_midd['url'] }}
    @else
        {{ $data->get_seo_title or $data->title }}. {{ $data->get_category->title }} {{ $seo_midd['postfix_global'] }}
    @endif
@endsection
@section('description') {!! strip_tags($data->short) !!} @endsection
@section('share_image'){!! env('APP_URL') !!}{{ $data->first_image }}@endsection

@section('content')
    <div class="pageFeedItem uk-position-relative uk-margin-large-bottom">
        @role('Админ|Модератор')
            <a class="admin_edit" href="/admin/feed/{{ $data->id }}/edit">Редактировать материал</a>
        @endrole
        <div class="page-{{ $data->url }}">
            <div class="col-xs-24">
                {!! Breadcrumbs::render('feed.item', $data) !!}
            </div>
            @if($data->short_render)
                <div class="page_short">{!! $data->short_render !!}</div>
            @endif
            <div class="page_description">{!! $data->description_render !!}</div>
        </div>
    </div>
@endsection

@section('contentBottom')
    @if(LarrockFeed::getModel()->whereCategory($data->get_category->id)->where('id', '!=', $data->id)->count('id') > 0)
        <div class="additional_materials uk-margin-large-bottom">
            <p class="uk-h2">Другие материалы по теме:</p>
            @foreach(LarrockFeed::getModel()->whereCategory($data->get_category->id)->where('id', '!=', $data->id)->get() as $item)
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
        </div>
    @endif
    <div class="uk-text-right">
        <a class="uk-button" href="{{ $data->get_category->full_url }}">Назад к «{{ $data->get_category->title }}»</a>
    </div>
    <div class="uk-clearfix"></div>
@endsection
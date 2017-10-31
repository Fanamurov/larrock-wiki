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
            <div class="uk-clearfix"></div>
            @if($data->short_render)
                <div class="page_short">{!! $data->short_render !!}</div>
            @endif
            <div class="page_description">{!! $data->description_render !!}</div>
        </div>
    </div>
@endsection

@section('contentBottom')
    <div class="uk-text-right">
        <a class="uk-button" href="{{ $data->get_category->full_url }}">Назад к "{{ $data->get_category->title }}"</a>
    </div>
    <div class="uk-clearfix"></div>
@endsection
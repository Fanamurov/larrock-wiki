@extends('larrock::admin.main')
@section('title') Поиск по сайту @endsection

@section('content')
    <div class="ibox">
        <h2>Поиск по сайту</h2>
        <form action="/admin/search" method="get" class="uk-form">
            <input type="text" name="search" value="{{ Request::get('text') }}" placeholder="Название раздела или элемента" class="uk-form-width-large">
            {{ csrf_field() }}
            <button type="submit" class="uk-button uk-button-primary">Поиск</button>
        </form>
    </div>
    <br/>

    <div class="ibox uk-margin-large-bottom">
        <i>Ищем по компонентам: @foreach($data as $app) {{ $app->title }}@if( !$loop->last),@endif @endforeach</i>
    </div>

    @foreach($data as $app)
        @if(count($app->search) > 0)
            <div class="ibox-content uk-margin-bottom">
                <h3>{{ $app->title }}</h3>
                <table class="uk-table uk-form">
                    <thead>
                    <tr>
                        <th></th>
                        @if(isset($app->rows['title']))
                            <th>{{ $app->rows['title']->title }}</th>
                        @endif
                        @foreach($app->rows as $row)
                            @if($row->in_table_admin_ajax_editable || $row->in_table_admin)
                                <th style="width: 90px" @if($row->name !== 'active') class="uk-hidden-small" @endif>{{ $row->title }}</th>
                            @endif
                        @endforeach
                        @include('larrock::admin.admin-builder.additional-rows-th')
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($app->search as $data_value)
                        <tr>
                            <td width="70">
                                <a href="/admin/{{ $app->name }}/{{ $data_value->id }}/edit">
                                    @if($app->plugins_backend && array_key_exists('images', $app->plugins_backend) && $image = $data_value->getMedia('images')->sortByDesc('order_column')->first())
                                        <img style="width: 55px" src="{{ $image->getUrl('110x110') }}">
                                    @else
                                        <i class="uk-icon-picture-o icon-color"></i>
                                    @endif
                                </a>
                            </td>
                            @if(isset($app->rows['title']))
                                <td>
                                    <a class="uk-h4" href="/admin/{{ $app->name }}/{{ $data_value->id }}/edit">{{ $data_value->title }}</a>
                                    <br/>
                                    <a class="link-to-front" target="_blank" href="{{ $data_value->full_url }}" title="ссылка на элемент на сайте">
                                        {{ str_limit($data_value->full_url, 35, '...') }}
                                    </a>
                                </td>
                            @endif
                            @foreach($app->rows as $row)
                                @if($row->in_table_admin_ajax_editable)
                                    @if(get_class($row) === 'Larrock\Core\Helpers\FormBuilder\FormCheckbox')
                                        <td class="row-active @if($row->name !== 'active') uk-hidden-small @endif">
                                            <div class="uk-button-group btn-group_switch_ajax" role="group" style="width: 100%">
                                                <button type="button" class="uk-button uk-button-primary uk-button-small @if($data_value->{$row->name} === 0) uk-button-outline @endif"
                                                        data-row_where="id" data-value_where="{{ $data_value->id }}" data-table="{{ $app->table }}"
                                                        data-row="active" data-value="1" style="width: 50%">on</button>
                                                <button type="button" class="uk-button uk-button-danger uk-button-small @if($data_value->{$row->name} === 1) uk-button-outline @endif"
                                                        data-row_where="id" data-value_where="{{ $data_value->id }}" data-table="{{ $app->table }}"
                                                        data-row="active" data-value="0" style="width: 50%">off</button>
                                            </div>
                                        </td>
                                    @elseif(get_class($row) === 'Larrock\Core\Helpers\FormBuilder\FormInput')
                                        <td class="uk-hidden-small">
                                            <input type="text" value="{{ $data_value->{$row->name} }}" name="{{ $row->name }}"
                                                   class="ajax_edit_row form-control" data-row_where="id" data-value_where="{{ $data_value->id }}"
                                                   data-table="{{ $app->table }}">
                                        </td>
                                    @endif
                                @endif
                                @if($row->in_table_admin)
                                    <td>
                                        {{ $data_value->{$row->name} }}
                                    </td>
                                @endif
                            @endforeach
                            @include('larrock::admin.admin-builder.additional-rows-td')
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @if(method_exists($app->search, 'total'))
                    {!! $data->render() !!}
                @endif
            </div>
        @endif
    @endforeach
@endsection
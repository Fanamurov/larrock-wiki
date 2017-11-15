<form id="search-autocomplite-full" class="form-search uk-form form-search-autocomplite uk-display-inline-block uk-width-1-1" method="get" action="/admin/search">
    <div class="uk-form-row">
        <select name="query" class="uk-width-1-1 uk-form-large" id="search_site_full">
            <option value="@yield('title_search')">@yield('title_search')</option>
        </select>
        {{ csrf_field() }}
    </div>
</form>

@if(isset($search_data))
    <script type="text/javascript">
        $('#search_site_full').selectize({
            maxItems: 1,
            valueField: 'full_url',
            labelField: 'title',
            searchField: 'title',
            persist: true,
            createOnBlur: false,
            create: false,
            plugins: ['remove_button'],
            allowEmptyOption: true,
            sortField: {
                field: 'title',
                direction: 'asc'
            },
            placeholder: 'Поиск по сайту',
            options: [
                    @foreach($search_data as $item)
                {title: '{{ $item['title'] }}', id: {{ $item['id'] }}, category: '{{ $item['category'] }}', full_url: '/admin/{{ $item['component'] }}/{{ $item['id'] }}/edit'},
                @endforeach
            ],
            render: {
                item: function(item, escape) {
                    return '<div>' +
                        (item.title ? '<span class="title">' + escape(item.title.replace('&quot;', '').replace('&quot;', '')) + '</span>' : '') +
                        (item.category ? '<span class="category">/' + escape(item.category.replace('&quot;', '').replace('&quot;', '')) + '</span>' : '') +
                        '</div>';
                },
                option: function(item, escape) {
                    return '<div>' +
                        '<span class="uk-label">' + escape(item.title.replace('&quot;', '').replace('&quot;', '')) + '</span>' +
                        (item.category ? '<span class="caption">в разделе: ' + escape(item.category.replace('&quot;', '').replace('&quot;', '')) + '</span>' :'') +
                        '</div>';
                }
            },
            onChange: function (item) {
                window.location = item;
            }
        });
    </script>
@else
    <p class="alert alert-danger">Middleware SiteSearchAdmin not loaded!</p>
@endif
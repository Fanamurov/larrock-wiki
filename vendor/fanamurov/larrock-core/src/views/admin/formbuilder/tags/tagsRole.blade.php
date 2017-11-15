<div class="uk-form-row form-group-{{ $row_key }} {{ $row_settings->css_class_group }}">
    <label for="{{ $row_key }}" class="uk-form-label">
        {{ $row_settings->title }}
        @if($row_settings->help)
            <span class="uk-form-help-block">({{ $row_settings->help }})</span>
        @endif
    </label>
    <div class="input-group" style="width: 100%">
        <select @if($row_settings->max_items !== 1) multiple @endif required
            name="@if($row_settings->max_items !== 1){{ $row_key }}[]@else{{ $row_key }}@endif" id="tags_{{ $row_key }}">
            @if($selected)
                @foreach($selected as $value)
                    <option selected="selected" value="{{ $value->id }}">{{ $value->slug }}</option>
                @endforeach
            @endif
        </select>
    </div>

    <script type="text/javascript">
        $('#tags_{{ $row_key }}').selectize({
            maxItems: {!! $row_settings->max_items !!},
            valueField: 'id',
            labelField: 'title',
            searchField: 'title',
            persist: false,
            createOnBlur: false,
            create: false,
            plugins: ['remove_button'],
            allowEmptyOption: false,
            sortField: {
                field: 'title',
                direction: 'asc'
            },
            options: [
                @foreach($tags as $value)
                    {title: '{!! $value->slug !!}', id: '{!! $value->id !!}'},
                @endforeach
            ],
        });
    </script>
</div>
<nav id="wikiMenu">
    @if(count($data->first()->get_childActive) > 0)
        <ul class="uk-nav">
            @foreach($data->first()->get_childActive as $child)
                <li class="@if(count($child->get_childActive) > 0 || count($child->get_feedActive) > 0) uk-parent @endif">
                    <a href="{{ $child->full_url }}">{{ $child->title }}</a>
                    @if(count($child->get_childActive) > 0)
                        <ul class="uk-nav-sub">
                            @foreach($child->get_childActive as $child2)
                                <li @if(URL::current() === env('APP_URL').$child2->full_url) class="active" @endif>
                                    <a href="{{ $child2->full_url }}">{{ $child2->title }}</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                    @if(count($child->get_feedActive) > 0)
                        <ul class="uk-nav-sub">
                            @foreach($child->get_feedActive as $feed)
                                <li @if(URL::current() === env('APP_URL').$feed->full_url) class="active" @endif><a href="{{ $feed->full_url }}">{{ $feed->title }}</a></li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        </ul>
    @endif
</nav>
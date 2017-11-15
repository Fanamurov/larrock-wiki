<style type="text/css">
    .module_smartbanners .link_item {
        padding-bottom: 25px;
        padding-right: 17px;
    }
    .module_smartbanners .link_item .link_text {
        color: #666666;
        font-size: 11px;
        line-height: 16px;
        padding-top: 4px;
        font-family: Verdana, Arial, sans-serif !important;
    }
    .module_smartbanners .link_item .link_text_header {
        display: none;
    }
    .module_smartbanners .link_item img {
        border-radius: 6px;
        height: auto;
        width: auto;
        max-width: none;
    }
    .module_smartbanners .link_image {
        text-align: left;
        position: relative;
        left: -6px;
    }
    .module_smartbanners .link_image a {
        display: block;
    }
</style>

<div class="container-fluid">
    <div class="module_smartbanners">
        @foreach($data as $data_value)
            <div class="link_item row">
                <div class="link_text_header col-xs-24"></div>
                <div class="link_image col-xs-24">
                    <noindex><a rel="nofollow" target="a_blank" href='{{ $data_value['banner_url'] }}'>
                            <img src='{{ $data_value['image'] }}' alt='{{ $data_value['alt_title'] or 'реклама' }}'>
                        </a></noindex>
                </div>
                <div class="link_text col-xs-24">{!! $data_value['title'] !!}</div>
            </div>
        @endforeach
    </div>
</div>
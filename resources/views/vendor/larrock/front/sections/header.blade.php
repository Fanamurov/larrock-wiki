<header>
    <div class="uk-container uk-container-center">
        <div class="uk-grid">
            <div class="uk-width-1-1 uk-width-small-1-2 uk-width-medium-1-3 uk-width-large-1-4">
                <a href="/" class="a_logo"><img class="logo" src="/_assets/_front/_images/logo.png" srcset="/_assets/_front/_images/logo@2x.png 2x" alt="{{ env('SITE_NAME') }}"></a>
                <a href="/" class="logo_text">LarrockCMS</a>
            </div>
            <div class="uk-width-1-1 uk-width-small-1-2 uk-width-medium-2-3 uk-width-large-3-4 header-links">
                @if(isset($menu_default))
                    <section id="top_menu" class="uk-container uk-container-center">
                        @include('larrock::front.modules.menu.top', ['menu' => $menu_default])
                    </section>
                @endif
            </div>
        </div>
    </div>
</header>
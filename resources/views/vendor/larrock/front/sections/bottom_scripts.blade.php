<!-- Mainly scripts -->
<script src="/_assets/bower_components/uikit/js/uikit.min.js"></script>
<script src="/_assets/bower_components/uikit/js/components/grid.min.js"></script>
<script src="/_assets/bower_components/uikit/js/components/notify.min.js"></script>
<script src="/_assets/bower_components/uikit/js/core/modal.min.js"></script>
<script src="/_assets/bower_components/selectize/dist/js/standalone/selectize.min.js"></script>
<script src="/_assets/_front/_js/min/front_core.min.js"></script>
@if(isset($validator)) {!! $validator->render() !!} @endif
@stack('scripts')

<a href="#top" title="Переместиться наверх страницы" id="toTop"></a>

@if(App::environment() !== 'local')
    <!-- Yandex.Metrika counter -->
    <script type="text/javascript" >
        (function (d, w, c) {
            (w[c] = w[c] || []).push(function() {
                try {
                    w.yaCounter46679727 = new Ya.Metrika({
                        id:46679727,
                        clickmap:true,
                        trackLinks:true,
                        accurateTrackBounce:true
                    });
                } catch(e) { }
            });

            var n = d.getElementsByTagName("script")[0],
                s = d.createElement("script"),
                f = function () { n.parentNode.insertBefore(s, n); };
            s.type = "text/javascript";
            s.async = true;
            s.src = "https://mc.yandex.ru/metrika/watch.js";

            if (w.opera == "[object Opera]") {
                d.addEventListener("DOMContentLoaded", f, false);
            } else { f(); }
        })(document, window, "yandex_metrika_callbacks");
    </script>
    <noscript><div><img src="https://mc.yandex.ru/watch/46679727" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
    <!-- /Yandex.Metrika counter -->
@endif

<script>
    $(document).ready(function() {
        $('.highlight pre').each(function(i, block) {
            hljs.highlightBlock(block);
        });
        $('pre code').each(function(i, block) {
            hljs.highlightBlock(block);
        });
    });
</script>
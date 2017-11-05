<?php

namespace App\Http\Middleware;

use Cache;
use Closure;

class WikiMenu
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $categoriesTech = Cache::remember('categoriesTech', 1440, function() {
            return \LarrockCategory::getModel()->whereId(1)->with([
                'get_childActive.get_childActive.get_childActive',
                'get_childActive.get_feedActive',
            ])->get();
        });
        $categoriesUsers = Cache::remember('categoriesUsers', 1440, function() {
            return \LarrockCategory::getModel()->whereId(2)
                ->with(['get_childActive.get_childActive.get_childActive'])
                ->get();
        });

        \View::share('wikiMenuTech', view('wiki.modules.wikiMenu', ['data' => $categoriesTech])->render());
        \View::share('wikiMenuUsers', view('wiki.modules.wikiMenu', ['data' => $categoriesUsers])->render());
        return $next($request);
    }
}

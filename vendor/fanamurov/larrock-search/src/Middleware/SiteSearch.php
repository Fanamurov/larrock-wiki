<?php

namespace Larrock\ComponentSearch\Middleware;

use Cache;
use Closure;

class SiteSearch
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
        $data = Cache::remember('siteSearch', 1440, function(){
            $data = [];
            $config = config('larrock-search.components');
            foreach ($config as $item){
                if($search_data = $item->search()){
                    $data = array_merge($data, $search_data);
                }
            }
            return $data;
        });
        \View::share('searchSite', view('larrock::front.modules.search.site-autocomplite', ['search_data' => $data])->render());
        return $next($request);
    }
}
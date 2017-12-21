<?php

namespace App\Http\Controllers;

use Cache;
use Illuminate\Http\Request;
use LarrockPages;

class MainpageController extends Controller
{
    public function __construct()
    {
        $this->middleware(['web', 'GetSeo', 'SiteSearch', 'App\Http\Middleware\WikiMenu', 'AddMenuFront', 'AddBlocksTemplate']);
    }

    public function index()
    {
        $url = 'larrockcms';
        $data['data'] = Cache::remember('page'. $url, 1440, function() use ($url) {
            return LarrockPages::getModel()->whereUrl($url)->with(['get_seo', 'getImages', 'getFiles'])->active()->firstOrFail();
        });

        if(\View::exists('larrock::front.pages.'. $url)){
            return view('larrock::front.pages.'. $url, $data);
        }
        return view('larrock::front.pages.item', $data);
    }
}

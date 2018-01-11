<?php

namespace Larrock\ComponentSearch;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Larrock\Core\Traits\ShareMethods;

class AdminSearchController extends Controller
{
    use ShareMethods;

    public function __construct()
    {
        $this->shareMethods();
        $this->middleware(\LarrockPages::combineAdminMiddlewares());
    }

    public function index(Request $request)
    {
        $result = [];
        $text = $request->get('text');
        $components = \Config::get('larrock-admin-search.components');

        foreach ($components as $item){
            if($item->searchable){
                $item->search = $item->model::search($text)->get();
                $result[$item->name] = $item;
            }
        }

        return view('larrock::admin.search.result', ['data' => $result]);
    }
}
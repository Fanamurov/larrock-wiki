<?php

namespace Larrock\ComponentSearch;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AdminSearchController extends Controller
{
    public function __construct()
    {
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
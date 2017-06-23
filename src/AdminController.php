<?php

namespace Larrock\Core;

use Alert;
use Illuminate\Http\Request;
use Breadcrumbs;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use JsValidator;
use Lang;
use Redirect;
use Validator;
use View;

class AdminController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $config;

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        $data['data'] = $this->config->model::orderBy('position', 'DESC')->paginate(30);
        return view('larrock::admin.admin-builder.index', $data);
    }

    /**
     * Creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $test = Request::create('/admin/'. $this->config->name, 'POST', [
            'title' => 'Новый материал',
            'url' => str_slug('novyy-material'),
            'active' => 0,
        ]);
        return $this->store($test);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return View
     */
    public function edit($id)
    {
        $data['data'] = $this->config->model::findOrFail($id);
        $data['app'] = $this->config->tabbable($data['data']);

        $validator = JsValidator::make(Component::_valid_construct($this->config, 'update', $id));
        View::share('validator', $validator);

        Breadcrumbs::register('admin.'. $this->config->name .'.edit', function($breadcrumbs, $data)
        {
            $breadcrumbs->parent('admin.'. $this->config->name .'.index');
            $breadcrumbs->push($data->title);
        });

        return view('larrock::admin.admin-builder.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), Component::_valid_construct($this->config, 'update', $id));
        if($validator->fails()){
            return back()->withInput($request->except('password'))->withErrors($validator);
        }

        $data = $this->config->model::find($id);
        if($data->fill($request->all())->save()){
            Alert::add('successAdmin', 'Материал '. $request->input('title') .' изменен')->flash();
            \Cache::flush();
            return back();
        }

        Alert::add('errorAdmin', 'Материал '. $request->input('title') .' не изменен')->flash();
        return back()->withInput();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($search_blank = $this->config->model::whereUrl('novyy-material')->first()){
            Alert::add('errorAdmin', 'Измените URL этого материала, чтобы получить возможность создать новый')->flash();
            return redirect()->to('/admin/'. $this->config->name .'/'. $search_blank->id. '/edit');
        }

        $validator = Validator::make($request->all(), Component::_valid_construct($this->config->valid));
        if($validator->fails()){
            return back()->withInput($request->except('password'))->withErrors($validator);
        }

        $data = new $this->config->model();
        $data->fill($request->all());
        foreach ($this->config->rows as $row){
            if(get_class($row) === 'App\Helpers\FormBuilder\FormCheckbox'){
                $data->{$row->name} = $request->input($row->name, NULL);
            }
            if(get_class($row) === 'App\Helpers\FormBuilder\FormDate'){
                $data->{$row->name} = $request->input('date', date('Y-m-d'));
            }
        }

        if($data->save()){
            \Cache::flush();
            Alert::add('successAdmin', 'Материал '. $request->input('title') .' добавлен')->flash();
            return Redirect::to('/admin/'. $this->config->name .'/'. $data->id .'/edit')->withInput();
        }

        Alert::add('errorAdmin', 'Материал '. $request->input('title') .' не добавлен')->flash();
        return back()->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param  int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $id)
    {
        if($data = $this->config->model::find($id)){
            $data->clearMediaCollection();
            $name = $data->title;

            $this->config->removeDataPlugins($this->config);

            if($data->delete()){
                \Cache::flush();
                Alert::add('successAdmin', Lang::get('apps.delete.success', ['name' => $name]))->flash();
            }else{
                Alert::add('errorAdmin', Lang::get('apps.delete.error', ['name' => $name]))->flash();
            }
        }else{
            Alert::add('errorAdmin', 'Такого материала больше нет')->flash();
        }
        if($request->get('place') === 'material'){
            return Redirect::to('/admin/'. $this->config->name);
        }
        return back();
    }
}

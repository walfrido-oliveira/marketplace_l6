<?php

namespace App\Http\Controllers\Admin;

use \App\User;
use App\Store;
use App\Traits\UploadTrait;
use Illuminate\Http\Request;
use \App\Http\Requests\StoreRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class StoreController extends Controller
{

    use UploadTrait;

    /**
     * A instance of Store
     *
     * @var \App\Store
     */
    private $store;

    /**
     * Create a new instance of ProductController
     *
     * @param App\Store
     */
    public function __construct(Store $store)
    {
        $this->middleware('user.has.store')->only(['create', 'store']);
        $this->store = $store;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $store = auth()->user()->store;

        return view('admin.stores.index', compact('store'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::all(['id', 'name']);

        return view('admin.stores.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $data = $request->all();
        $user =  auth()->user();

        if($request->hasFile('logo')) {
            $data['logo'] = $this->imageUpload($request->file('logo'));
        }

        $store = $user->store()->create($data);

        flash('Loja Criada com sucesso')->success();
        return redirect()->route('admin.stores.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $store = $this->store->find($id);

        return view('admin.stores.edit', compact('store'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\StoreRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreRequest $request, $id)
    {
        $data = $request->all();

        $store = $this->store->find($id);

        if($request->hasFile('logo')) {
            if(Storage::disk('public')->exists($store->logo)) {
                Storage::disk('public')->delete($store->logo);
            }
            $data['logo'] = $this->imageUpload($request->file('logo'));
        }

        $store->update($data);

        flash('Loja Atualizada com sucesso')->success()->important();
        return redirect()->route('admin.stores.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $store = $this->store->find($id);
        $store->delete();

        flash('Loja removida com sucesso')->success();
        return redirect()->route('admin.stores.index');

    }

}

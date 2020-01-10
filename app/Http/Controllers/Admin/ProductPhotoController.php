<?php

namespace App\Http\Controllers\Admin;

use Storage;
use App\ProductPhoto;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductPhotoController extends Controller
{

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function removePhoto(Request $request)
    {
        $name = $request->get('name');

        if(Storage::disk('public')->exists($name)) {
            Storage::disk('public')->delete($name);
        }

        $removePhoto = ProductPhoto::where('image', $name);
        $productId = $removePhoto->first()->product_id;

        $removePhoto->delete();

        flash('Imagem removida com sucesso!')->success();
        return redirect()->route('admin.products.edit', ['product' => $productId]);

    }
}

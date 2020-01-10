<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cart = session()->has('cart') ? session()->get('cart') : [];

        return view('cart', compact('cart'));
    }

    /**
     * Add products into cart
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request)
    {
        $product = $request->get('product');

        if(session()->has('cart')) {
            $products = session()->get('cart');
            $productsSlugs = array_column($products, 'slug');

            if(in_array($product['slug'], $productsSlugs)) {
                $products = $this->productIncrement($product['slug'], $product['amount'], $products);
                session()->put('cart', $products);
            } else {
                session()->push('cart', $product);
            }

        } else {
            $products[] = $product;
            session()->put('cart', $products);
        }

        flash('Produto Adicionado no carrinho')->success();
        return redirect()->route('product.single', ['slug' => $product['slug']]);
    }

    /**
     * Remove a item from cart
     *
     * @param string $slug
     *
     * @return \Illuminate\Http\Response
     */
    public function remove($slug)
    {
        if(!session()->has('cart')) {
            return redirect()->route('cart.index');
        }

        $products = session()->get('cart');

        $products = array_filter($products, function($line) use($slug) {
            return $line['slug'] != $slug;
        });

        session()->put('cart', $products);
        return redirect()->route('cart.index');
    }

    /**
     * Remove all itens into cart
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function cancel()
    {
        session()->forget('cart');

        flash('Compra cancelada!')->success();
        return redirect()->route('cart.index');
    }

    /**
     *
     * @param string $slug
     * @param int $amount
     * @param array $products
     *
     * @return array
     */
    private function productIncrement($slug, $amount, $products)
    {
        $products = array_map(function($line) use($slug, $amount){
            if($slug == $line['slug']) {
                $line['amount'] += $amount;
            }
            return $line;
        }, $products);

        return $products;
    }
}

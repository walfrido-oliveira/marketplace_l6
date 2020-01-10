<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new instance
     *
     * @param App\Product
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $products = $this->product->limit(9)->orderBy('id', 'DESC')->get();

        return view('welcome', compact('products'));
    }

    /**
     *
     * Open a single product
     *
     * @param string $slug
     */
    public function single($slug)
    {
        $product = $this->product->whereSlug($slug)->first();

        return view('single', compact('product'));
    }
}

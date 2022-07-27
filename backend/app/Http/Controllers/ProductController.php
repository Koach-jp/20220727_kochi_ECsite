<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::paginate(8);
        return response()->json([
            'products' => $products
        ], 200);
    }


    public function show(Product $product)
    {
        $oneProduct = Product::find($product->id);
        return response()->json([
            'product' => $oneProduct
        ], 200);
    }
}

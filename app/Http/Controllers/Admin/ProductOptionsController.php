<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductOptionsController extends Controller
{
    public function create(Product $product)
    {
        return view('admin.products.options', compact('product'));
    }
}

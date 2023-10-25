<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\AppBaseController;
use Illuminate\Http\Request;
use App\Models\Product;

class SearchController extends AppBaseController
{
    public function index()
    {
        $products = Product::all();

        return $this->sendResponse($product,"Products retrived successfully");
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|string|min:4|max:100|unique:products',
            'description' => 'required|string|min:6|max:255',
        ]);

        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return $this->sendResponse($product,"Product created successfully", 201);
    }

    public function query(Request $request)
    {
        if($request->has('search')){
            $products = Product::search($request->search)->get();
        }else{
            $products = Product::get();
        }


        return $this->sendResponse($products, "Products retrived successfully");
    }
}

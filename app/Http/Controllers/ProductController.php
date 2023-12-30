<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProductController extends Controller
{
    /**
     * Instantiate a new ProductController instance.
     */
    public function __construct()
    {
       $this->middleware('auth');
       $this->middleware('permission:create-product|edit-product|delete-product', ['only' => ['index','show']]);
       $this->middleware('permission:create-product', ['only' => ['create','store']]);
       $this->middleware('permission:edit-product', ['only' => ['edit','update']]);
       $this->middleware('permission:delete-product', ['only' => ['destroy']]);
    }


    public function index(): View
    {
        return view('products.index', [
            'products' => Product::latest()->paginate(3)
        ]);
    }

  
    public function create(): View
    {
        return view('products.create');
    }

 
    public function store(StoreProductRequest $request): RedirectResponse
    {
        Product::create($request->all());
        return redirect()->route('products.index')
                ->withSuccess('New product is added successfully.');
    }

  
    public function show(Product $product): View
    {
        return view('products.show', [
            'product' => $product
        ]);
    }


    public function edit(Product $product): View
    {
        return view('products.edit', [
            'product' => $product
        ]);
    }

 
    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $product->update($request->all());
        return redirect()->back()
                ->withSuccess('Product is updated successfully.');
    }

  
    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();
        return redirect()->route('products.index')
                ->withSuccess('Product is deleted successfully.');
    }
}
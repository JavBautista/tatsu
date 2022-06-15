<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductsInventory;
use App\Models\Product;


class ProductsInventoryController extends Controller
{
    public function getInventoriesProduct(Request $request){
        if(!$request->ajax()) return redirect('/');
        $inventories = ProductsInventory::where('product_id',$request->product_id)->get();                
        return $inventories;
    }
    
    public function store(Request $request){
        if(!$request->ajax()) return redirect('/');

        $inventory = new ProductsInventory();        
        $inventory->product_id = $request->product_id;
        $inventory->qty = $request->qty;
        $inventory->cost = $request->cost;
        $inventory->created_at = $request->created_at;
        $inventory->save();

        $product= Product::findOrFail($request->product_id);        
        $product->qty = $request->qty;
        $product->cost = $request->cost;
        $product->save();
    }
}

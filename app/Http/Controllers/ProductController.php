<?php

namespace App\Http\Controllers;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Exports\ProductsExport;
use App\Models\Product;
use App\Models\ProductsInventory;

class ProductController extends Controller
{
    public function index(){
        return view('products');

    }

    public function get(Request $request){
        if(!$request->ajax()) return redirect('/');
        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if($buscar==''){
            $products = Product::orderBy('id', 'desc')
                        ->paginate(20);
        }else{
            $products = Product::where($criterio, 'like', '%'.$buscar.'%')
                        ->orderBy('id','desc')
                        ->paginate(20);
        }

        return [
            'pagination'=>[
                'total'=> $products->total(),
                'current_page'=> $products->currentPage(),
                'per_page'=> $products->perPage(),
                'last_page'=> $products->lastPage(),
                'from'=> $products->firstItem(),
                'to'=> $products->lastItem(),
            ],
            'products'=>$products,
        ];

    }//get

    public function store(Request $request){
        if(!$request->ajax()) return redirect('/');
        $product= new Product();
        $product->active =1;
        $product->name = $request->name;
        $product->provider = $request->provider;
        $product->description = $request->description;
        $product->unit = $request->unit;
        $product->cost = $request->cost;
        $product->qty = $request->qty;
        // Guardar el producto
        $product->save();

        // Obtener el ID después de guardar el producto
        $id = $product->id;

        // Generar el valor del campo "key" basado en el ID del registro
        $product->key = 'T' . str_pad($id, 5, '0', STR_PAD_LEFT);

        // Guardar el producto con el nuevo valor de "key"
        $product->save();

        $inventory = new ProductsInventory();        
        $inventory->product_id = $product->id;
        $inventory->qty = $request->qty;
        $inventory->cost = $request->cost;
        $inventory->created_at = $product->created_at;
        $inventory->save();

    }//store

    public function update(Request $request){
        if(!$request->ajax()) return redirect('/');
        $product= Product::findOrFail($request->product_id);
        $product->key = $request->key;
        $product->name = $request->name;
        $product->provider = $request->provider;
        $product->description = $request->description;
        $product->unit = $request->unit;
        $product->save();

    }//update

    public function activar(Request $request){
        if(!$request->ajax()) return redirect('/');
        $product= Product::findOrFail($request->id);
        $product->active = 1;
        $product->save();
    }//activar

    public function desactivar(Request $request){
        if(!$request->ajax()) return redirect('/');
        $product= Product::findOrFail($request->id);
        $product->active = 0;
        $product->save();
    }//activar

    public function export(){
        return Excel::download(new ProductsExport, 'products.xlsx');

    }

}

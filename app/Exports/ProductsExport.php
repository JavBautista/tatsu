<?php

namespace App\Exports;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ProductsExport implements FromView
{
    private $fecha_ini, $fecha_fin;

    /*function __construct($f1,$f2) {
        $this->fecha_ini = $f1;
        $this->fecha_fin = $f2;
    }*/

    public function view(): View
    {
        $products = Product::all();
        return view('products_export', ['products' => $products]);
    }
}
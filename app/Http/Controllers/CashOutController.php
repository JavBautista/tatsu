<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CashOut;

class CashOutController extends Controller
{
    public function index(){

        $cash_outs = CashOut::all();
        return view('cash_outs',['cash_outs'=>$cash_outs]);

    }

}

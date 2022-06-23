<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CashOut;

class CashOutController extends Controller
{
    public function index(){

        return view('cash_outs');

    }

    public function get(Request $request){
        if(!$request->ajax()) return redirect('/');
        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if($buscar==''){
            $cash_outs = CashOut::orderBy('date', 'desc')
                        ->paginate(20);
        }else{
            $cash_outs = CashOut::where($criterio, 'like', '%'.$buscar.'%')
                        ->orderBy('date','desc')
                        ->paginate(20);
        }

        return [
            'pagination'=>[
                'total'=> $cash_outs->total(),
                'current_page'=> $cash_outs->currentPage(),
                'per_page'=> $cash_outs->perPage(),
                'last_page'=> $cash_outs->lastPage(),
                'from'=> $cash_outs->firstItem(),
                'to'=> $cash_outs->lastItem(),
            ],
            'cash_outs'=>$cash_outs,
        ];

    }//get

    public function store(Request $request){
        if(!$request->ajax()) return redirect('/');
        
        $total= $request->sales - $request->expenses;
        
        $cash = $total-($request->card_sales + $request->card_tips);
        
        $cash_out= new CashOut();
        $cash_out->date      =$request->date;
        $cash_out->expenses  = $request->expenses;
        $cash_out->sales     = $request->sales;
        $cash_out->card_sales= $request->card_sales;
        $cash_out->card_tips = $request->card_tips;
        $cash_out->total     = $total;
        $cash_out->cash      = $cash;
        $cash_out->save();
    }//store

    public function update(Request $request){
        if(!$request->ajax()) return redirect('/');

        $total= $request->sales - $request->expenses;
        $cash = $total-($request->card_sales + $request->card_tips);
        
        $cash_out= CashOut::findOrFail($request->cash_out_id);
        $cash_out->date        =$request->date;
        $cash_out->expenses   = $request->expenses;
        $cash_out->sales      = $request->sales;
        $cash_out->card_sales = $request->card_sales;
        $cash_out->card_tips  = $request->card_tips;
        $cash_out->total      = $total;
        $cash_out->cash       = $cash;
        $cash_out->save();
    }//update

    

}

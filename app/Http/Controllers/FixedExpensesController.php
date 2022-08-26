<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FixedExpenses;

class FixedExpensesController extends Controller
{
     public function index(){

        return view('fixed_expenses');

    }

    public function get(Request $request){
        if(!$request->ajax()) return redirect('/');
        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if($buscar==''){
            $fixed_expenses = FixedExpenses::orderBy('id', 'desc')
                        ->paginate(20);
        }else{
            $fixed_expenses = FixedExpenses::where($criterio, 'like', '%'.$buscar.'%')
                        ->orderBy('id','desc')
                        ->paginate(20);
        }

        return [
            'pagination'=>[
                'total'=> $fixed_expenses->total(),
                'current_page'=> $fixed_expenses->currentPage(),
                'per_page'=> $fixed_expenses->perPage(),
                'last_page'=> $fixed_expenses->lastPage(),
                'from'=> $fixed_expenses->firstItem(),
                'to'=> $fixed_expenses->lastItem(),
            ],
            'fixed_expenses'=>$fixed_expenses,
        ];

    }//get

    public function store(Request $request){
        if(!$request->ajax()) return redirect('/');
        $fixed_expense= new FixedExpenses();
        $fixed_expense->active       = 1;
        $fixed_expense->description  = $request->description;
        $fixed_expense->amount       = $request->amount;
        $fixed_expense->cutoff       = $request->cutoff;
        $fixed_expense->save();
    }//store

    public function update(Request $request){
        if(!$request->ajax()) return redirect('/');
        $fixed_expense= FixedExpenses::findOrFail($request->id);
        $fixed_expense->description  = $request->description;
        $fixed_expense->amount       = $request->amount;
        $fixed_expense->cutoff       = $request->cutoff;
        $fixed_expense->save();
    }//update

     public function activar(Request $request){
        if(!$request->ajax()) return redirect('/');
        $fixed_expense= FixedExpenses::findOrFail($request->id);
        $fixed_expense->active  = 1;
        $fixed_expense->save();
     }

     public function desactivar(Request $request){
        if(!$request->ajax()) return redirect('/');
        $fixed_expense= FixedExpenses::findOrFail($request->id);
        $fixed_expense->active  = 0;
        $fixed_expense->save();
     }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;

class ExpenseController extends Controller
{
    public function index(){
        return view('expenses');        
    }



    public function get(Request $request){
        if(!$request->ajax()) return redirect('/');
        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if($buscar==''){
            $expenses = Expense::orderBy('date', 'desc')
                        ->paginate(20);
        }else{
            $expenses = Expense::where($criterio, 'like', '%'.$buscar.'%')
                        ->orderBy('date','desc')
                        ->paginate(20);
        }

        return [
            'pagination'=>[
                'total'=> $expenses->total(),
                'current_page'=> $expenses->currentPage(),
                'per_page'=> $expenses->perPage(),
                'last_page'=> $expenses->lastPage(),
                'from'=> $expenses->firstItem(),
                'to'=> $expenses->lastItem(),
            ],
            'expenses'=>$expenses,
        ];

    }//get

    public function store(Request $request){
        if(!$request->ajax()) return redirect('/');                
        $expense= new Expense();
        $expense->description = $request->description;
        $expense->cost = $request->cost;
        $expense->till = $request->till;
        $expense->billing = $request->billing;
        $expense->billing_reference = $request->billing_reference;
        $expense->person = $request->person;
        $expense->evidence = $request->evidence;
        $expense->date = $request->date;
        $expense->save();
    }//store

    public function updateInfo(Request $request){
        if(!$request->ajax()) return redirect('/');
        $expense= Expense::findOrFail($request->id);
        $expense->description = $request->description;
        $expense->cost = $request->cost;
        $expense->till = $request->till;
        $expense->person = $request->person;
        $expense->evidence = $request->evidence;
        $expense->save();
    }//updateInfo

    public function updateFactura(Request $request){
        if(!$request->ajax()) return redirect('/');
        $expense= Expense::findOrFail($request->id);
        $expense->billing = $request->billing;
        $expense->billing_reference = $request->billing_reference;
        $expense->save();
    }//updateFactura
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payroll;

class PayrollController extends Controller
{
    public function index(){
        return view('payroll');
    }

    public function get(Request $request){
        if(!$request->ajax()) return redirect('/');
        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if($buscar==''){
            $payroll = Payroll::where('active',1)
                        ->orderBy('id', 'desc')
                        ->paginate(20);
        }else{
            $payroll = Payroll::where('active',1)
                        ->where($criterio, 'like', '%'.$buscar.'%')
                        ->orderBy('id','desc')
                        ->paginate(20);
        }

        return [
            'pagination'=>[
                'total'=> $payroll->total(),
                'current_page'=> $payroll->currentPage(),
                'per_page'=> $payroll->perPage(),
                'last_page'=> $payroll->lastPage(),
                'from'=> $payroll->firstItem(),
                'to'=> $payroll->lastItem(),
            ],
            'payroll'=>$payroll,
        ];

    }

    public function store(Request $request)
    {
        $payroll= new Payroll();
        $payroll->employee_id = $request->employee_id;
        $payroll->active = 1;
        $payroll->date = $request->date;
        $payroll->name = $request->name;
        $payroll->description = $request->description;
        $payroll->base = $request->base;
        $payroll->discount = $request->discount;
        $payroll->bonus = $request->bonus;
        $payroll->total = $request->total;
        $payroll->observations = $request->observations;
        $payroll->save();

    }

    public function update(Request $request)
    {
        $payroll= Payroll::findOrFail($request->id);
        $payroll->name = $request->name;
        $payroll->description = $request->description;
        $payroll->base = $request->base;
        $payroll->discount = $request->discount;
        $payroll->bonus = $request->bonus;
        $payroll->total = $request->total;
        $payroll->observations = $request->observations;
        $payroll->save();
    }

    public function disable(Request $request)
    {
        $payroll= Payroll::findOrFail($request->id);
        $payroll->active = 0;
        $payroll->save();
    }
}

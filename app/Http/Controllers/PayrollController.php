<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payroll;
use App\Models\Employee;
use Illuminate\Support\Carbon;

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

        return $payroll->id;

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

    public function updateAmounts(Request $request)
    {
        $payroll= Payroll::findOrFail($request->payroll_id);
        $payroll->base = $request->base;
        $payroll->discount = $request->discount;
        $payroll->bonus = $request->bonus;
        $payroll->total = $request->total;
        $payroll->save();
    }

    public function disable(Request $request)
    {
        $payroll= Payroll::findOrFail($request->id);
        $payroll->active = 0;
        $payroll->save();
    }

    public function capturaLoadInfoPeriodo(Request $request){
        if(!$request->ajax()) return redirect('/');
        //Determinamos la fecha inicial y final a partir de la feha recibida
        $request_fecha=$request->fecha;
        $fix_date = Carbon::parse($request_fecha);
        $start = $fix_date->copy()->startOfWeek(Carbon::TUESDAY)->format('Y-m-d');
        $end   = $fix_date->copy()->endOfWeek(Carbon::MONDAY)->format('Y-m-d');

        $employees = Employee::where('active',1)->get();

        $array_personal=[];
        foreach($employees as $employee){
            $array_personal[]=[
                'employee_id' => $employee->id,
                'name'        => $employee->name,
                'base'        => 0,
                'bonus'       => 0,
                'payroll_id'  => 0
            ];
        }

        return [
            'fecha_ini'=>$start,
            'fecha_fin'=>$end,
            'array_personal'=>$array_personal,
        ];
    }//capturaLoadInfoPeriodo
}

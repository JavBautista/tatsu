<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('employees');
    }

    public function get(Request $request)
    {
         if(!$request->ajax()) return redirect('/');
        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if($buscar==''){
            $employee = Employee::orderBy('id', 'desc')
                        ->paginate(20);
        }else{
            $employee = Employee::where($criterio, 'like', '%'.$buscar.'%')
                        ->orderBy('id','desc')
                        ->paginate(20);
        }

        return [
            'pagination'=>[
                'total'=> $employee->total(),
                'current_page'=> $employee->currentPage(),
                'per_page'=> $employee->perPage(),
                'last_page'=> $employee->lastPage(),
                'from'=> $employee->firstItem(),
                'to'=> $employee->lastItem(),
            ],
            'employee'=>$employee,
        ];
    }

    public function getAllActive(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $employees = Employee::where('active',1)->get();
        return $employees;
    }


    public function store(Request $request)
    {
        $employee= new Employee();
        $employee->active=1;
        $employee->name = $request->name;
        $employee->address = $request->address;
        $employee->movil = $request->movil;
        $employee->mail = $request->mail;
        $employee->date_admission = $request->date_admission;
        $employee->date_termination = NULL;
        $employee->observations = $request->observations;
        $employee->save();

    }

    public function update(Request $request)
    {
        $employee= Employee::findOrFail($request->id);
        $employee->name = $request->name;
        $employee->address = $request->address;
        $employee->movil = $request->movil;
        $employee->mail = $request->mail;
        $employee->date_admission = $request->date_admission;
        $employee->observations = $request->observations;
        $employee->save();
    }

    public function active(Request $request)
    {
        $employee= Employee::findOrFail($request->id);
        $employee->active=1;
        $employee->save();
    }

    public function inactive(Request $request)
    {
        $employee= Employee::findOrFail($request->id);
        $employee->active=0;
        $employee->save();
    }
}

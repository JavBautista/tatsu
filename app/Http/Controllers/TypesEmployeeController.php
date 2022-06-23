<?php

namespace App\Http\Controllers;

use App\Models\TypesEmployee;
use Illuminate\Http\Request;

class TypesEmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('types_employees');
    }

    public function get(Request $request)
    {
         if(!$request->ajax()) return redirect('/');
        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if($buscar==''){
            $types_employee = TypesEmployee::orderBy('id', 'desc')
                        ->paginate(20);
        }else{
            $types_employee = TypesEmployee::where($criterio, 'like', '%'.$buscar.'%')
                        ->orderBy('id','desc')
                        ->paginate(20);
        }

        return [
            'pagination'=>[
                'total'=> $types_employee->total(),
                'current_page'=> $types_employee->currentPage(),
                'per_page'=> $types_employee->perPage(),
                'last_page'=> $types_employee->lastPage(),
                'from'=> $types_employee->firstItem(),
                'to'=> $types_employee->lastItem(),
            ],
            'types_employee'=>$types_employee,
        ];
    }


    public function store(Request $request)
    {
        $type_employee= new TypesEmployee();
        $type_employee->active=1;
        $type_employee->description=$request->description;
        $type_employee->save();

    }

    public function update(Request $request)
    {
        $type_employee= TypesEmployee::findOrFail($request->id);
        $type_employee->description=$request->description;
        $type_employee->save();
    }

    public function active(Request $request)
    {
        $type_employee= TypesEmployee::findOrFail($request->id);
        $type_employee->active=1;
        $type_employee->save();
    }

    public function inactive(Request $request)
    {
        $type_employee= TypesEmployee::findOrFail($request->id);
        $type_employee->active=0;
        $type_employee->save();
    }

}

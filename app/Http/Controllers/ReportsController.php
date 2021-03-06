<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CashOut;
use App\Models\Expense;
use App\Models\Payroll;
use Illuminate\Support\Carbon;

class ReportsController extends Controller
{

    public function testFechasCarbon(){
        $request_fecha = '2022-07-10';
        //$now = Carbon::now();
        $now = Carbon::parse($request_fecha);
        $weekStartDate = $now->copy()->startOfWeek(Carbon::TUESDAY)->format('Y-m-d');
        $weekEndDate =   $now->copy()->endOfWeek(Carbon::MONDAY)->format('Y-m-d');

        $monthStartDate = $now->copy()->startOfMonth()->format('Y-m-d');
        $monthEndDate =   $now->copy()->endOfMonth()->format('Y-m-d');

        echo 'NOW '.$now.'<br>';
        echo '----<br>';
        echo 'WEEK START '.$weekStartDate.'<br>';
        echo 'WEEK FIN '.$weekEndDate.'<br>';
        echo '----<br>';
        echo 'WEEK START '.$monthStartDate.'<br>';
        echo 'WEEK FIN '.$monthEndDate.'<br>';
        echo '----<br>';
        echo 'NOW2 '.$now.'<br>';
        echo '----<br>';

        $dd = Carbon::parse($weekStartDate);
        echo 'dd '.$dd;
        echo '----<br>';
        $array_semana=[];
        for ($i=0; $i < 7; $i++) {
            $tmp = $dd->copy()->addDay($i)->format('Y-m-d');
            echo 'tmp: '.$tmp.'<br>';
            array_push($array_semana,$tmp);

        }
        print_r($array_semana);



    }

    public function corteSemanal(){
        return view('reportes.corte_semanal');
    }

    public function corteMensual(){
        return view('reportes.corte_mensual');
    }


    public function getCorteSemanal(Request $request){
        //Determinamos la fecha inicial y final a partir de la feha recibida
        $request_fecha=$request->fecha;
        $fix_date = Carbon::parse($request_fecha);
        $start = $fix_date->copy()->startOfWeek(Carbon::TUESDAY)->format('Y-m-d');
        $end   = $fix_date->copy()->endOfWeek(Carbon::MONDAY)->format('Y-m-d');

        //Aramamos un arreglo con los días y fechas de la semana
        $array_days=[];
        $dias=[0=>'MARTES',1=>'MIERCOLES',2=>'JUEVES',3=>'VIERNES',4=>'SABADO',5=>'DOMINGO',6=>'LUNES'];
        $dd = Carbon::parse($start);
        for ($i=0; $i < 7; $i++) {
            $tmp = $dd->copy()->addDay($i)->format('Y-m-d');

            $array_days[$tmp] = [
                'dd'=>$dias[$i],
                'date'=>$tmp,
                'expenses'=>0,
                'sales'=>0,
                'card_sales'=>0,
                'card_tips'=>0,
                'total'=>0,
                'cash'=>0,
                'expenses_generals'=>0,
                'payrolls'=>0,
            ];
        }

        $total_sales      =0;
        $total_expenses   =0;
        $total_card_sales =0;
        $total_neto       =0;
        $total_expenses_generales =0;
        $total_payrolls   =0;

        //Obtenemos los cortes y gastos generales en las fechas determinadas
        $cortes = CashOut::whereBetween('date',[$start,$end])
                            ->orderBy('date','desc')
                            ->get();

        $expenses_generals = Expense::whereBetween('date',[$start,$end])
                            ->orderBy('date','desc')
                            ->get();
        $payrolls = Payroll::whereBetween('date',[$start,$end])
                            ->orderBy('date','desc')
                            ->get();

        //Con los Cortes armamos el array de datos de cada día de la semana
        foreach($cortes as $corte){
            $index=$corte->date;
            //Con cada reg de corte sumamos los datos de cada fecha
            if(isset($array_days[$index])){
                $total_sales      += $corte->sales;
                $total_expenses   += $corte->expenses;
                $total_card_sales += $corte->card_sales;
                $total_neto       += $corte->total;

                $array_days[$index]['expenses']   +=$corte->expenses;
                $array_days[$index]['sales']      +=$corte->sales;
                $array_days[$index]['card_sales'] +=$corte->card_sales;
                $array_days[$index]['card_tips']  +=$corte->card_tips;
                $array_days[$index]['total']      +=$corte->total;
                $array_days[$index]['cash']       +=$corte->cash;
            }
        }

        //Con los Gastos generales iremos sumando cada gasto al día correspendiente en el array de dias de la semana
        foreach($expenses_generals as $xg){
            $index=$xg->date;
            if(isset($array_days[$index])){
                $total_expenses_generales +=$xg->cost;
                $array_days[$index]['expenses_generals']+=$xg->cost;
            }
        }

        //Con los Payrolls iremos sumando cada gasto al día correspendiente en el array de dias de la semana
        foreach($payrolls as $pr){
            $index=$pr->date;
            if(isset($array_days[$index])){
                $total_payrolls +=$pr->total;
                $array_days[$index]['payrolls']+=$pr->total;
            }
        }


        return [
            'fecha_ini'=>$start,
            'fecha_fin'=>$end,
            'array_days'=>$array_days,
            'total_sales'=>$total_sales,
            'total_expenses'=>$total_expenses,
            'total_card_sales'=>$total_card_sales,
            'total_neto'=>$total_neto,
            'total_expenses_generales'=>$total_expenses_generales,
            'total_payrolls'=>$total_payrolls,
        ];
    }

    public function getCorteMensual(Request $request){
        //Determinamos la fecha inicial y final a partir del numero de mes recibida
        $mm   = ($request->month<10)?'0'.$request->month:$request->month;
        $yyyy = $request->year;
        $request_fecha = $yyyy.'-'.$mm.'-01';

        $fix_date = Carbon::parse($request_fecha);
        $start = $fix_date->copy()->startOfMonth()->format('Y-m-d');
        $end   = $fix_date->copy()->endOfMonth()->format('Y-m-d');

        //Aramamos un arreglo con los días y fechas de la semana
        $array_days=[];
        $dd = Carbon::parse($start);
        for ($i=0; $i < 30; $i++) {
            $tmp = $dd->copy()->addDay($i)->format('Y-m-d');
            $array_days[$tmp] = [
                'date'=>$tmp,
                'expenses'=>0,
                'sales'=>0,
                'card_sales'=>0,
                'card_tips'=>0,
                'total'=>0,
                'cash'=>0,
                'expenses_generals'=>0,
                'payrolls'=>0,
            ];
        }

        $total_sales      =0;
        $total_expenses   =0;
        $total_card_sales =0;
        $total_neto       =0;
        $total_expenses_generales =0;
        $total_payrolls =0;

        //Obtenemos los cortes y gastos generales en las fechas determinadas
        $cortes = CashOut::whereBetween('date',[$start,$end])
                            ->orderBy('date','desc')
                            ->get();

        $expenses_generals = Expense::whereBetween('date',[$start,$end])
                            ->orderBy('date','desc')
                            ->get();
        $payrolls = Payroll::whereBetween('date',[$start,$end])
                            ->orderBy('date','desc')
                            ->get();

        //Con los Cortes armamos el array de datos de cada día de la semana
        foreach($cortes as $corte){
            $index=$corte->date;
            //Con cada reg de corte sumamos los datos de cada fecha
            if(isset($array_days[$index])){
                $total_sales      += $corte->sales;
                $total_expenses   += $corte->expenses;
                $total_card_sales += $corte->card_sales;
                $total_neto       += $corte->total;

                $array_days[$index]['expenses']   +=$corte->expenses;
                $array_days[$index]['sales']      +=$corte->sales;
                $array_days[$index]['card_sales'] +=$corte->card_sales;
                $array_days[$index]['card_tips']  +=$corte->card_tips;
                $array_days[$index]['total']      +=$corte->total;
                $array_days[$index]['cash']       +=$corte->cash;
            }
        }

        //Con los Gastos generales iremos sumando cada gato al día correspendiente en el array de dias de la semana
        foreach($expenses_generals as $xg){
            $index=$xg->date;
            if(isset($array_days[$index])){
                $total_expenses_generales +=$xg->cost;
                $array_days[$index]['expenses_generals']+=$xg->cost;
            }
        }

        //Con los Gastos generales iremos sumando cada gato al día correspendiente en el array de dias de la semana
        foreach($payrolls as $pr){
            $index=$pr->date;
            if(isset($array_days[$index])){
                $total_payrolls +=$pr->total;
                $array_days[$index]['payrolls']+=$pr->total;
            }
        }


        return [
            'fecha_ini'=>$start,
            'fecha_fin'=>$end,
            'array_days'=>$array_days,
            'total_sales'=>$total_sales,
            'total_expenses'=>$total_expenses,
            'total_card_sales'=>$total_card_sales,
            'total_neto'=>$total_neto,
            'total_expenses_generales'=>$total_expenses_generales,
            'total_payrolls'=>$total_payrolls,
        ];
    }
}

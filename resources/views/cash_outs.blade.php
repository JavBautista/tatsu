@extends('layouts.app')

@section('content')
<div class="container">
    <h2>CORTES</h2>
    <table class="table">
        <thead>
            <tr>
                <th>FECHA</th>
                <th>GASTOS</th>
                <th>NOTAS</th>
                <th>TARJETA</th>
                <th>TARJETA PROPINA</th>
                <th>NETO</th>
                <th>EFECTIVO</th>
            </tr>
        </thead>
        @foreach($cash_outs as $data)
        <tr>
            <td> {{$data->date}}</td>
            <td> {{$data->expenses}}</td>
            <td> {{$data->sales}}</td>
            <td> {{$data->card_sales}}</td>
            <td> {{$data->card_tips}}</td>
            <td> {{$data->total}}</td>
            <td> {{$data->cash}}</td>
        </tr>
        @endforeach
    </table>
</div>
@endsection
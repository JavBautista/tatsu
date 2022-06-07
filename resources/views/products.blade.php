@extends('layouts.app')

@section('content')
<div class="container">
    <h2>INSUMOS</h2>
    <table class="table">
        <thead>
            <tr>
                <th>NOMBRE</th>
                <th>UNIDAD</th>
                <th>COSTO</th>
            </tr>
        </thead>
        @foreach($products as $data)
        <tr>
            <td> {{$data->name}}</td>
            <td> {{$data->unit}}</td>
            <td> {{$data->cost}}</td>
        </tr>
        @endforeach
    </table>
</div>
@endsection
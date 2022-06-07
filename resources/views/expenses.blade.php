@extends('layouts.app')

@section('content')
<div class="container">
    <h2>GATOS</h2>
    <table class="table">
        <thead>
            <tr>
                <th>FECHA</th>
                <th>PERSONA</th>
                <th>DESCRIPCION</th>
                <th>COSTO</th>
            </tr>
        </thead>
        @foreach($expenses as $data)
        <tr>
            <td> {{$data->date}}</td>
            <td> {{$data->person}}</td>
            <td> {{$data->descripcion}}</td>
            <td> {{$data->cost}}</td>
        </tr>
        @endforeach
    </table>
</div>
@endsection
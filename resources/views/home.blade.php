@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="list-group">
                      <a href="/cortes"  class="list-group-item list-group-item-action">CORTES</a>
                      <a href="/gastos"  class="list-group-item list-group-item-action">GASTOS</a>
                      <a href="/insumos" class="list-group-item list-group-item-action">INSUMOS</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

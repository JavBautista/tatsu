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
                    <h2>REPORTES</h2>
                    <div class="list-group mt-4">
                      <a href="/reporte/corte-semanal"  class="list-group-item list-group-item-action">CORTE SEMANAL</a>
                      <a href="/reporte/corte-mensual"  class="list-group-item list-group-item-action">CORTE MENSUAL</a>

                      <a href="/reporte/facturas"  class="list-group-item list-group-item-action">FACTURAS</a>

                      <a href="/reporte/ventas-anuales"  class="list-group-item list-group-item-action">REPORTE VENTAS ANAUALES</a>
                      <a href="/reporte/ventas-mes-dias"  class="list-group-item list-group-item-action">VENTAS MES/DIA</a>
                    </div>

                    <h2 class="mt-4">CAPTURAS</h2>
                    <div class="list-group mt-4">
                      <a href="/cortes"  class="list-group-item list-group-item-action">CORTES</a>
                      <a href="/gastos"  class="list-group-item list-group-item-action">GASTOS</a>
                      <a href="/insumos" class="list-group-item list-group-item-action">INSUMOS</a>
                    </div>

                    <h2 class="mt-4">CONFIGURACIONES</h2>
                    <div class="list-group mt-4">
                      <a href="/gastos-fijos"  class="list-group-item list-group-item-action">GASTOS FIJOS</a>
                      <a href="/personal"  class="list-group-item list-group-item-action">PERSONAL</a>
                      <a href="/tipos-personal"  class="list-group-item list-group-item-action">TIPOS DE PERSONAL</a>
                      <a href="/nomina/captura"  class="list-group-item list-group-item-action">NOMINA</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

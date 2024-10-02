@extends('layouts.app')
@section('content')
	<!--<corte-semanal-component></corte-semanal-component>-->
	<!-- reportes/ventas-anuales.blade.php -->
<div class="container">
    <form action="{{ route('ventas.mes-dia') }}" method="GET">
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="year">Año:</label>
                <select name="year" id="year" class="form-control">
                    @for ($i = date('Y'); $i >= 2022; $i--)
                        <option value="{{ $i }}" {{ $i == $year ? 'selected' : '' }} >{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="form-group col-md-6">
                <label for="month">Mes:</label>
                <select name="month" id="month" class="form-control">
                    @for ($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                    @endfor
                </select>
            </div>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Filtrar</button>
        </div>
    </form>
    <hr>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Total Ventas</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalVentasMes = 0;
                $minVentas = PHP_INT_MAX; // Valor máximo inicial para comparación
                $maxVentas = PHP_INT_MIN; // Valor mínimo inicial para comparación
                $ventasDiarias = []; // Array para almacenar las ventas diarias

                // Primero, sumar las ventas diarias y determinar min y max
                foreach ($ventasPorDia as $dia => $ventas) {
                    $totalVentas = $ventas->sum('sales');
                    $ventasDiarias[$dia] = $totalVentas;

                    // Comparar para encontrar el mínimo y máximo
                    if ($totalVentas < $minVentas) {
                        $minVentas = $totalVentas;
                    }
                    if ($totalVentas > $maxVentas) {
                        $maxVentas = $totalVentas;
                    }
                }
            @endphp
            @foreach ($ventasDiarias as $dia => $totalVentas)
                <tr class="{{ $totalVentas == $minVentas ? 'table-danger' : ($totalVentas == $maxVentas ? 'table-success' : '') }}">
                    <td>{{ Carbon\Carbon::createFromFormat('d', $dia)->format('d/m/Y') }}</td>
                    <td>
                        @php
                            $totalVentasMes += $totalVentas;
                        @endphp
                        $ {{ number_format($totalVentas, 2) }} <!-- Formatear el total a dos decimales -->
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td>TOTAL</td>
                <td><strong>$ {{ number_format($totalVentasMes, 2) }}</strong></td>
            </tr>
        </tfoot>
    </table>


</div>


@endsection
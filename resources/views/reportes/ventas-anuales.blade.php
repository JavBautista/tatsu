@extends('layouts.app')
@section('content')
	<!--<corte-semanal-component></corte-semanal-component>-->
	<!-- reportes/ventas-anuales.blade.php -->
   <div class="container">
		<div class="legend">
		    <div class="legend-item">
		        <span class="badge text-bg-primary">$</span>
		        <span>Ventas</span>
		    </div>
		    <div class="legend-item">
		        <span class="badge text-bg-danger">$</span>
		        <span>Gastos</span>
		    </div>
		</div>

		<table class="table" border="1">
		    <thead>
		        <tr>
		            <th>Mes</th>
		            @foreach (array_keys($formattedData) as $year)
		                <th>{{ $year }}</th>
		            @endforeach
		        </tr>
		    </thead>
		    <tbody>
		        @for ($month = 1; $month <= 12; $month++)
		            <tr>
		                <td>{{ DateTime::createFromFormat('!m', $month)->format('F') }}</td>
		                @foreach (array_keys($formattedData) as $year)
		                    <td>
		                        @if (isset($formattedData[$year][$month]))
		                            <span class="text-primary">$ {{ number_format($formattedData[$year][$month]['total_sales'], 2, '.', ',') }} MXN </span><br>
		                            <span class="text-danger">$ {{ number_format($formattedData[$year][$month]['total_expenses'], 2, '.', ',') }} MXN</span>


		                        @else
		                            $ 0.00 MXN
		                        @endif
		                    </td>
		                @endforeach
		            </tr>
		        @endfor
		    </tbody>
		</table>

	</div>

@endsection
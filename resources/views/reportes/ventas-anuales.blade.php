@extends('layouts.app')
@section('content')
	<!--<corte-semanal-component></corte-semanal-component>-->
	<!-- reportes/ventas-anuales.blade.php -->
   <div class="container">
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
		                        @if (isset($formattedData[$year][$month - 1]))
		                            {{ number_format($formattedData[$year][$month - 1]['total_sales'], 2, '.', ',') }} MXN
		                        @endif
		                    </td>
		                @endforeach
		            </tr>
		        @endfor
		    </tbody>
		</table>
	</div>

@endsection
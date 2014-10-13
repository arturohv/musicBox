@extends('layouts.default')
<?php View::share('titulo', 'Resultado');    
?>
@section('content')
<h2>Resultado del proceso:</h2>
<br/>
<table class="table table-striped table-bordered">
    <tr>
        <th>Nombre de Archivo</th>
        <th>Ruta</th>        
        <th>Acciones</th>
    </tr>

    @foreach($resultados as $r)
        <tr>
            <td>{{ $r->archivo }}</td>   
            <td>{{ $r->ruta }}</td>      
                
            <td>
                Descarga
            </td>   
        </tr>
    @endforeach
</table>

@stop
@extends('layouts.default')
<?php View::share('titulo');?>
@section('content')

<table id="tabla">
    <tr>
        <th>Id</th>
        <th>Placa</th>
        <th>Color</th>
        <th>Acciones</th>
    </tr>
</table>

<script type="text/javascript">
    alert("Prueba de cargar_tabla");
    $(document).ready(function() {
        cargar_tabla();
        
    });

    function cargar_tabla() {
        $.getJSON('avionesjs', function(json, textStatus) {
            for (var i = 0; i < json.length; i++) {
                var row = '<tr>';
                row += '<td>' + json[i].id + '</td>';
                row += '<td>' + json[i].filename + '</td>';
                
                row += '</tr>';
                $('#tabla tr:last').after(row);
            };
            
    }
</script>


@stop
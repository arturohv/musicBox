@extends('layouts.default')
<?php View::share('titulo');?>
@section('content')



<table class= "table" id="tabla">
    <tr>
        <th>Id</th>
        <th>Placa</th>
        <th>Acciones</th>
    </tr>

</table>

<script type="text/javascript">    

    $(document).ready(function() {
        cargar_tabla();
    });

    setInterval(function() {
      cargar_tabla();            
    }, 2000);

    function cargar_tabla() {        
       $.getJSON('results', function(json,  textStatus) {
        
            for (var i = 0; i < json.length; i++) {                
                var row = '<tr>';
                row += '<td>' + json[i].archivo + '</td>';
                row += '<td>' + json[i].ruta + '</td>';                
                row += '</tr>';
                $('#tabla tr:last').after(row);
            };            
        });            
    }
</script>


@stop
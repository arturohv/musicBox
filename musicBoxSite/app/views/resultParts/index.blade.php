@extends('layouts.default')
<?php View::share('titulo');?>
@section('content')



<table class= "table" id="tabla">
    <thead>
        <tr>
            <th>Archivo</th>
            <th>Descarga</th>            
        </tr>
    </thead>
    <tbody>
        <tr></tr>
    </tbody>
 <tr></tr>
</table>

<script type="text/javascript">    
var interval = null;

    $(document).on('ready',function() {
        cargar_tabla();
    });

    interval = setInterval(function() {
      cargar_tabla();            
    }, 1000);

    function cargar_tabla() {        
       $.getJSON('results', function(json,  textStatus) {
            $("#tabla tbody tr").remove();
          
            $('#tabla tbody').append('<tr></tr>');
            for (var i = 0; i < json.length; i++) {  
                              
                var row = '<tr>';
                row += '<td>' + json[i].archivo + '</td>';
                /*row += '<td><a href="/results/' + json[i].id + '">Parte ' + (i + 1) + '</a></td>';*/
                row += '<td><a href='+ json[i].ruta + ' download>Parte ' + (i + 1) + '</a></td>';             
                row += '</tr>';
                $('#tabla tbody tr:last').after(row);

            };            
        });            
    }
</script>


@stop
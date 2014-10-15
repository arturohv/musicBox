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
    //Asigna el id del archivo que se esta procesado
    id = '<?php echo $idResult ;?>';


    $(document).ready(function() {
        cargar_tabla();
        
    });

    function cargar_tabla() {        
       $.getJSON('results', function(json, id, textStatus) {
            for (var i = 0; i < json.length; i++) {                
                var row = '<tr>';
                row += '<td>' + json[i].id + '</td>';
                row += '<td>' + json[i].filename + '</td>';                
                row += '</tr>';
                $('#tabla tr:last').after(row);
            };            
        });            
    }
</script>


@stop
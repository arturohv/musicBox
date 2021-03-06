@extends('layouts.default')
<?php View::share('titulo', 'Mensaje de error'); ?>
@section('content')
<div class="row">
	<div class="col-md-8 col-md-offset-2">
		<h2>Mensaje de Error</h2>
			<div class="panel panel-default ">
				<div class="panel-heading"><h4><b>Posibles causas:</b></h4></div>
		                <div class="panel-body">
		                	<ul>
		                		<li>No se ha seleccionado ningún archivo.</li>
		                		<li>El archivo seleccionado tiene su metadata incompleto</li>
		                		<li>El tamaño de división digitado debe ser superior a 0</li>
		                		<li>Solo se permite archivos con extensión mp3</li>
		                		<li>{{link_to("uploads/create", 'Reintentar', $attributes = array(), $secure = null);}}</li>
		                	</ul>
		                </div>
		        </div>        
			</div>
	</div>
</div>
	
@stop
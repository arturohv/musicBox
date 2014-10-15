@extends('layouts.default')
<?php View::share('titulo', 'Subir Archivo'); ?>
@section('content')

{{ Form::open(array('action' => 'UploadController@uploadFile','files' => true)) }}

<div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default ">
                <div class="panel-heading"><h4><b>Subir Archivo</b></h4></div>
                <div class="panel-body">
                    <br />             
                    <div class="form-horizontal">
						<div class="form-group">
							{{ Form::label('file', 'Ruta del Archivo:', array('class' => 'control-label col-md-3')) }}                 
                            <div class="col-md-7">
                            	{{ Form::file('file', '', array('class' => 'form-control input-sm')) }}	                           
                            </div>
                        </div>                                      

                        <div class="form-group">
                        	{{ Form::label('splitBy', 'Modo de División:', array('class' => 'control-label col-md-3')) }}
 							<div class="btn-group col-md-7" >
							    <label class="btn btn-default **active**">						       
							       {{ Form::radio('tipoMod', 'bySize', true) }}
							    Tamaño </label>
							    <label class="btn btn-default">						       
							       {{ Form::radio('tipoMod', 'byTime', false) }}
							    Tiempo </label>						    
							</div>                       		
                        </div>                  

                        <div class="form-group">
							{{ Form::label('valor', 'Valor:', array('class' => 'control-label col-md-3')) }}                 
                            <div class="col-md-7">
                            	{{ Form::number('valor', '', array('class' => 'form-control input-sm')) }}	                           
                            </div>
                        </div>                
                    </div>

                </div>

                <div class="panel-footer text-center">                    
                    {{Form::submit('Subir', array('class' => 'btn btn-primary min-width-100'))}}
				</div>

            </div>

        </div>

    </div>



{{ Form::close() }}
@stop
<?php

class UploadController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{		
		$this->layout->nest('content', 'uploads.create', array());		
	}

	public function uploadFile()
	{
		$file = Input::file('file');
		$modeType = Input::get('tipoMod');
		$valor = Input::get('valor');

		$parts = 0;
		$timePerChunk = 0;

		$destinationPath = 'uploads/originals';		
		$filename = $file->getClientOriginalName();
		//$extension =$file->getClientOriginalExtension();
		$uploadSuccess = Input::file('file')->move($destinationPath, $filename);
		 
		if( $uploadSuccess ) {
			$fileurl = $destinationPath . "/" . $filename;
			if ($modeType == 'bySize') {
				$parts = $valor; 
			} else {
				$timePerChunk = $valor;
			}
			//Guarda el registro en base de datos
			$this->store($filename,$fileurl,$parts,$timePerChunk);	
		   //return Response::json('success', 200); // or do a redirect with some message that file was uploaded
		} else {
		   return Response::json('error', 400);
		}
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store($pFileName, $pFileUrl, $pParts, $timePerChunk)
	{		
		
		$upload = new Upload();
		$upload->filename = $pFileName;
		$upload->fileurl = $pFileUrl;
		$upload->parts = $pParts;
		$upload->time_per_chunk = $timePerChunk;
		$upload->save();
		//Arma la cadena en formato Json
		$jsonString = '{"id":"'.$upload->id.'","file":"'.$upload->fileurl.'","parts":"'.$upload->parts.'","time_per_chunk":"'.$upload->time_per_chunk.'"}';				
		//Envia el mensaje al servidor de colas
		Queue::push('laravel', array('message' => $jsonString));
		$this->layout->nest('content', 'uploads.create', array());		
	}



	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


}

<?php



include_once('../workerMQ/mp3Lib.php');

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

	public function validarMeta($ruta){
		$valido = 1;
		
		$duracionSeg = 0;
		$f = $ruta;
		$m = new mp3file($f);
		$a = $m->get_metadata();
		foreach ($a as $key => $value) {
			if ($key == 'Length') {		
				$duracionSeg = $value;						
			} 
		}

		if ($duracionSeg <= 0) {
			$valido = 0;
		}

		return $valido;
	}

	public function validar($ext, $size){
		$valido = 1;
		if($ext!=="mp3"){
			$valido = 0;
		}

		if ($size <= 0) {
			$valido = 0;
		}		




		return $valido;
	}

	public function uploadFile()
	{
		$file = Input::file('file');

		if ($file != NULL) {			
			$modeType = Input::get('tipoMod');
			$valor = Input::get('valor');

			$parts = 0;
			$timePerChunk = 0;

			$destinationPath = public_path() . '/uploads/originals';		
			$filename = $file->getClientOriginalName();
			$extension =$file->getClientOriginalExtension();		

			if($this->validar($extension, $valor) == 1){
				$uploadSuccess = Input::file('file')->move($destinationPath, $filename);
				
				if( $uploadSuccess ) {
					$fileurl = $destinationPath . "/" . $filename;

					if ($this->validarMeta($fileurl) == 1) {									
						//Renombrar el archivo con uno temporal
						$newName = uniqid().'.mp3';			
						rename($fileurl, $destinationPath.'/'.$newName);
						$fileurl = $destinationPath . "/" . $newName;
						if ($modeType == 'bySize') {
							$parts = $valor; 
						} else {
							$timePerChunk = $valor;
						}
						//Guarda el registro en base de datos
						$id = $this->store($filename,$fileurl,$parts,$timePerChunk);
						//Redirige a la pagina de resultados
						return Redirect::to('results')->with('message',$id);			
					} else {
						return Redirect::to('/error');
					}
					
				} else {
				   return Response::json('error', 400);
				} 
			} else {
				
					return Redirect::to('/error');
			}
		} else {
			return Redirect::to('/error');
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
		return $upload->id;			
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


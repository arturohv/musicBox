<?php

class ResultPartsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	
	

	public function index()
	{	
		
		$id = Session::get('message');				
		/*$this->layout->titulo = 'Resultados';
		$resultados  = ResultParts::GetResultado($id);		
		
		$this->layout->nest(
			'content',
			'resultParts.index',
			array(
				'resultados' => $resultados
			)
		);*/

	if (Request::ajax())
		{			
    		$resultados = ResultParts::GetResultado($id);
    		return Response::Json($resultados);
		}
		$this->layout->titulo = 'Resultados';
		$this->layout->nest(
			'content',
			'resultParts.index',
			array(
				'idResult' => $id
			)
		);


	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
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

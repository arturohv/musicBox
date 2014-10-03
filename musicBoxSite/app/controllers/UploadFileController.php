<?php
class UploadFileController extends AuthorizedController {
	public function postFile()
	{
		$file = Input::file('file'); 

		$destinationPath = 'uploads/';
		$filename = $file->getClientOriginalName();
		//$extension =$file->getClientOriginalExtension(); //if you need extension of the file
		$uploadSuccess = Input::file('file')->move($destinationPath, $filename);
		 
		if( $uploadSuccess ) {
		   return Response::json('success', 200); // or do a redirect with some message that file was uploaded
		} else {
		   return Response::json('error', 400);
		}
	}
}
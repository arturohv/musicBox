<?php 
	/**
	* 
	*/
	class ResultParts extends Eloquent
	{
		protected $table = 'result_parts';
		protected $fillable = array('upload_id','filename','fileurl');
		protected $guarded = array('id');
		public $timestamps = false;
		
	}
 ?>
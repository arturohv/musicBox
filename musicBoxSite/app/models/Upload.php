<?php 
	/**
	* 
	*/
	class Upload extends Eloquent
	{
		protected $table = 'upload';
		protected $fillable = array('filename','fileurl','parts','time_per_chunk');
		protected $guarded = array('id');
		public $timestamps = false;
		
	}
 ?>
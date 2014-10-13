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

		public static function GetResultado($idMedia)
		{
			$sql = 'select u.filename as archivo, rp.fileurl as ruta
					from result_parts rp
					inner join upload u on u.id = rp.upload_id
					where u.id = '. $idMedia;

			return DB::select($sql);
		}
		
	}
 ?>
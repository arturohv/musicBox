<?php
namespace workerMQ{
	use AMQPConnection;
	use AMQPChannel;
	use AMQPQueue;

	include_once('mp3Lib.php');
	include_once('PgSQL.php');
	
	class ampq {

		public function __construct(){

		}
	
	   public function amqp_connection() {
		    $amqpConnection = new AMQPConnection();
		    $amqpConnection->setLogin("guest");
		    $amqpConnection->setPassword("guest");
		    $amqpConnection->setHost("localhost");
			$amqpConnection->setPort("5672");
		    $amqpConnection->setVhost("/");
		    $amqpConnection->connect();

		    if(!$amqpConnection->isConnected()) {
		        die("Cannot connect to the broker, exiting !\n");
		    }
		    return $amqpConnection;
		}

		//Se conecta y recibe todos los mensajes pendientes
		public function amqp_receive($exchangeName, $routingKey, $queueName) 
		{
		    $amqpConnection = $this->amqp_connection();
		    $channel = new AMQPChannel($amqpConnection);
		    $queue = new AMQPQueue($channel);
		    $queue->setName($queueName);
		    $queue->bind($exchangeName, $routingKey);

		    while($message = $queue->get()) {
		      	        
		        $queue->ack($message->getDeliveryTag());
		        $media = $this->ampq_process_msg($message);
		        $this->ffmpeg_process($media);
		        
		    }

		    if(!$amqpConnection->disconnect()) {
		        throw new Exception("Could not disconnect !");
		    }
		}

		public function ampq_process_msg($message){		
			
			$obj = $message->getBody();

			$phpArray = json_decode($obj, true);			
			foreach ($phpArray as $key => $value) {
			  	
			  	if ($key == 'data') 
			    {			    	
				    foreach ($value as $k => $v) 
				    {				    					      	
				    	$msg = json_decode($v, true);

				    	break;
					}
					break;								    	
			    }	    
			}
			
			return $msg;			
		}

		public function getIntervalo($tSeg, $tParts, $tValue){
			$partSize = $tSeg / $tParts;
			//Obtine Horas
			$iHour = $partSize / 3600;
			//Obtiene minutos
			$iMin = $iHour - intval($iHour);
			$iMin = $iMin * 60;
			//Obtiene Segundos
			$iSeg = $iMin - intval($iMin);
			$iSeg = $iSeg * 60;

			if ($tValue == 'hh') {
				return intval($iHour);
			} elseif ($tValue == 'mm') {
				return intval($iMin); 
			} else {
				return $iSeg;
			}
		}

		public function getParts($tSeg, $tTime){
			$tTime = intval($tTime);
			if ($tSeg >= ($tTime * 60)) {
			 	return $tSeg / ($tTime * 30);
			 } else {
			 	return 0;
			 }
		}

		public function insertResult($fileUrl){
			$oPgl = new PgSQL();
			$oPgl->connect('localhost','5432','arcanna', 'arturohv','bastos'); 

			$qs = "select max(id) as last_id from upload;";
			$res = $oPgl->select($qs);

			foreach ($res as $key => $value) {
				if ($key == 'last_id') {
					foreach ($value as $k1 => $v) {
						$last_id = $v;
					}

				}
			}

			$qs = "insert into result_parts (upload_id, filename, fileurl) values ({$last_id}, '{$fileUrl}', '{$fileUrl}');";
			
			$oPgl->sql_add($qs);
			$oPgl->transaction();
			//$oPgl->close();
		}


		public function ffmpeg_split($file, $iIni, $iFin, $outName){
			$comm = "ffmpeg -acodec copy -i ".$file." -ss ".$iIni." -t ".$iFin." ".$outName;
			//echo "$comm\n";
			echo shell_exec($comm);
			$this->insertResult($outName);


			//ffmpeg -acodec copy -i input.mp3 -ss 00:00:25 -t 00:02:00 output.wav 	
			//echo shell_exec("ffmpeg -i input.avi output.avi &");

		}

		public function formatTime($pHour, $pMin, $pSec){
			
			$sHour = str_pad($pHour, 2, "0", STR_PAD_LEFT);
			$sMin = str_pad($pMin, 2, "0", STR_PAD_LEFT);
			$pSec = intval($pSec); 
			$sSec = str_pad($pSec, 2, "0", STR_PAD_LEFT);
			//echo "$sHour:$sMin:$sSec\n";
			return "$sHour:$sMin:$sSec";
		}

	
		public function ffmpeg_process($media){
			
			$filename = "";
			$duracionSeg = 0;
			$pTime = 0;

			foreach ($media as $key => $value) {
				if ($key == 'file') {
					$pFile = $value;
					$filename = "../musicBoxSite/".$pFile;					
				}

				if ($key == 'id') {
					$pId = $value;					
				}

				if ($key == 'parts') {
					$pParts = $value;					
				}

				if ($key == 'time_per_chunk') {
					$pTime = $value;					
				}			
			}
		   $existeArchivo = file_exists($filename);

		   //Valida que exista el archivo en la ubicacion actual
		   if ($existeArchivo == 1) {
		    	//Obtener Tamaño en bytes del archivo que se va a procesar
		    	$fSize = filesize($filename) . ' bytes';
		    	//Obtener la duración
		    	$f = $filename;
				$m = new mp3file($f);
				$a = $m->get_metadata();
				foreach ($a as $key => $value) {
					if ($key == 'Length') {
						
						$duracionSeg = $value;						
					} 
				}

				//echo "$duracionSeg \n";

				if ($duracionSeg > 0) {
					//Por tamaño caso contrario por tiempo
			    	if ($pParts > 0) {
			    			//Obtener el tamaño de cada parte segun el tamaño
			    			//del archivo. 
			    			$iHour = $this->getIntervalo($duracionSeg,$pParts,'hh');
			    			$iMin = $this->getIntervalo($duracionSeg,$pParts,'mm');
			    			$iSec = $this->getIntervalo($duracionSeg,$pParts,'ss');		    			

			    			
			    			$invHour = $iHour;
			    			$invMin = $iMin;
			    			$invSeg = $iSec;
			    			for ($i=1; $i <=$pParts ; $i++) { 
			    				//echo "$invHour:$invMin:$invSeg\n";

			    				if ($i == 1) {
			    					//echo "00:00:00 -> $invHour:$invMin:$invSeg\n";
			    					$this->ffmpeg_split($filename, '00:00:00', $this->formatTime($invHour,$invMin,$invSeg), '../musicBoxSite/uploads/parts/'.'file'.$pId.$i.'.mp3');
			    				} else{
			    					$bInvHour = $invHour;
			    					$bInvMin = $invMin;
			    					$bInvSeg =$invSeg;

			    					//Incrementa Horas, minutos y segundos
				    				$invHour = $invHour + $iHour;	
				    				$invMin = $invMin + $iMin;
				    				if ($invMin > 60) {			    					
				    					while ($invMin > 60) {
				    						$invMin = $invMin - 60;
				    						$invHour = $invHour + 1;
				    					}
				    				}

				    				$invSeg = $invSeg + $iSec;
				    				if ($invSeg > 60) {			    					
				    					while ($invSeg > 60) {
				    						$invSeg = $invSeg - 60;
				    						$invMin = $invMin + 1;
				    					}
				    				}
				    				//********************************* 

				    				//echo "$bInvHour:$bInvMin:$bInvSeg -> $invHour:$invMin:$invSeg\n";
				    				$this->ffmpeg_split($filename, $this->formatTime($bInvHour,$bInvMin,$bInvSeg), $this->formatTime($iHour,$iMin,$iSec), '../musicBoxSite/uploads/parts/'.'file'.$pId.$i.'.mp3');
			    				}			
			    				
			    			}

			    		} else {
			    			//Tiempo
			    			$parts = $this->getParts($duracionSeg,$pTime);
			    			$parts = intval($parts);
			    			
			    			$iHour = $pTime / 60;			    					    			
			    			$iMin = $iHour - intval($iHour);			    			
			    			$iMin = $iMin * 60;		    			
			    			$invHour = intval($iHour);
			    			$invMin = $iMin;
			    			

			    			for ($i=1; $i <=$parts ; $i++) { 
			    				if ($i == 1) {
			    					$this->ffmpeg_split($filename, '00:00:00', $this->formatTime($invHour,$invMin,'0'), '../musicBoxSite/uploads/parts/'.'file'.$pId.$i.'.mp3');
			    				} else {

			    					$bInvHour = $invHour;
			    					$bInvMin = $invMin;
			    					$bInvSeg =0;

			    					//Incrementa Horas, minutos y segundos
				    				$invHour = $invHour + intval($iHour);
				    				$invMin = $invMin + $iMin;
				    				if ($invMin > 60) {			    					
				    					while ($invMin > 60) {
				    						$invMin = $invMin - 60;
				    						$invHour = $invHour + 1;
				    					}
				    				}

				    				$this->ffmpeg_split($filename, $this->formatTime($bInvHour,$bInvMin,$bInvSeg), $this->formatTime(intval($iHour),$iMin,'0'), '../musicBoxSite/uploads/parts/'.'file'.$pId.$i.'.mp3');
			    				}
			    			}
			    		}	
				}	

		    		
					

					


		   } 	
			    
			
		}


	}

}






?>
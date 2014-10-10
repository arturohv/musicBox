<?php
namespace workerMQ{
	use AMQPConnection;
	use AMQPChannel;
	use AMQPQueue;

	include_once('mp3Lib.php');
	
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
		        /*echo("Message #".$message->getDeliveryTag()." '".$message->getBody()."'");

		        if($message->isRedelivery()) {
		            echo("\t(this message has already been delivered)");
		        }
		        // just for testing purpose, shows how to manually remove a message from queue
		        if(rand(0,6) > 4) {
		            
		            echo("\t(this message has been removed from the queue)");
		        }
		        print_r($message->getMessageId());
		        echo "\n";*/
		        
		        $media = $this->ampq_process_msg($message);
		        $this->ffmpeg_process($media);
		        //$queue->ack($message->getDeliveryTag());
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

		
	
		public function ffmpeg_process($media){
			
			$filename = "";


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
		    	/*$fSize = filesize($filename) . ' bytes';	
		    	//Por tamaño caso contrario por tiempo
		    	if ($pParts > 0) {
		    		# code...
		    			echo "partes \n";
		    		} else {
		    			echo "tamaño \n";
		    		}	*/

		    		$f = $filename;
					$m = new mp3file($f);
					$a = $m->get_metadata();
					print_r($a);
		   } 	
			    
			
		}


	}






}
?>
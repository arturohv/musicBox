<?php
	namespace workerMQ{

	include_once('amqp.php');

	$oAmqp = new ampq();

	//$test = $oAmqp->amqp_connection();

	$oAmqp->amqp_receive("laravel","laravel","laravel");

	/*$oAmqp = new Amqp();
	$oAmqp->amqp_connect();
	//$oAmqp->disconnect()
	echo "Escuchando... \n";

	//$channel = $oAmqp->channel();
	$channel = $oAmqp->channel();
	$channel = new AMQPChannel($oAmqp);

	$channel->queue_declare('laravel',false,false,false,false);
	echo "Esperando Mensajes (Crtl + C para detener escucha.) \n";


	$callback = function($msg) {
	  echo " [x] Received ", $msg->body, "\n";
	  $json =  json_decode($msg->body , true);

	  foreach ($json as $key => $value){
	    if($key == "id")
	        $pId = $value;
	    if($key == "file")
	        $pFile = $value;
	    if($key == "parts")
	        $pParts = $value;
	    if($key == "time_per_chunk")
	        $pTime = $value;
	  }
    
  //echo "$from_file $to_file $id \n";


};

$channel->basic_consume('laravel', '', false, true, false, false, $callback);

while(count($channel->callbacks)) {
    $channel->wait();
}

$channel->close();
$oAmqp->close();
	/*do {
		echo 'prueba ciclo';
	} while ( 1==1);*/

	}
?>
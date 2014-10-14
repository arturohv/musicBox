<?php
	namespace workerMQ{

	include_once('amqp.php');
	$oAmqp = new ampq();

	

	//$oAmqp->amqp_receive("laravel","laravel","laravel");

	do {
		$oAmqp->amqp_receive("laravel","laravel","laravel");
		sleep(1);
		echo "Escuchando...\n";		
	} while (1==1);

	}
?>
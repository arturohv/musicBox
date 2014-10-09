<?php
	//use AMQPConnection;

class Amqp {

	/*public function amqp_connect() 
	{
	    $amqpConnection = new AMQPConnection();
	    $amqpConnection->setLogin("guest");
	    $amqpConnection->setPassword("guest");
	    $amqpConnection->setHost("localhost");
	    $amqpConnection->setPort("5672");
	    $amqpConnection->setVhost("/");
	    
	    echo "Conectando... \n";


	    $amqpConnection->connect();

	    if(!$amqpConnection->isConnected()) {
	        die("Cannot connect to the broker, exiting !\n");
	    } else {
	    	echo "Conectado correctamente al servidor Rabbit...\n";
	    }
	    return $amqpConnection;
    }*/
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

public function amqp_receive($exchangeName, $routingKey, $queueName) 
{
    $amqpConnection = amqp_connection();
    $channel = new AMQPChannel($amqpConnection);
    $queue = new AMQPQueue($channel);
    $queue->setName($queueName);
    $queue->bind($exchangeName, $routingKey);

    while($message = $queue->get()) {
        echo("Message #".$message->getDeliveryTag()." '".$message->getBody()."'");

        if($message->isRedelivery()) {
            echo("\t(this message has already been delivered)");
        }
        // just for testing purpose, shows how to manually remove a message from queue
        if(rand(0,6) > 4) {
            $queue->ack($message->getDeliveryTag());
            echo("\t(this message has been removed from the queue)");
        }
        print_r($message->getMessageId());
        echo "\n";
    }

    if(!$amqpConnection->disconnect()) {
        throw new Exception("Could not disconnect !");
    }
}





    /*public static function amqp_receive($exchangeName, $routingKey, $queueName) {
	    $amqpConnection = amqp_connection();

	    $channel = new AMQPChannel($amqpConnection);
	    $queue = new AMQPQueue($channel);
	    $queue->setName($queueName);
	    $queue->bind($exchangeName, $routingKey);

	    while($message = $queue->get()) {
	        echo("Message #".$message->getDeliveryTag()." '".$message->getBody()."'");

	        if($message->isRedelivery()) {
	            echo("\t(this message has already been delivered)");
	        }
	        // just for testing purpose, shows how to manually remove a message from queue
	        if(rand(0,6) > 4) {
	            $queue->ack($message->getDeliveryTag());
	            echo("\t(this message has been removed from the queue)");
	        }
	        print_r($message->getMessageId());
	        echo "\n";
	    }

	    if(!$amqpConnection->disconnect()) {
	        throw new Exception("Could not disconnect !");
	    }
	}*/
}
?>
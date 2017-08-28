<?php

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface {

    protected $clients;

    public function getClients() {
        return null;
    }

    public function sayHello() {
        foreach ($this->clients as $client) {
            $client->send('{
                "user": "Bot",
                "text": "Hello world !",
                "time": "' . gmdate('Y-m-d H:i:s') . '"
            }');
        }
    }

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        echo "Chat instanciated...\n";
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId}) occured...\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        foreach ($this->clients as $client) {
            $client->send($msg);
            echo "Sending : {$msg}\n";
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected...\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }

}

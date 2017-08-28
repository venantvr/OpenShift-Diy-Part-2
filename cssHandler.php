<?php

use Ratchet\ConnectionInterface;
use Ratchet\Http\HttpServerInterface;
use Guzzle\Http\Message\RequestInterface;
use Guzzle\Http\Message\Response;

class CssHandler implements HttpServerInterface {

    protected $response;

    function __construct($folder) {
        $this->folder = $folder;
    }

    public function onOpen(ConnectionInterface $conn, RequestInterface $request = null) {

        $this->response = new Response(200, [
            'Content-Type' => 'text/css; charset=utf-8',
        ]);

        echo $this->folder;

        $content = file_get_contents($this->folder . 'diy/css/style.css');

        $this->response->setBody($content);

        $this->close($conn);
    }

    public function onClose(ConnectionInterface $conn) {

    }

    public function onError(ConnectionInterface $conn, \Exception $e) {

    }

    public function onMessage(ConnectionInterface $from, $msg) {

    }

    protected function close(ConnectionInterface $conn) {
        $conn->send($this->response);
        $conn->close();
    }

}

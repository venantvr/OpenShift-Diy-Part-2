<?php

require __DIR__ . '/vendor/autoload.php';

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Ratchet\Http\Router;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\UrlMatcher;

require 'chat.php';
require 'webPage.php';
require 'jsHandler.php';
require 'cssHandler.php';

$host   = '127.0.0.1';
$folder = '.';

if (isset($argv) == true && isset($argv[1])) {
    $host = $argv[1];
}

if (isset($argv) == true && isset($argv[2])) {
    $folder = $argv[2];
}

if (isset($host) == false) {
    $host = "localhost";
}

echo 'Listening on : ' . $host . "\r\n";
echo '1.0.9' . "\r\n";

$chat       = new Chat();
$webpage    = new WebPage($folder);
$jsHandler  = new JsHandler($folder);
$cssHandler = new CssHandler($folder);

$routes = new RouteCollection();
$routes->add('chat', new Route('/chat', array('_controller' => new WsServer($chat), 'allowedOrigins' => '*')));
$routes->add('webpage', new Route('/webpage', array('_controller' => $webpage, 'allowedOrigins' => '*')));
$routes->add('js/main.js', new Route('/js/main.js', array('_controller' => $jsHandler, 'allowedOrigins' => '*')));
$routes->add('css/style.css', new Route('/css/style.css', array('_controller' => $cssHandler, 'allowedOrigins' => '*')));

$router = new Router(new UrlMatcher($routes, new RequestContext));

$server = IoServer::factory(new HttpServer($router), 8080, $host);
$server->run();


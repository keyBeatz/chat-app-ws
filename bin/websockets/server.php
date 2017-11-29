<?php

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use ChatApp\WebSockets\Dispatcher;

$container = require __DIR__ . '/../../app/bootstrap.php';
require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/Dispatcher.php';

$controller = $container->getByType( 'ChatApp\WebSockets\Controller' );
$clientCollection = $container->getByType( 'ChatApp\WebSockets\ClientCollection' );

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new Dispatcher( $controller, $clientCollection )
        )
    ),
    8080
);
$controller->injectLoop( $server->loop )->startLoops();

$server->run();
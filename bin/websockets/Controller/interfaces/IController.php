<?php

namespace ChatApp\WebSockets;

use Ratchet\ConnectionInterface;

/**
 * Interface IController
 * @package ChatApp\WebSockets
 */
interface IController {
    public function call( IClientConnection $from, array $data );
    public function sendResponse( IClientConnection $client, $data, $attachActionName = true );
}

interface IControllerLoop extends IControllerClients {
    public function getLoop();
    public function startLoops();
}

interface IControllerClients {
    public function getClients();
}


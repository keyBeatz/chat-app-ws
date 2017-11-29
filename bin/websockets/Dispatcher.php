<?php

namespace ChatApp\WebSockets;

use ChatApp\WebSockets\Exceptions\ControllerBadInterface;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

/**
 * Class Dispatcher
 * @package ChatApp\WebSockets
 */
class Dispatcher implements MessageComponentInterface {

    protected $clientCollection;

    /**
     * @var Controller
     */
    protected $controller;

    /**
     * Dispatcher constructor.
     *
     * @param Controller $controller
     * @param ClientCollection $clientCollection
     */
    public function __construct( Controller $controller, ClientCollection $clientCollection ) {
        $this->clientCollection = $clientCollection;
        $this->controller       = $controller;

        $this->setControllerDependencies();
    }

    /**
     * @throws ControllerBadInterface
     */
    protected function setControllerDependencies() {
        if( !$this->controller instanceof IController )
            throw new ControllerBadInterface( "Injected controller must be instance of AllStars\WebSockets\IController" );

        $this->controller->injectClients( $this->clientCollection );
    }

    /**
     * @param ConnectionInterface $connection
     */
    public function onOpen( ConnectionInterface $connection ) {
        // Store the new connection to send messages to later
        $client = $this->clientCollection->addClient( $connection );
        $this->sendInitMessage( $client );

        echo count($this->clientCollection);

        echo "New connection! ({$connection->resourceId})\n";
    }

    /**
     * @param ConnectionInterface $connection
     * @param string $msg
     */
    public function onMessage( ConnectionInterface $connection, $msg ) {
        $data = json_decode( $msg, true );
        $client = $this->clientCollection->getClientByConnection( $connection );
        // route message to controller
        $this->controller->call( $client, $data );
    }

    /**
     * @param ConnectionInterface $connection
     */
    public function onClose( ConnectionInterface $connection ) {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clientCollection->removeClient( $connection, ClientCollection::MATCH_CLIENT_BY_CONNECTION );

        echo "Connection {$connection->resourceId} has disconnected\n";
    }

    /**
     * @param ConnectionInterface $connection
     * @param \Exception $e
     */
    public function onError( ConnectionInterface $connection, \Exception $e ) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $connection->close();
    }

    /**
     * @param IClientConnection $client
     */
    protected function sendInitMessage( IClientConnection $client ) : void {
        $this->controller->sendResponse( $client, [
            'action' => 'init',
            'data' => [
                'resource_id' => $client->getResourceId()
            ]
        ]);
    }
}
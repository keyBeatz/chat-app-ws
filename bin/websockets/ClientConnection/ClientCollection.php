<?php

namespace ChatApp\WebSockets;

use ChatApp\WebSockets\Exceptions\ControllerRuntimeException;
use ChatApp\WebSockets\Exceptions\ClientNoResourceIdException;
use Ratchet\ConnectionInterface;
use React\EventLoop\LoopInterface;

/**
 * Class ClientCollection
 * @package AllStars\WebSockets
 */
class ClientCollection implements \Countable {

    const MATCH_CLIENT_BY_CONNECTION = "connection";
    const MATCH_CLIENT_USER_ID = "user_id";
    const MATCH_CLIENT_RESOURCE_ID = "resource_id";

    private $clients = [];

    public function addClient( ConnectionInterface $connection, int $userId = 0 ) : ?IClientConnection {
        try {
            // TODO: handling multiple connections with same id
            $client          = new Client( $connection, $userId );
            $this->clients[] = $client;

            return $client;
        }
        catch( ClientNoResourceIdException $e ) {
            // TODO
            return null;
        }
    }

    public function removeClient( $byWhat, string $flag = "connection" ) : void {
        $client = null;

        if( $flag === "connection" ) {
            if( $byWhat instanceof ConnectionInterface ) {
                $client = $this->getClientByConnection( $byWhat );
            }
        }
        else if( $flag === "user_id" ) {
            $userId = (int) $byWhat;
            if( is_int( $userId ) ) {
                $client = $this->getClientByUserId( $userId );
            }
        }
        else if( $flag === "resource_id" ) {
            $resourceId = (int) $byWhat;
            if( is_int( $resourceId ) ) {
                $client = $this->getClientByResourceId( $resourceId );
            }
        }

        if( $client !== null ) {
            $client->getConnection()->close();
            if( ( $key = array_search( $client, $this->clients ) ) !== false ) {
                unset( $this->clients[$key] );
            }
        }
    }

    public function getClientByConnection( ConnectionInterface $connection ) : ?IClientConnection {
        if( $connection instanceof IClientConnection )
            $users = array_filter( $this->clients, function( Client $user ) use( $connection ) { return $user === $connection; } );
        else
            $users = array_filter( $this->clients, function( Client $user ) use( $connection ) { return $user->getConnection() === $connection; } );

        return $users ? reset( $users ) : null;
    }

    public function getClientByUserId( int $userId ) : ?IClientConnection {
        $users = array_filter( $this->clients, function( Client $user ) use( $userId ) { return $user->getUserId() === $userId; } );
        return $users ? reset( $users ) : null;
    }

    public function getClientByResourceId( int $resourceId ) : ?IClientConnection {
        $users = array_filter( $this->clients, function( Client $user ) use( $resourceId ) { return $user->getResourceId() === $resourceId; } );
        return $users ? reset( $users ) : null;
    }

    public function getClients() : array {
        return $this->clients;
    }

    public function count() {
        return count( $this->clients );
    }

}

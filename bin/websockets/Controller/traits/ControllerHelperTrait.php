<?php

namespace ChatApp\WebSockets;

use ChatApp\WebSockets\Exceptions\ControllerRuntimeException;
use Ratchet\ConnectionInterface;
use React\EventLoop\LoopInterface;

/**
 * Trait ControllerHelperTrait
 * @package AllStars\WebSockets
 */
trait ControllerHelperTrait {

    /** @var  ClientCollection */
    private $clients;

    /** @var  LoopInterface */
    private $loop;

    /**
     * @param IClientConnection $client
     * @param array $data
     * @throws ControllerRuntimeException
     */
    public function call( IClientConnection $client, array $data ) : void {

        $action = $data['action'] ?? null;

        if( $action === null )
            throw new ControllerRuntimeException( "Action was not defined in " . __CLASS__ );

        if( !is_string( $action ) )
            throw new ControllerRuntimeException( "Action name must be string in " . __CLASS__ );

        $methodName = "action" . ucfirst( $action );

        if( !method_exists( $this, $methodName ) )
            throw new ControllerRuntimeException( "The method {$methodName} called in " . __CLASS__ . " does not exists" );

        call_user_func( [ $this, $methodName ], $client, $data );
    }

    /**
     * @param IClientConnection $client
     * @param $data
     * @param bool $attachActionName
     *
     * @return bool returns true on success
     */
    public function sendResponse( IClientConnection $client, $data, $attachActionName = true ) : bool {
        if( !$data )
            return false;

        if( is_array( $data ) ) {
            if( $attachActionName && !isset( $data['action'] ) ) {
                $trace = debug_backtrace();
                $caller = $trace[1] ?? [];
                $callerMethod = $caller['function'] ?? "";
                $action = "";
                if( $callerMethod )
                    $action = strtolower( preg_replace( "/^(action|loop)/", "", $callerMethod ) );

                echo "action: " . $action;

                if( $action )
                    $data['action'] = $action;
            }

            $client->send( json_encode( $data ) );
        }
        else if( is_string( $data ) )
            $client->send( $data );
        else if( is_numeric( $data ) || is_bool( $data ) )
            $client->send( (string) $data );
        else
            return false;

        return true;
    }

    /**
     * @return void
     */
    public function startLoops() : void {
        if( !$this->loop )
            return;

        $methods = get_class_methods( get_class( $this ) );

        if( !$methods )
            return;

        foreach( $methods as $method ) {
            // only method prefixed with "loop" can pass
            if( !method_exists( $this, $method ) || !preg_match( "/^loop/", $method ) )
                continue;

            $controller = $this;

            $timerInterval = static::TIMER_SEC ?? 3;

            $this->getLoop()->addPeriodicTimer( $timerInterval, function() use( $controller, $method ) {
                call_user_func( [ $controller, $method ], $this->getClients() );
            });
        }
    }

    /**
     * @param $clients
     */
    public function injectClients( ClientCollection $clients ) : void {
        $this->clients = $clients;
    }

    /**
     * @param $loop
     *
     * @return $this
     */
    public function injectLoop( $loop ) {
        $this->loop = $loop;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getClients() : ClientCollection {
        return $this->clients;
    }

    /**
     * @return LoopInterface
     */
    public function getLoop() : LoopInterface {
        return $this->loop;
    }
}

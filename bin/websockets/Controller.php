<?php

namespace ChatApp\WebSockets;

use Nette\Utils\Strings;
use Nette\Application\LinkGenerator;
use ChatApp\WebSockets\Exceptions\ControllerRuntimeException;
use Ratchet\ConnectionInterface;
use React\EventLoop\LoopInterface;

/**
 * Class Controller
 * @package AllStars\WebSockets
 */
class Controller implements IController, IControllerLoop {

    use ControllerHelperTrait;

    const TIMER_SEC = 3;



    public function __construct (

    ) {

    }

    /**
     * Example method. To create your own action, prefix your method with "action" prefix following by name of your Action (first letter uppercase).
     * When sending response via ControllerHelper trait (sendResponse method), it's not required to add action name in array to be send.
     * If sendResponse is called from action<Name> method and $data[action] is not provided, method will automatically set name of action from caller method.
     * @param IClientConnection $client
     * @param array $data
     */
    private function actionTest( IClientConnection $client, array $data ) {
        $userConnection = $this->clients->getClientByConnection( $client );

        $this->sendResponse( $client, [ "data" => count( $this->clients ), "user_id" => $userConnection->getUserId()] );
    }

    public function actionInit( IClientConnection $client, array $data ) {
        $userId = $data['data']['user_id'] ?? 0;

        if( $userId )
            $client->setUserId( $userId );

        $data['result'] = $userId;

        $dataSend = [
            'action' => 'initialized',
            'data' => [],
            'status' => "OK"
        ];

        $this->sendResponse( $client, $dataSend );
    }

    /**
     * Example method. To create you own loop, prefix your method with "loop" prefix following by name of your Action (first letter uppercase).
     * Responses have same behaviour as action methods.
     * @param $clientCollection
     */
    /*private function loopTest( ClientCollection $clientCollection ) {


        echo "loopTest inited\n";
        echo "Clients: " . count( $clientCollection->getClients() );
        foreach( $clientCollection->getClients() as $client ) {
            $this->sendResponse( $client, ["action"=>"test", "data" => ["test"=>"1111"]] );
        }
    }*/


}
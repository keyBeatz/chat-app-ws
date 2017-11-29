<?php

namespace ChatApp\WebSockets;

use Latte\Engine;
use Latte\Runtime\Template;
use Model\Command\ConversationCommand;
use Model\Query\ConversationQuery;
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
    /**
     * @var ConversationQuery
     */
    private $conversationQuery;
    /**
     * @var ConversationCommand
     */
    private $conversationCommand;


    public function __construct (
        ConversationQuery $conversationQuery,
        ConversationCommand $conversationCommand
    ) {

        $this->conversationQuery = $conversationQuery;
        $this->conversationCommand = $conversationCommand;
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

    public function actionSendMessage( IClientConnection $client, array $data ) {
        $userConnection = $this->clients->getClientByConnection( $client );
        $userId = $userConnection->getUserId();
        $conversationId = $data['data']['conversationId'] ?? null;
        $message = $data['data']['message'] ?? "";

        if( $userId && $conversationId && $message ) {
            var_dump($message);
            $this->conversationCommand->addMessage( $conversationId, $userId, $message );
        }
        print json_encode($data);


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

    private function loopReceiveMessage( ClientCollection $clientCollection ) {
        echo "loopTest inited\n";
        echo "Clients: " . count( $clientCollection->getClients() );
        foreach( $clientCollection->getClients() as $client ) {
            $currentUserId = $client->getUserId();
            $unreadConversations = $this->conversationQuery->getUserConversations( $currentUserId, [ 'unread' => 1 ] );
            if( count( $unreadConversations ) ) {
                $latte = new Engine();
                $output = [];
                foreach( $unreadConversations as $conversation ) {
                    $params = [
                        'conversation' => $conversation,
                        'currentUserId' => $currentUserId
                    ];
                    $output[$conversation->conversation->id] = $latte->renderToString( __DIR__ . '/../../app/components/chat/templates/ChatWindow.latte', $params );
                }

                $this->sendResponse( $client, [ "data" => $output ] );
            }
        }
    }


}
<?php

namespace App\Presenters;

use AllStars\Components\Panel\ChatPanelControl;
use AllStars\Components\Panel\IPanelCommentsControlFactory;
use AllStars\Components\RegionGame\IRegionGameFilterControlFactory;
use AllStars\Components\User\IRegisterControlFactory;
use AllStars\Components\Widget\ISearchControlFactory;
use AllStars\Components\RightPanelControl;
use LeanMapper\Connection;
use Model\Repository\UserRepository;
use Model\Repository\UserLogRepository;
use Nette;


class BasePresenter extends Nette\Application\UI\Presenter
{

    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @var $userId
     * @persistent
     */
    public $userId = 7;

    private $conversationId = null;

    public function __construct( ) {
        parent::__construct();
    }

    /**
     * @return null
     */
    public function getConversationId () {
        return $this->conversationId;
    }

    protected function beforeRender() {
        parent::beforeRender();
        //$this->userId = $this->getParameter( "id" ) ?? 7;
    }

    /**
     * @return int|null
     */
    public function getUserId(): ?int {
        return $this->userId;
    }

    public function injectConnection( Connection $connection ) {
        $connection->registerFilter('limit', array('Model\Filter\CommonFilter', 'limit'));
        $connection->registerFilter('orderBy', array('Model\Filter\CommonFilter', 'orderBy'));
        $connection->registerFilter('orderByAsc', array('Model\Filter\CommonFilter', 'orderByAsc'));
        $connection->registerFilter('orderByDesc', array('Model\Filter\CommonFilter', 'orderByDesc'));
        $connection->registerFilter('getLastItem', array('Model\Filter\CommonFilter', 'getLastItem'));
        $connection->registerFilter('type', array('Model\Filter\CommonFilter', 'type'));
        $connection->registerFilter('pagination', array('Model\Filter\CommonFilter', 'pagination'));
        $this->connection = $connection;
    }

    public function handleSwitchConversation( $conversationId ) {
        $this->conversationId = (int) $conversationId;
    }

}
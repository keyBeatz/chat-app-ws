<?php

namespace ChatApp\Components;

use App\Presenters\BasePresenter;
use Model\Query\ConversationQuery;
use Nette\Application\UI\Control;

class ChatControl extends Control {

    /**
     * @var IChatConversationControlFactory
     */
    private $chatConversationControl;
    /**
     * @var IChatWindowControlFactory
     */
    private $chatWindowsControl;
    /**
     * @var ConversationQuery
     */
    private $conversationQuery;

    private $userConversations;

    private $currentUserId;

    /**
     * ChatControl constructor.
     *
     * @param IChatConversationControlFactory $chatConversationControl
     * @param IChatWindowControlFactory $chatWindowsControl
     */
    function __construct(
        IChatConversationControlFactory $chatConversationControl,
        IChatWindowControlFactory $chatWindowsControl,
        ConversationQuery $conversationQuery
    ) {
        parent::__construct();

        $this->chatConversationControl = $chatConversationControl;
        $this->chatWindowsControl = $chatWindowsControl;
        $this->conversationQuery = $conversationQuery;
    }

    /**
     * @return ChatConversationControl
     */
    protected function createComponentConversations(): ChatConversationControl {
        return $this->chatConversationControl->create( $this->userConversations, $this->currentUserId );
    }

    /**
     * @return ChatWindowControl
     */
    protected function createComponentWindow(): ChatWindowControl {
        $conversation = null;
        if( count($this->userConversations) ) {
            $conversation = reset( $this->userConversations );
        }

        return $this->chatWindowsControl->create( $conversation, $this->currentUserId );
    }

    protected function attached( $presenter ) {
        $this->currentUserId = $presenter->getUserId();
        $this->userConversations = $this->conversationQuery->getUserConversations( $this->currentUserId );
    }

    /**
     * @return void
     */
    public function render(): void {

        $template = $this->createTemplate();

        $template->setFile( __DIR__ . '/templates/ChatControl.latte' );
        $template->render();
    }

}

interface IChatControlFactory {
    /**
     * @return ChatControl
     */
    public function create(): ChatControl;
}
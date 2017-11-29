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

    private $conversationId;
    /**
     * @var IChatSendFormFactory
     */
    private $sendFormFactory;

    /**
     * ChatControl constructor.
     *
     * @param IChatConversationControlFactory $chatConversationControl
     * @param IChatWindowControlFactory $chatWindowsControl
     */
    function __construct(
        IChatConversationControlFactory $chatConversationControl,
        IChatWindowControlFactory $chatWindowsControl,
        IChatSendFormFactory $sendFormFactory,
        ConversationQuery $conversationQuery
    ) {
        parent::__construct();

        $this->chatConversationControl = $chatConversationControl;
        $this->chatWindowsControl = $chatWindowsControl;
        $this->conversationQuery = $conversationQuery;
        $this->sendFormFactory = $sendFormFactory;
    }

    /**
     * @return ChatConversationControl
     */
    protected function createComponentConversations(): ChatConversationControl {
        return $this->chatConversationControl->create( $this->userConversations, $this->currentUserId, $this->conversationId );
    }

    /**
     * @return ChatWindowControl
     */
    protected function createComponentWindow(): ChatWindowControl {
        $conversation = null;

        if( count($this->userConversations) ) {
            // if conversationId is set then choose it as active. Otherwise choose first (the newest) as active
            if( $this->conversationId !== null ) {
                $defaultConversation = array_filter( $this->userConversations, function( $conv ) {
                    return $conv->conversation->id === (int) $this->conversationId;
                });
                $conversation = count( $defaultConversation ) ? reset( $defaultConversation ) : null;
            }
            if( $conversation === null ) {
                $conversation = reset( $this->userConversations );
            }
        }

        return $this->chatWindowsControl->create( $conversation, $this->currentUserId );
    }

    protected function createComponentSendForm(): ChatSendForm {
        return $this->sendFormFactory->create();
    }

    protected function attached( $presenter ) {
        $this->currentUserId = $presenter->getUserId();
        $this->conversationId = $presenter->getConversationId();
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

    public function handleSwitchConversation( $conversationId ) {
        bdump($conversationId);
    }

}

interface IChatControlFactory {
    /**
     * @return ChatControl
     */
    public function create(): ChatControl;
}
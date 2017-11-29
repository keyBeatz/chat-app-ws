<?php

namespace ChatApp\Components;

use Nette\Application\UI\Control;

class ChatConversationControl extends Control {

    /**
     * @var
     */
    private $userConversations;
    /**
     * @var int
     */
    private $currentUserId;
    /**
     * @var
     */
    private $activeConversationId;

    function __construct( $userConversations, int $currentUserId, $activeConversationId ) {
        parent::__construct();

        bdump($userConversations);
        $this->userConversations = $userConversations;
        $this->currentUserId = $currentUserId;
        $this->activeConversationId = $activeConversationId;
    }

    /**
     * @return void
     */
    public function render(): void {
        $template = $this->createTemplate();

        $template->conversations = $this->userConversations ? $this->userConversations : [];
        $template->currentUserId = $this->currentUserId;
        $template->activeConversationId = $this->activeConversationId;

        $template->setFile( __DIR__ . '/templates/ChatConversation.latte' );
        $template->render();
    }
}


interface IChatConversationControlFactory {
    /**
     * @return ChatConversationControl
     */
    public function create( $userConversations, int $currentUserId, $activeConversationId = null ): ChatConversationControl;
}
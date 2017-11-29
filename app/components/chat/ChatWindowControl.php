<?php

namespace ChatApp\Components;

use Model\Entity\ConversationMember;
use Nette\Application\UI\Control;

class ChatWindowControl extends Control {

    /**
     * @var ConversationMember
     */
    private $conversation;
    /**
     * @var int
     */
    private $currentUserId;

    function __construct(
        ConversationMember $conversation,
        int $currentUserId
    ) {
        parent::__construct();
        $this->conversation = $conversation;
        $this->currentUserId = $currentUserId;
    }

    /**
     * @return void
     */
    public function render(): void {
        $template = $this->createTemplate();

        $template->conversation = $this->conversation ? $this->conversation : [];
        $template->currentUserId = $this->currentUserId;

        $template->setFile( __DIR__ . '/templates/ChatWindow.latte' );
        $template->render();
    }

}


interface IChatWindowControlFactory {
    /**
     * @return ChatWindowControl
     */
    public function create( ConversationMember $conversation, int $currentUserId ): ChatWindowControl;
}
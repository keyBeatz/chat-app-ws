<?php

namespace ChatApp\Components;

use Nette\Application\UI\Control;

class ChatConversationControl extends Control {

    function __construct() {
        parent::__construct();
    }

    /**
     * @return void
     */
    public function render(): void {
        $template = $this->createTemplate();
        $template->setFile( __DIR__ . '/templates/ChatConversation.latte' );
        $template->render();
    }

}


interface IChatConversationControlFactory {
    /**
     * @return ChatConversationControl
     */
    public function create(): ChatConversationControl;
}
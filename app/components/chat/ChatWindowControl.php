<?php

namespace ChatApp\Components;

use Nette\Application\UI\Control;

class ChatWindowControl extends Control {

    /**
     * @var IChatSendFormFactory
     */
    private $sendFormFactory;

    function __construct( IChatSendFormFactory $sendFormFactory ) {
        parent::__construct();
        $this->sendFormFactory = $sendFormFactory;
    }

    protected function createComponentSendForm(): ChatSendForm {
        return $this->sendFormFactory->create();
    }

    /**
     * @return void
     */
    public function render(): void {
        $template = $this->createTemplate();
        $template->setFile( __DIR__ . '/templates/ChatWindow.latte' );
        $template->render();
    }

}


interface IChatWindowControlFactory {
    /**
     * @return ChatWindowControl
     */
    public function create(): ChatWindowControl;
}
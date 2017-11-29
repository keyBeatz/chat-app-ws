<?php

namespace ChatApp\Components;

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
     * ChatControl constructor.
     *
     * @param IChatConversationControlFactory $chatConversationControl
     * @param IChatWindowControlFactory $chatWindowsControl
     */
    function __construct(
        IChatConversationControlFactory $chatConversationControl,
        IChatWindowControlFactory $chatWindowsControl
    ) {
        parent::__construct();

        $this->chatConversationControl = $chatConversationControl;
        $this->chatWindowsControl = $chatWindowsControl;
    }

    /**
     * @return ChatConversationControl
     */
    protected function createComponentConversations(): ChatConversationControl {
        return $this->chatConversationControl->create();
    }

    /**
     * @return ChatWindowControl
     */
    protected function createComponentWindow(): ChatWindowControl {
        return $this->chatWindowsControl->create();
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
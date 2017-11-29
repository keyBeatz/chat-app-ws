<?php

namespace ChatApp\Components;

use Nette\Application\UI\Control;
use Nette\Application\UI\Form;

class ChatSendForm extends Control {

    function __construct() {
        parent::__construct();
    }

    protected function createComponentSendForm() {
        $form = new Form();

        $form->addText( "message", "Zpráva" );

        $form->addSubmit( "submit" );

        return $form;
    }

    /**
     * @return void
     */
    public function render(): void {
        $template = $this->createTemplate();
        $template->setFile( __DIR__ . '/templates/ChatSendForm.latte' );
        $template->render();
    }

}


interface IChatSendFormFactory {
    /**
     * @return ChatSendForm
     */
    public function create(): ChatSendForm;
}
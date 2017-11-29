<?php

namespace App\Presenters;

use ChatApp\Components\ChatControl;
use ChatApp\Components\IChatControlFactory;
use Nette;


class HomepagePresenter extends Nette\Application\UI\Presenter
{

    /**
     * @var IChatControlFactory
     */
    private $chatControlFactory;

    function __construct( IChatControlFactory $chatControlFactory ) {
        parent::__construct();

        $this->chatControlFactory = $chatControlFactory;
    }

    /**
     * @return ChatControl
     */
    protected function createComponentChat(): ChatControl {
        return $this->chatControlFactory->create();
    }

}

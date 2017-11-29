<?php

namespace ChatApp\WebSockets\Exceptions;

class ControllerRuntimeException extends \RuntimeException {

}

class ControllerLogicException extends \LogicException {

}

class ControllerBadInterface extends ControllerLogicException {

}
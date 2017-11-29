<?php

namespace ChatApp\WebSockets\Exceptions;

class ClientRuntimeException extends \RuntimeException {

}

class ClientLogicException extends \LogicException {

}

class ClientNoResourceIdException extends ClientLogicException {

}
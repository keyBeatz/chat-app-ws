<?php

namespace ChatApp\WebSockets;

use Ratchet\ConnectionInterface;

interface IClientConnection extends ConnectionInterface {
    public function getConnection() : ConnectionInterface;
    public function getResourceId() : int;
    public function getUserId() : int;
    public function setUserId( int $userId ) : void;
}
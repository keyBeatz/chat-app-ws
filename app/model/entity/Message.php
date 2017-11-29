<?php

namespace Model\Entity;

use Dibi\DateTime;
use LeanMapper;

/**
 * @property int $id
 * @property string $text
 * @property DateTime $dateSent
 * @property DateTime $dateEdited
 * @property-read int $user_id
 * @property Conversation $conversation m:hasOne(conversation_id:conversation)
 * @property User $user m:hasOne(user_id:user)
 */
class Message extends LeanMapper\Entity
{

}



<?php

namespace Model\Entity;

use LeanMapper;

/**
 * @property int $id
 * @property User $user m:hasOne(user_id:user)
 * @property-read int $userId
 * @property Conversation $conversation m:hasOne(conversation_id:conversation)
 * @property string $status m:enum(self::STATUS_*)
 * @property bool $unread
 * @property bool $deleted
 */
class ConversationMember extends LeanMapper\Entity
{

    const STATUS_ACTIVE     = 'active';
    const STATUS_DELETED    = 'deleted';

}



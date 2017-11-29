<?php

namespace Model\Entity;

use LeanMapper;

/**
 * @property int $id
 * @property string $type
 * @property string $dateCreated
 * @property string $dateUpdated
 * @property Message $lastMessage m:belongsToOne(conversation_id:message#union) m:filter(limit#1, orderByDesc#date_sent)
 * @property Message[] $messages m:belongsToMany(conversation_id:message) m:useMethods
 * @property ConversationMember[] $members m:belongsToMany(conversation_id:conversation_member)
 */
class Conversation extends LeanMapper\Entity
{

	public function getMessages( $limit = 100 ) {

		$data = $this->getValueByPropertyWithRelationship( "messages", new LeanMapper\Filtering( function( LeanMapper\Fluent $statement ) use( $limit ) {
			$statement->orderBy( "date_sent ASC" )->limit( $limit );
		}));

		return $limit === 1 && !empty( $data ) ? reset( $data ) : $data;
	}

}



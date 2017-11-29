<?php

namespace Model\Repository;


class MessageRepository extends BaseRepository
{
	public function findBySocket( $types ) {
		$table = $this->getTable();

		$query = $this->connection->select( "{$table}.*,
											conversation.region_id AS regionId,
											conversation.game_id AS gameId,
											conversation.stream_id AS streamId,
											conversation.type,
											user.id AS userId,
											user.name AS userName" )
		                        ->from( $table )
								->leftJoin( 'conversation' )->on( "{$table}.conversation_id = conversation.id" )
								->leftJoin( 'user' )->on( "user.id = {$table}.user_id" )
		                        ->where( 'conversation.type IN %in', $types )
		                        ->where( 'date_sent >= %s', date('Y-m-d H:i:s', strtotime('-6 seconds')));

        $rows = $query->fetchAll();

		return $rows;
	}
}
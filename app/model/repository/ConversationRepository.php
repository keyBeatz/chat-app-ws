<?php

namespace Model\Repository;


class ConversationRepository extends BaseRepository
{
	public function findByUser( int $userId, array $args = [] ) {

		$table = $this->getTable();

        $query = $this->connection->select( "{$table}.*" )
		                         ->from( $table )
		                         ->leftJoin( 'conversation_member' )
		                         ->on( "{$table}.id = conversation_member.conversation_id" )
		                         ->where( 'conversation_member.user_id = %i', $userId );

        if( !empty( $args['unread'] ) ) {
            $query->where( "conversation_member.unread = %i", 1 );
        }

        $query->orderBy( "conversation.date_updated DESC" );

        $rows = $query->fetchAll();

		return $this->createEntities( $rows );
	}

	public function findByUserFriend( int $userId, int $userFriendId ) {
        $table = $this->getTable();

        $query = $this->connection->select( "{$table}.*" )->from( $table );

        $query->leftJoin( "conversation_member AS member1" )
              ->on( "member1.conversation_id = conversation.id AND member1.user_id = %i", $userId );
        $query->leftJoin( "conversation_member AS member2" )
              ->on( "member2.conversation_id = conversation.id AND member2.user_id = %i", $userFriendId );

        $query->where( "member1.conversation_id = member2.conversation_id" );

        $row = $query->fetch();

        if( !$row )
            return null;

        return $this->createEntity( $row );
    }
}

class ConversationMemberRepository extends BaseRepository
{

	public function findByUser( int $userId, array $args = [] ) {

		$table = $this->getTable();

		$query = $this->connection->select( "{$table}.*" )
		                        ->from( $table )
								->leftJoin( 'conversation' )
								->on( "{$table}.conversation_id = conversation.id" )
		                        ->where( 'user_id = %i', $userId );

		if( !empty( $args['unread'] ) ) {
		    $query->where( "{$table}.unread = %i", $args['unread'] );
        }

        $query->orderBy( "conversation.date_updated DESC" );

        $rows = $query->fetchAll();

		return $this->createEntities( $rows );
	}

}
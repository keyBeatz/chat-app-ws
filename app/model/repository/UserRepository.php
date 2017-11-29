<?php

namespace Model\Repository;


class UserRepository extends BaseRepository
{

	public function find( $id ) {
		$row = $this->connection->select( '*, null AS password' )
		                        ->from( $this->getTable() )
		                        ->where( 'id = %i', $id )
		                        ->fetch();

		if( $row === false ) {
			throw new \Exception( 'Entity was not found.' );
		}

		return $this->createEntity( $row );
	}

	public function findMe( $id ) {
		$row = $this->connection->select( '*' )
		                        ->from( $this->getTable() )
		                        ->where( 'id = %i', $id )
		                        ->fetch();

		if( $row === false ) {
			throw new \Exception( 'Entity was not found.' );
		}

		return $this->createEntity( $row );
	}

	public function update( $id, $values ) {
		$this->connection->update( $this->getTable(), $values )->where( 'id = %i', $id )->execute();
	}

    /**
     * @param string $phrase
     * @param array $args
     *
     * @return array|\Dibi\Row[]
     * @throws \Exception
     */
	public function search( string $phrase, array $args = [] ) {

	    $all = !empty( $args['all'] ) ? true : false;

	    if( !$phrase )
            throw new \Exception( 'Phrase was not set.' );

        $query = $this->connection->select( '*' )
                                ->from( $this->getTable() );

        $table = $this->getTable();

        $sql = [];
        $sql[] = "SELECT *, null AS password FROM {$table}";
        $sql[] = "WHERE 1 = 1 AND (";

        if( !empty( $args['login'] ) || !empty( $args['nick'] ) || $all ) {
            $sql[] = "login LIKE %~like~";
            $sql[] = $phrase;
        }

        if( !empty( $args['name'] ) || !empty( $args['firstname'] ) || $all ) {
            $sql[] = "OR firstname LIKE %~like~";
            $sql[] = $phrase;
        }

        if( !empty( $args['name'] ) || !empty( $args['lastname'] ) || $all ) {
            $sql[] = "OR lastname LIKE %~like~";
            $sql[] = $phrase;
        }

        if( !empty( $args['email'] ) || $all ) {
            $sql[] = "OR email LIKE %~like~";
            $sql[] = $phrase;
        }

        $sql[] = ")";


        $rows = $this->connection->query( $sql )->fetchAll();
        return empty( $args['raw'] ) ? $this->createEntities( $rows ) : $rows;
    }

	public function findUserSubscribes( $userId ) {
		$rows = $this->connection->select( 'user.*' )
								->from( $this->getTable() )
								->leftJoin( 'user_subscribe' )->on( 'user_subscribe.profile_id = user.id' )
								->where( 'user_subscribe.user_id = %i', $userId )
								->fetchAll();

		return $this->createEntities( $rows );
	}

}
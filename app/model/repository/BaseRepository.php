<?php

namespace Model\Repository;

use LeanMapper;

class BaseRepository extends LeanMapper\Repository
{


	public function find( $id ) {
		$row = $this->connection->select( '*' )
		                        ->from( $this->getTable() )
		                        ->where( 'id = %i', $id )
		                        ->fetch();

		return $row ? $this->createEntity( $row ) : null;
	}

	public function findBySlug( $slug ) {
		$row = $this->connection->select( '*' )
		                        ->from( $this->getTable() )
		                        ->where( 'slug = %s', $slug )
		                        ->fetch();

		return $row ? $this->createEntity( $row ) : null;
	}

	public function findByIds( $ids ) {
		$rows = $this->connection->select( '*' )
		                        ->from( $this->getTable() )
		                        ->where( 'id IN %in', $ids )
		                        ->fetchAll();

        return $rows ? $this->createEntities( $rows ) : [];
	}

	public function findBy( $params ) {
	    $limit = $params['limit'] ?? null;
	    $count = $params['count'] ?? null;

	    unset($params['limit'], $params['count']);

		$rows = $this->connection->select( '*' )
								->from( $this->getTable() );

		foreach ($params as $col => $val) {
			switch (gettype($val)) {
				case 'integer':
					$rows = $rows->where($col . ' = %i', $val);
					break;
				case 'array':
					$rows = $rows->where($col . ' IN %in', $val);
					break;
				case 'NULL':
					$rows = $rows->where($col . ' IS NULL');
					break;
				default:
					$rows = $rows->where($col . ' = %s', $val);
					break;
			}
		}

		if ( is_int($limit) ) {
		    $rows = $rows->limit($limit);
        }

        if ( $count ) {
            $rows = $rows->removeClause('select')
                    ->select('count(*) AS total')
                    ->fetch();

            return !empty($rows) ? $rows->total : 0;
        } else {
            $rows = $rows->fetchAll();
        }

		return $rows ? $this->createEntities( $rows ) : [];
	}

	public function findAll() {
		$rows = $this->connection->select( '*' )
			                 ->from( $this->getTable() )
			                 ->fetchAll();

        return $rows ? $this->createEntities($rows) : [];
	}


    public function findByType( $type = false ) {

        $query = $this->connection->select( '*' )
                                  ->from( $this->getTable() );

        if( $type ) {
            $query->where( 'type = %s', $type );
        }

        return $this->createEntities( $query->fetchAll() );
    }

	public function deleteById( $id ) {
		$this->connection->delete( $this->getTable() )
						->where( 'id IN (%i)', $id)
						->execute();
	}
}


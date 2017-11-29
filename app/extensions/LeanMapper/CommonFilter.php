<?php

namespace Model\Filter;

use LeanMapper\Fluent;

class CommonFilter
{
    public static function limit( Fluent $fluent, $limit = false ) {
        if( $limit )
            $fluent->limit( $limit );
    }

    public static function type( Fluent $fluent, $type = false ) {
        if( $type )
            $fluent->where( "type = %s", $type );
    }

    public static function orderBy( Fluent $fluent, $column ) {
        $fluent->orderBy( '%n', $column );
    }

    public static function orderByAsc( Fluent $fluent, $column ) {
        $fluent->orderBy( '%n ASC', $column );
    }

    public static function orderByDesc( Fluent $fluent, $column ) {
        $fluent->orderBy( '%n DESC', $column );
    }

    public static function getLastItem( Fluent $fluent ) {
        $fluent->orderBy( 'id ASC' );
        $fluent->limit( 1 );
    }

    public static function pagination( Fluent $fluent, $page = 1, $perPage = 10 ) {
        $page       = $page ? $page : 1;
        $perPage    = $perPage && $perPage > 1 ? $perPage : 10;
        $offset     = ( $page * $perPage ) - $perPage;

        $fluent->limit( $perPage );
        $fluent->offset( $offset );
    }

}
<?php

/**
 * Add new column to the User List Table
 *
 * @author  Mauricio Gelves <mg@maugelves.com>
 * @param   $column
 * @return  mixed
 */
function new_modify_user_table( $column ) {
    $column['apikey'] = 'API Key';
    $column['estado'] = 'Estado';
    return $column;
}
add_filter( 'manage_users_columns', 'new_modify_user_table' );


function new_modify_user_table_row( $val, $column_name, $user_id ) {
    $umeta = get_user_meta( $user_id );
    switch ($column_name) {
        case 'apikey' :
            //return get_the_author_meta( 'mgdbq2josn_uhashkey', $user_id );
            return $umeta["mgdbq2josn_uhashkey"][0];
            break;
        case 'estado':
            if( $umeta["mgdbq2josn_user_state"][0] == "activo" ){
                return "<p style='color: green;'>Activo</p>";
            }
            else {
                return "<p style='color: red;'>Bloqueado</p>";
            }
            break;
        default:
    }
    return $val;
}
add_filter( 'manage_users_custom_column', 'new_modify_user_table_row', 10, 3 );
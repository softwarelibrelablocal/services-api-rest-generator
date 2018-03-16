<?php

/**
 * Register Custom Post Type "Connector"
 */
function mg_register_connector_cpts() {

    $labels = array(
        'name'                  => _x( 'Conectores', 'Post Type General Name', 'mgdbq2json' ),
        'singular_name'         => _x( 'Conector', 'Post Type Singular Name', 'mgdbq2json' ),
        'menu_name'             => __( 'Conectores', 'mgdbq2json' ),
        'name_admin_bar'        => __( 'Conector', 'mgdbq2json' ),
        'archives'              => __( 'Item Archives', 'mgdbq2json' ),
        'all_items'             => __( 'Todos los conectores', 'mgdbq2json' ),
        'add_new_item'          => __( 'Añadir nuevo conector', 'mgdbq2json' ),
        'add_new'               => __( 'Añadir nuevo', 'mgdbq2json' ),
        'new_item'              => __( 'Nuevo conector', 'mgdbq2json' ),
        'edit_item'             => __( 'Editar conector', 'mgdbq2json' ),
        'update_item'           => __( 'Actualizar conector', 'mgdbq2json' ),
        'view_item'             => __( 'Ver conector', 'mgdbq2json' ),
        'search_items'          => __( 'Buscar conector', 'mgdbq2json' ),
    );
    $args = array(
        'label'                 => __( 'Conector', 'mgdbq2json' ),
        'description'           => __( 'Databases connectors', 'mgdbq2json' ),
        'labels'                => $labels,
        'supports'              => array('title'),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_icon'             => 'dashicons-networking',
        'menu_position'         => 5,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => true,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
    );
    register_post_type( 'mg_connector', $args );

}
add_action( 'init', 'mg_register_connector_cpts', 0 );


/**
 * This function changes the placeholder of the Post Title input
 */
function mg_change_connector_title_placeholder( $title ){
    $screen = get_current_screen();
    if  ( 'mg_connector' == $screen->post_type ) {
        $title = __( 'Nombre del conector', 'mgdbq2json' );
    }
    return $title;
}
add_filter( 'enter_title_here', 'mg_change_connector_title_placeholder' );





/**
 * Connector update messages.
 *
 * @param   array $messages Existing post update messages.
 * @return  array Amended post update messages with new CPT update messages.
 * @since   1.0
 */
function mg_connector_updated_messages( $messages ) {

    if( 'mg_connector' == get_post_type() ):

        $messages['mg_connector'] = array(
            0  => '', // Unused. Messages start at index 1.
            1  => __( 'Conector actualizado.', 'mgdbq2json' ),
            4  => __( 'Conector actualizado.', 'wpduf' ),
            5  => isset( $_GET['revision'] ) ? sprintf( __( 'Conector restaurado a la versión %s', 'wpduf' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
            6  => __( 'Conector publicado.', 'wpduf' ),
            7  => __( 'Conector guardado.', 'wpduf' ),
            8  => __( 'Conector enviado.', 'wpduf' ),
            10 => __( 'Borrado de Conector.', 'wpduf' )
        );

    endif;

    return $messages;
}
add_filter( 'post_updated_messages', 'mg_connector_updated_messages' );





function my_disable_quick_edit( $actions = array(), $post = null ) {

    // Abort if the post type is not "books"
    if ( ! is_post_type_archive( 'mg_connector' ) ) {
        return $actions;
    }

    // Remove the Quick Edit link
    if ( isset( $actions['inline hide-if-no-js'] ) ) {
        unset( $actions['inline hide-if-no-js'] );
    }

    // Return the set of links without Quick Edit
    return $actions;

}
add_filter( 'post_row_actions', 'my_disable_quick_edit', 10, 2 );
add_filter( 'page_row_actions', 'my_disable_quick_edit', 10, 2 );





/**
 * Create new columns for the Connector Custom Post Type
 *
 * @author  Mauricio Gelves <mg@maugelves.com>
 * @param   $defaults
 * @return  mixed
 * @since   1.0
 */
function mg__modify_connectors_posts_columns( $defaults ) {
    $defaults = array();
    $defaults['title']                  = __('Conector', 'mgdbq2json');
    $defaults['connector_description']  = __('Descripción', 'mgdbq2json');
    $defaults['connector_engine']       = __('Motor de BBDD', 'mgdbq2json');
    $defaults['date']                   = __('Fecha de creación', 'mgdbq2json');
    return $defaults;
}
add_filter('manage_mg_connector_posts_columns', 'mg__modify_connectors_posts_columns');




/**
 * Fill the new columns for Connector Custom Post Type
 *
 * @author  Mauricio Gelves <mg@maugelves.com>
 * @param   $column_name
 * @param   $post_id
 * @since   1.0
 */
function mg_fill_connector_posts_columns( $column_name, $post_id ) {

    switch( $column_name ):

        case 'connector_description':
            the_field('mgdbq2josn_descripcion');
            break;

        case 'connector_engine':
            $engine = get_field('mgdbq2josn_dbengine');
            echo $engine['label'];
            break;

    endswitch;


}
add_action( 'manage_mg_connector_posts_custom_column', 'mg_fill_connector_posts_columns', 10, 2 );
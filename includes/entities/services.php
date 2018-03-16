<?php

/**
 * Register Custom Post Type "Connector"
 */
function mg_register_services_cpts() {

    $labels = array(
        'name'                  => _x( 'Servicios', 'Post Type General Name', 'mgdbq2json' ),
        'singular_name'         => _x( 'Servicio', 'Post Type Singular Name', 'mgdbq2json' ),
        'menu_name'             => __( 'Servicios', 'mgdbq2json' ),
        'name_admin_bar'        => __( 'Servicio', 'mgdbq2json' ),
        'all_items'             => __( 'Todos los servicios', 'mgdbq2json' ),
        'add_new_item'          => __( 'Añadir nuevo Servicio', 'mgdbq2json' ),
        'add_new'               => __( 'Añadir nuevo', 'mgdbq2json' ),
        'new_item'              => __( 'Nuevo Servicio', 'mgdbq2json' ),
        'edit_item'             => __( 'Editar Servicio', 'mgdbq2json' ),
        'update_item'           => __( 'Actualizar Servicio', 'mgdbq2json' ),
        'view_item'             => __( 'Ver Servicio', 'mgdbq2json' ),
        'search_items'          => __( 'Buscar Servicio', 'mgdbq2json' ),
    );
    $args = array(
        'label'                 => __( 'Servicio', 'mgdbq2json' ),
        'description'           => __( 'Administrar servicios de REST API', 'mgdbq2json' ),
        'labels'                => $labels,
        'supports'              => array('title'),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_icon'             => 'dashicons-cloud',
        'menu_position'         => 6,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => true,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
    );
    register_post_type( 'mg_service', $args );

}
add_action( 'init', 'mg_register_services_cpts', 0 );


/**
 * This function changes the placeholder of the Post Title input
 */
function mg_change_service_title_placeholder( $title ){
    $screen = get_current_screen();
    if  ( 'mg_service' == $screen->post_type ) {
        $title = __( 'Nombre del servicio', 'mgdbq2json' );
    }
    return $title;
}
add_filter( 'enter_title_here', 'mg_change_service_title_placeholder' );




/**
 * Service update messages.
 *
 * @param   array $messages Existing post update messages.
 * @return  array Amended post update messages with new CPT update messages.
 * @since   1.0
 */
function mg_servicio_updated_messages( $messages ) {

    if( 'mg_service' == get_post_type() ):

        $messages['mg_service'] = array(
            0  => '', // Unused. Messages start at index 1.
            1  => __( 'Servicio actualizado.', 'mgdbq2json' ),
            4  => __( 'Servicio actualizado.', 'wpduf' ),
            5  => isset( $_GET['revision'] ) ? sprintf( __( 'Servicio restaurado a la versión %s', 'wpduf' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
            6  => __( 'Servicio publicado.', 'wpduf' ),
            7  => __( 'Servicio guardado.', 'wpduf' ),
            8  => __( 'Servicio enviado.', 'wpduf' ),
            10 => __( 'Borrado de Servicio.', 'wpduf' )
        );

    endif;

    return $messages;
}
add_filter( 'post_updated_messages', 'mg_servicio_updated_messages' );






/**
 * Create new columns for the Service Custom Post Type
 *
 * @author  Mauricio Gelves <mg@maugelves.com>
 * @param   $defaults
 * @return  mixed
 * @since   1.0
 */
function mg__modify_services_posts_columns( $defaults ) {
    $defaults = array();
    $defaults['title']                  = __('Servicio', 'mgdbq2json');
    $defaults['service-name']           = __('Nombre en API', 'mgdbq2json');
    $defaults['service-description']    = __('Descripción', 'mgdbq2json');
    $defaults['service-connector']      = __('Conector', 'mgdbq2json');
    $defaults['date']                   = __('Fecha de creación', 'mgdbq2json');
    return $defaults;
}
add_filter('manage_mg_service_posts_columns', 'mg__modify_services_posts_columns');






/**
 * Fill the new columns for Service Custom Post Type
 *
 * @author  Mauricio Gelves <mg@maugelves.com>
 * @param   $column_name
 * @param   $post_id
 * @since   1.0
 */
function mg_fill_service_posts_columns( $column_name, $post_id ) {

    switch( $column_name ):

        case 'service-name':
            the_field('mgdbq2josn_servicename');
            break;
        case 'service-description':
            the_field('mgdbq2josn_descripcion');
            break;
        case 'service-connector':
            $connector = get_post( get_field('mgdbq2josn_connector')[0] );
            // Create a link to the Connector relationship
            echo sprintf('<a href="%s">%s</a>', get_edit_post_link( $connector->ID ), $connector->post_title );
            break;
    endswitch;


}
add_action( 'manage_mg_service_posts_custom_column', 'mg_fill_service_posts_columns', 10, 2 );
<?php

function my_acf_admin_enqueue_scripts() {
    // register style
    wp_register_style( 'my-acf-input-css', plugin_dir_url( __FILE__ ) . '../../css/my-acf-input.css', false );
    wp_enqueue_style( 'my-acf-input-css' );


    // register script
    global $post_type;
    if( 'mg_connector' == $post_type && is_admin() ) {
        wp_register_script( 'my-acf-input-js', plugin_dir_url( __FILE__ ) . '../../js/connectors.js', false);
        wp_enqueue_script( 'my-acf-input-js' );
    }


}
add_action( 'acf/input/admin_enqueue_scripts', 'my_acf_admin_enqueue_scripts' );


function remove_menus(){
    $user = wp_get_current_user();

    if ( !in_array( 'administrator', (array) $user->roles ) ) {

        remove_menu_page( 'index.php' );                  //Dashboard
        remove_menu_page( 'jetpack' );                    //Jetpack*
        remove_menu_page( 'edit.php' );                   //Posts
        remove_menu_page( 'upload.php' );                 //Media
        remove_menu_page( 'edit.php?post_type=page' );    //Pages
        remove_menu_page( 'edit-comments.php' );          //Comments
        remove_menu_page( 'themes.php' );                 //Appearance
        remove_menu_page( 'plugins.php' );                //Plugins
        remove_menu_page( 'users.php' );                  //Users
        remove_menu_page( 'tools.php' );                  //Tools
        remove_menu_page( 'options-general.php' );        //Settings

    }


}
add_action( 'admin_menu', 'remove_menus' );



if( function_exists('acf_add_options_page') ) {

    $option_page = acf_add_options_page(array(
        'capability' 	=> 'edit_posts',
        'icon_url'      => 'dashicons-admin-tools   ',
        'menu_title' 	=> 'MGDBQ2JSON Config',
        'menu_slug' 	=> 'mgdbq2json-config',
        'page_title' 	=> 'MGDBQ2JSON Config',
        'redirect' 	    => false
    ));

}

?>
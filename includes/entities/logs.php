<?php

/**
 * Adds a new top-level page to the administration menu.
 */
function logs_add_page() {
    add_menu_page(
        __( 'Logs','wpduf' ),
        __( 'Logs', 'wpduf' ),
        'edit_pages',
        'logs_list',
        'logs_list_callback',
        'dashicons-clock',
        26
    );
}
add_action('admin_menu', 'logs_add_page');



/**
 * Display callback for the Booking List page.
 */
function logs_list_callback() {
    //Prepare Table of elements
    $logs_table = new \MGDBQ2JSON\ListTable\Logs();
    $logs_table->prepare_items(); ?>

    <div class="wrap">
        <h1><?php _e('Registro de Log','mgdbq2json'); ?></h1>

        <form action="" method="post">
            <?php

            //$logs_table->search_box(__("Buscar por referencia", "mgdbq2json"), "txtsearch");
            $logs_table->views();
            $logs_table->display();
            ?></form>
    </div><!-- END .wrap -->
    <?php
}


add_action('admin_head', 'set_logs_column_width');
function set_logs_column_width() {
    $screen = get_current_screen();
    if($screen->id == "toplevel_page_logs_list"){
        echo '<style type="text/css">';
        echo '.column-id { width:40px !important; overflow:hidden }';
        echo '.column-date_created { width:130px !important; overflow:hidden }';
        echo '.column-user { width:200px !important; overflow:hidden }';
        echo '.column-ip { width:110px !important; overflow:hidden }';
        echo '.column-service { width:150px !important; overflow:hidden }';
        echo '.column-parameters { width:240px !important; overflow:hidden }';
        echo '.column-response { width:240px !important; overflow:hidden }';
        echo '.column-error { width: 100px !important; overflow:hidden; }';
        echo 'td.column-parameters,td.column-error { font-size: 9px !important; }';
        echo 'td.column-error { color: red; font-weight: bold; }';
        echo '</style>';
    }
}
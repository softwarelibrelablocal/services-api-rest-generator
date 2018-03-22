<?php
/**
 * @link              http://example.com
 * @since             1.0.0
 * @package           MG_DBQ2JSON
 *
 * @wordpress-plugin
 * Plugin Name:       MG Database Queries 2 JSON
 * Description:       Create database connections and execute queries that will be rendered and returned as JSON.
 * Version:           1.0.0
 * Author:            Mauricio Gelves
 * Author URI:        https://maugelves.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       mgdbq2json
 * Domain Path:       /languages
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-mg-dbq2json-activator.php
 */
function activate_mg_dbq2json() {

    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $table_name = $wpdb->prefix . 'logs';

    $sql = "CREATE TABLE $table_name (
              `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
              `user_id` int(11) NOT NULL DEFAULT '0',
              `apikey` varchar(12) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
              `service_id` int(11) NOT NULL DEFAULT '0',
              `connector_id` int(11) NOT NULL DEFAULT '0',
              `parameters` varchar(500) NOT NULL DEFAULT '',
              `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
              `response` text NOT NULL,
              `error_code` varchar(100) NOT NULL DEFAULT '',
              `IP` varchar(45) NOT NULL DEFAULT '0',
              `execution_time` int(11) NOT NULL,
              PRIMARY KEY (`id`)
            ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );


    // Set default options
    update_option( 'options_mgdbq2json_nro_intentos', 3 );
    update_option( '_options_mgdbq2json_nro_intentos', 'field_58623ed1101be' );
    update_option( 'options_mgdbq2json_intervalo_minutos', 2 );
    update_option( '_options_mgdbq2json_intervalo_minutos', 'field_58623f3c101bf' );

}
register_activation_hook( __FILE__, 'activate_mg_dbq2json' );



/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-mg-dbq2json-deactivator.php
 */
function deactivate_mg_dbq2json() {

}
register_deactivation_hook( __FILE__, 'deactivate_mg_dbq2json' );


// Load Helpersxz
foreach (glob(__DIR__ . "/includes/helpers/*.php") as $filename)
    include $filename;


/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */

require plugin_dir_path( __FILE__ ) . 'includes/main.php';



/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
\MGDBQ2JSON\Main::getInstance()->init();

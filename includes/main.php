<?php

namespace MGDBQ2JSON;


/**
* The core plugin class.
*
* This is used to define admin-specific hooks, and
* public-facing site hooks.
*
* Also maintains the unique identifier of this plugin as well as the current
* version of the plugin.
*
* @since      1.0.0
* @package    MG_DBQ2json
* @subpackage MG_DBQ2json/includes
* @author     Mauricio Gelves <mg@example.com>
*/

class Main extends \Singleton{

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name = 'mg-dbq2json';

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version = '1';


    /**
     * @return string
     */
    public function get_version()
    {
        return $this->version;
    }

    /**
     * @return string
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }


    /**
     * Check all the plugin and options dependencies for this plugin
     */
    public function check_dependencies() {

        // Variables
        $plugins  = array(

            // Register dependencies
            array(
                'name'               => 'WP REST API',  // El nombre del plugin.
                'slug'               => 'rest-api',     // El "slug" del plugin (normalmente el nombre de la carpeta).
                'required'           => true            // Si es falso, el plugin es "recomendado" en lugar de "requerido".
            )

        );
        

        // Check registered dependencies
        tgmpa( $plugins );

    }


    public function init() {
        $this->load_dependencies();
    }


    private function load_acf_plugin() {

        // Set up ACF
        add_filter('acf/settings/path', function() {
            return sprintf("%s/advanced-custom-fields-pro/", dirname(__FILE__));
        });
        add_filter('acf/settings/dir', function() {
            return sprintf("%s/advanced-custom-fields-pro/", plugin_dir_url(__FILE__));
        });
        require_once(sprintf("%s/advanced-custom-fields-pro/acf.php", dirname(__FILE__)));

    }


    /**
     * Load all the libraries and classes needed to execute the plugin
     */
    private function load_dependencies() {

        // Load ACF Plugin
        $this->load_acf_plugin();

        // Load Third Parties ACF Plugins
        include __DIR__ . "/acf-random-string-field/acf-random-string-field.php";
        include __DIR__ . "/acf-role-filter/acf-user-role-field-setting.php";

        // Check plugin dependencies
        include __DIR__ . "/tgm-plugin-activation/class-tgm-plugin-activation.php";
        add_action( 'tgmpa_register', array($this, 'check_dependencies' ) );
        
        
        // Load Entities
        foreach (glob(__DIR__ . "/entities/*.php") as $filename)
            include $filename;

        // Register the ACF Group Fields
        foreach (glob(__DIR__ . "/acf-group-fields/*.php") as $filename)
            include $filename;

        // Register the Classes
        foreach (glob(__DIR__ . "/classes/*.php") as $filename)
            include $filename;

        // Register the WP-REST-API Services
        foreach (glob(__DIR__ . "/wp-rest-api/*.php") as $filename)
            include $filename;

        // Admin
        foreach (glob(__DIR__ . "/admin/*.php") as $filename)
            include $filename;

    }

    
}
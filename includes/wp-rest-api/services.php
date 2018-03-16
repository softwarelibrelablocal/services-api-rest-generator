<?php


/**
 * Class Services responsible to handle all the requests
 *
 * @auhor   Mauricio Gelves <mg@maugelves.com>
 * @package MGDBQ2JSON
 */
class Services extends \Singleton {
    // Properties
    private $version;
    private $namespace;

    public function init() {

        // Set default variables
        $this->version = \MGDBQ2JSON\Main::getInstance()->get_version();
        $this->namespace = \MGDBQ2JSON\Main::getInstance()->get_plugin_name() . '/v' . $this->version;

        add_action( 'rest_api_init', array( $this, 'register_services' ) );

    }




    /**
     * This function check if the REST API call brings all the
     * parameters to serve the Query
     *
     * @author  Mauricio Gelves <mg@maugelves.com>
     * @param   $dbservice \MGDBQ2JSON\Models\DBService
     * @param   $get array $_GET with all the querystrings
     * @return  bool
     * @since   1.0
     */
    public function check_service_parameters( $dbservice, $get ) {

        // Variable
        $result = true;

        // Check if all the parameters exists in the $_GET variable
        foreach ($dbservice->get_params() as $param ):
            if( empty( $get[$param] ) ) {
                $result = false;
                break;
            }
        endforeach;

        return $result;
    }



    /**
     * This functions handles the SQL Services.
     * Receives, controls, executes the query and returns the JSON.
     *
     * @param WP_REST_Request $request
     * @return array
     */
    public function process_service( WP_REST_Request $request ) {

        // Variables
        $result = null;

        // Check for the Service parameter
        if( empty( $_GET['s'] ) ) {

            // Generate the error
            $error_code = 'no-service-parameter';
            $error = new WP_Error($error_code, __("No se especificó el parámetro 's' con el nombre de la consulta.","mgdbq2json"), array( "status" => 400, "error"=>true ));

            // Set the log
            \MGDBQ2JSON\Services\Logs::getInstance()->log_error($error_code, $error);

            return $error;

        }

        // Check for the User API Key
        if( empty( $_GET['u'] ) ) {

            // Generate the error
            $error_code = 'no-apikey-parameter';
            $error = new WP_Error($error_code, __("No se especificó el parámetro 'u' con la API Key del usuario.","mgdbq2json"), array( "status" => 400, "error"=>true ));

            // Set the log
            \MGDBQ2JSON\Services\Logs::getInstance()->log_error($error_code, $error);

            return $error;
        }


        // Get the User By the API Key
        $user = \MGDBQ2JSON\Services\Users::getInstance()->get_user_by_apikey( esc_sql( $_GET["u"] ) );

        
        // Check the User exists
        if( !$user ) {

            // Generate the error
            $error_code = "user-not-found";
            $error = new WP_Error($error_code, __("No existe ningún usuario con el API Key especificado", "mgdbq2json"), array( "status" => 200, "error"=>true ));

            // Set the log
            \MGDBQ2JSON\Services\Logs::getInstance()->log_error($error_code, $error, $user, $service);

            return $error;
        }


        // Check he User is not blocked
        if( $user->is_blocked() ) {

            // Generate the error
            $error_code = "user-blocked";
            $error = new WP_Error($error_code, __("El usuario tiene su cuenta bloqueada", "mgdbq2json"), array( "status" => 200, "error"=>true ));

            // Set the log
            \MGDBQ2JSON\Services\Logs::getInstance()->log_error($error_code, $error, $user);

            return $error;

        }


        // Get the service
        $service = \MGDBQ2JSON\Services\DBServices::getInstance()->get_dbservice_by_name( esc_sql($_GET["s"]) );


        // Check the service exists
        if( !$service ) {

            // Generate the error
            $error_code = 'service-not-found';
            $error = new WP_Error($error_code, __("No existe ningún servicio con el nombre especificado", "mgdbq2json"), array( "status" => 200, "error"=>true ));

            // Set the log
            \MGDBQ2JSON\Services\Logs::getInstance()->log_error($error_code, $error, $user);


            // Should the user be blocked
            \MGDBQ2JSON\Services\Users::getInstance()->should_user_be_blocked($user);

            return $error;
        };

        
        // Check the User can access the service
        if( !$service->has_user( $user->get_id() ) ) {

            // Generate the error
            $error_code = "user-not-allowed";
            $error = new WP_Error($error_code, __("El usuario no tiene permisos para acceder a la consulta", "mgdbq2json"), array( "status" => 200, "error"=>true ));

            // Set the log
            \MGDBQ2JSON\Services\Logs::getInstance()->log_error($error_code, $error, $user, $service);

            // Should the user be blocked
            \MGDBQ2JSON\Services\Users::getInstance()->should_user_be_blocked($user);

            return $error;
        }

        // Check the dynamic parameters of the service
        if( !$service->check_parameters( $_GET ) ) {

            // Generate the error
            $error_code = "missing-parameters";
            $error = new WP_Error($error_code, __("Falta parámetros para ejecutar el servicio.", "mgdbq2json"), array( "status" => 200, "error"=>true ));

            // Set the log
            \MGDBQ2JSON\Services\Logs::getInstance()->log_error($error_code, $error, $user, $service);

            // Should the user be blocked
            \MGDBQ2JSON\Services\Users::getInstance()->should_user_be_blocked($user);

            return $error;
        }

        /*
         * Render the QUERY replacing the {{params}} with the values
         */
        $rendered_query = $service->get_query();
        foreach ($service->get_params() as $param ):
            $rendered_query = str_replace("{{" . $param . "}}", esc_sql( $_GET[$param] ), $rendered_query);
        endforeach;


        // Initialize a timer to check the execution time
        $msc = microtime(true);

        // Select the engine
        switch( $service->get_connector()->get_dbengine()['value'] ):

            case "oracle":
                $result = \MGDBQ2JSON\Services\Connectors::getInstance()->get_oracle_query( $service, $rendered_query );
                break;
            case "mysql":
                $result = \MGDBQ2JSON\Services\Connectors::getInstance()->get_mysql_query( $service, $rendered_query );
                break;
            case "mssql":
                $result = \MGDBQ2JSON\Services\Connectors::getInstance()->get_mssql_query( $service, $rendered_query );
                break;

        endswitch;

        // Stop the timer
        $msc = floor((microtime(true)-$msc)*1000); // Result in milliseconds

        // Create a new log
        $log = new \MGDBQ2JSON\Models\Log();


        /*
         * Check errors and return values.
         * In case of error set the code in the log instance.
         */
        if( empty( $result ) ){
            $result = array( "code" => "no-rows-found",
                            "message" => __('No se han encontrado registros', 'mgdbq2json'),
                            array( "data" => 200, "error" => false )
                            );
        }


        // Fill the rest of the log fields and save
        $log->set_user_id( $user->get_id() );
        $log->set_apikey( $user->get_api_key() );
        $log->set_service_id( $service->get_ID() );
        $log->set_response( maybe_serialize( $result ) );
        $log->set_connector_id( $service->get_connector_id() );
        $log->set_execution_time( $msc );
        $log->set_parameters( full_url($_SERVER) );
        $log->set_IP( $_SERVER['REMOTE_ADDR'] );
        $log->save();


        // Download the JSON?
        if( !empty($_GET["action"]) && $_GET["action"] === "download" ) {
            $filename = $service->get_slug() . "_" . date('YmdHi') . ".json";
            header('Content-disposition: attachment; filename=' . $filename);
            header('Content-type: application/json');
			
        }
		
		//Modificado por el Ayuntamiento
		//Si no se pone la cabecera los ajax dan error de CORS Allow Origin
		header('Access-Control-Allow-Origin: *'); 


        /**
         * If Result is a wp_error return as it is, otherwise it will be encapsulated in another array
         */
        if( is_wp_error( $result ) ) {
            return $result;
        }
        else{
            wp_send_json($result);
        }


    }



    public function register_services() {

        // Variables
        $base = 'services';

        register_rest_route( $this->namespace, '/' . $base, array(
            'methods'         => "GET",
            'callback'        => array( $this, 'process_service' ),
            'args'            => array(
                's' => array(
                    'required'  => true,
                    'validate_callback' => function($param, $request, $key) {
                        return !empty( $param );
                    }
                ),
            )

        ) );

    }


}
Services::getInstance()->init();

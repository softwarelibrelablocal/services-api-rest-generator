<?php
namespace MGDBQ2JSON\Models;

use MGDBQ2JSON\Services\Connectors;
use MGDBQ2JSON\Services\DBServices;
use MGDBQ2JSON\Services\Users;

class Log
{
    // Variables
    private $ID = 0;
    private $user_id = 0;
    private $user = null;
    private $apikey = "";
    private $service_id = 0;
    private $service = null;
    private $connector_id = 0;
    private $connector = null;
    private $parameters = "";
    private $date_created = "";
    private $response = "";
    private $error_code = "";
    private $IP = "";
    private $execution_time = 0;
    
    // Getters and Setters

    /**
     * @return int
     */
    public function get_user_id()
    {
        return $this->user_id;
    }

    /**
     * @param int $user_id
     */
    public function set_user_id($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * @return User
     */
    public function get_user()
    {
        if( is_null( $this->user ) ) {
            $this->user = Users::getInstance()->get_user_by_id( $this->user_id );
        }
        return $this->user;
    }

    /**
     * @return string
     */
    public function get_apikey()
    {
        return $this->apikey;
    }

    /**
     * @param string $apikey
     */
    public function set_apikey($apikey)
    {
        $this->apikey = $apikey;
    }

    /**
     * @param null $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return int
     */
    public function get_id()
    {
        return $this->ID;
    }

    /**
     * @param int $ID
     */
    public function set_id($ID)
    {
        $this->ID = $ID;
    }

    /**
     * @return int
     */
    public function get_service_id()
    {
        return $this->service_id;
    }

    /**
     * @param int $service_id
     */
    public function set_service_id($service_id)
    {
        $this->service_id = $service_id;
    }

    /**
     * @return DBService
     */
    public function get_service()
    {
        if(is_null($this->service)){
            $this->service = DBServices::getInstance()->get_dbservice_by_id( $this->service_id );
        }
        return $this->service;
    }

    /**
     * @param DBService $service
     */
    public function set_service($service)
    {
        $this->service = $service;
    }

    /**
     * @return int
     */
    public function get_connector_id()
    {
        return $this->connector_id;
    }

    /**
     * @param int $connector_id
     */
    public function set_connector_id($connector_id)
    {
        $this->connector_id = $connector_id;
    }

    /**
     * @return Connector
     */
    public function get_connector()
    {
        if(is_null($this->connector)){
            $this->connector = Connectors::getInstance()->get_connector_by_id($this->connector_id);
        }
        return $this->connector;
    }

    /**
     * @param Connector $connector
     */
    public function set_connector($connector)
    {
        $this->connector = $connector;
    }

    /**
     * @return string
     */
    public function get_parameters()
    {
        return $this->parameters;
    }

    /**
     * @param string $parameters
     */
    public function set_parameters($parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * @return string
     */
    public function get_date_created()
    {
        return $this->date_created;
    }

    /**
     * @param string $date_created
     */
    public function set_date_created($date_created)
    {
        $this->date_created = $date_created;
    }

    /**
     * @return string
     */
    public function get_response()
    {
        return $this->response;
    }

    /**
     * @param string $response
     */
    public function set_response($response)
    {
        $this->response = $response;
    }

    /**
     * @return string
     */
    public function get_error_code()
    {
        return $this->error_code;
    }

    /**
     * @param string $error_code
     */
    public function set_error_code($error_code)
    {
        $this->error_code = $error_code;
    }

    /**
     * @return string
     */
    public function get_IP()
    {
        return $this->IP;
    }

    /**
     * @param string $IP
     */
    public function set_IP($IP)
    {
        $this->IP = $IP;
    }

    /**
     * @return int
     */
    public function get_execution_time()
    {
        return $this->execution_time;
    }

    /**
     * @param int $execution_time
     */
    public function set_execution_time($execution_time)
    {
        $this->execution_time = $execution_time;
    }


    // METHODS
    /**
     * Saves or update a log
     *
     * @author  Mauricio Gelves
     * @param   Log \Log
     * @return  bool true in case of success, false in case of error.
     * @since   1.0
     */
    public function save(){

        /**
         * In case the API CALL has an action (View or Download)
         * don't log it because is a call from the wp-admin
         */
        if( !empty($_GET["action"]) ){
            $possible_actions = array("view", "download");
            if( in_array( $_GET["action"], $possible_actions ) ) return true;
        }




        global $wpdb;
        $args = array(
            "user_id"           => $this->get_user_id(),
            "apikey"            => $this->get_apikey(),
            "service_id"        => $this->get_service_id(),
            "connector_id"      => $this->get_connector_id(),
            "parameters"        => $this->get_parameters(),
            "response"          => is_string($this->get_response()) ? $this->get_response() : json_encode($this->get_response()),
            "error_code"        => $this->get_error_code(),
            "IP"                => $this->get_IP(),
            "execution_time"    => $this->get_execution_time()
        );


        $result = $wpdb->insert( $wpdb->prefix."logs", $args);

        if ( $result )
            // Save the ID
            $this->set_id( $wpdb->insert_id );


        return ($result > 0);
    }
    
}

?>
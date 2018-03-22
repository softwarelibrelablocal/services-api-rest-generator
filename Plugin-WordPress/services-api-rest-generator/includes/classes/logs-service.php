<?php

namespace MGDBQ2JSON\Services;


use MGDBQ2JSON\Models\DBService;
use MGDBQ2JSON\Models\Log;
use MGDBQ2JSON\Models\User;

class Logs extends \Singleton {

    /**
     * Returns a Log object
     *
     * @author  Mauricio Gelves <mg@maugelves.com>
     * @param   $ID     int Log Identifier
     * @return  false|Log
     */
    public function get_by_id( $ID ) {

        global $wpdb;

        $sql = "SELECT * FROM " . $wpdb->base_prefix . "logs where ID = %s";
        $sql = $wpdb->prepare( $sql, $ID );
        $row = $wpdb->get_row( $sql, OBJECT );

        return ($row) ? self::get_log_from_row($row) : false;

    }


    /**
     * Return a Log object from a Query row
     */
    public function get_log_from_row( $row ){

        $log = new Log();
        $log->set_id( $row->ID );
        $log->set_user_id( $row->user_id );
        $log->set_apikey( $row->apikey );
        $log->set_connector_id( $row->connector_id );
        $log->set_date_created( $row->date_created );
        $log->set_error_code( $row->error_code );
        $log->set_execution_time( $row->execution_time );
        $log->set_IP( $row->IP );
        $log->set_parameters( $row->parameters );
        $log->set_response( $row->response );
        $log->set_service_id( $row->service_id );

        return $log;

    }


    /**
     * Function to log API Calls
     * 
     * @author  Mauricio Gelves <mg@maugelves.com>
     * @param   $error_code string
     * @param   $response   string
     * @param   $user       User
     * @param   $service    DBService
     * @since   1.0
     */
    public function log_error($error_code, $response, $user = null, $service = null){

        // Make a new Log Instance
        $log = new Log();
        $log->set_IP( $_SERVER['REMOTE_ADDR'] );
        $log->set_parameters( full_url($_SERVER) );
        $log->set_error_code( $error_code );
        $log->set_response( $response );
        
        if( !empty( $user ) ){
            $log->set_apikey( $user->get_api_key() );
            $log->set_user_id( $user->get_id() );
        }
        
        if( !empty( $service ) ){
            $log->set_service_id( $service->get_ID() );
        }
        
        // Save the log
        $log->save();
        
    }

}

?>
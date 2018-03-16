<?php

namespace MGDBQ2JSON\Services;


use MGDBQ2JSON\Models\User;

class Users extends \Singleton {

    /**
     * Returns a User by its ID
     * @author  Mauricio Gelves <mg@maugelves.com>
     * @param   $user_id int User ID
     * @return  \MGDBQ2JSON\Models\User|false
     * @since   1.0
     */
    public function get_user_by_id ($user_id) {

        // Check parameter
        if( empty( $user_id ) || !is_numeric($user_id) ) return false;

        // Variable
        $user = false;

        $wpuser = get_user_by('id', $user_id);

        if( $wpuser ) {

            // Get User meta
            $umeta = get_user_meta( $user_id );

            // Fill the user fields
            $user = new \MGDBQ2JSON\Models\User();
            $user->set_id( $user_id );
            if( !empty( $umeta['mgdbq2josn_uhashkey'] ) ) $user->set_api_key( $umeta['mgdbq2josn_uhashkey'][0] );
            if( !empty( $umeta['mgdbq2josn_user_state'] ) ) $user->set_blocked( $umeta['mgdbq2josn_user_state'][0] == "bloqueado" );
            if( !empty( $umeta['first_name'] ) ) $user->set_firstname( $umeta['first_name'][0] );
            if( !empty( $umeta['last_name'] ) ) $user->set_lastname( $umeta['last_name'][0] );

        }

        return $user;
    }





    /**
     * Returns a User by its API Key
     *
     * @author  Mauricio Gelves <mg@maugelves.com>
     * @param   $apikey   string          User Api Key
     * @return  \MGDBQ2JSON\Models\User|false
     * @since   1.0
     */
    public function get_user_by_apikey( $apikey ) {

        // Check parameter
        if( empty( $apikey ) ) return false;

        $user = false;


        $args = array(
            'meta_key'     => 'mgdbq2josn_uhashkey',
            'meta_value'   => $apikey
        );
        $users = get_users( $args );

        if( count($users) == 1 ) {

            $user = $this->get_user_by_id( $users[0]->ID );

        }

        return $user;

    }


    /**
     * This functions checks in the database if the user should be blocked
     * due to its misbehaviour with the REST API
     *
     * @author  Mauricio Gelves <mg@maugelves.com>
     * @param   $user   User    Instance of User
     * @since   1.0
     * @return  bool    True if the user was blocked.
     */
    public function should_user_be_blocked( $user ) {

        // Variables
        global $wpdb;
        $result = false;

        // Get Options
        $minutes_interval = get_option("options_mgdbq2json_intervalo_minutos");
        $number_of_attempts = get_option("options_mgdbq2json_nro_intentos");

        if(empty($minutes_interval) || !is_numeric($minutes_interval)) $minutes_interval = 5;
        if(empty($number_of_attempts) || !is_numeric($number_of_attempts)) $minutes_interval = 3;

        $sql = "SELECT 	count(*) as errors_count
                FROM	" . $wpdb->prefix ."logs
                WHERE	(
                        STR_TO_DATE(date_created, '%%Y-%%m-%%d %%H:%%i') BETWEEN 
                        DATE_ADD( current_timestamp, INTERVAL -%d MINUTE ) AND
                        current_timestamp
                        )
                        AND error_code != ''
                        AND user_id = %d";

        $sql = $wpdb->prepare( $sql, $minutes_interval, $user->get_id() );
        $error_count = $wpdb->get_var($sql);

        // If user has more errors than expected block him/her.
        if( $error_count >= $number_of_attempts ) {
            
            // Block the user
            $user->set_blocked(true);
            $user->save();
            
            // Send email to the Administrator
            $adminemail = get_option("admin_email");
            $title      = __("[MGDBQ2JSON] - Bloqueo de usuario", "mgdbq2json");
            $mensaje    =  sprintf( __( "<p>El usuario %s <b>ha sido bloqueado</b> por el incorrecto uso de la aplicación MGDBQ2JSON.</p>","mgdbq2json"), $user->get_fullname() ) ;
            $mensaje    .= sprintf( __( "<p>Pinche en el siguiente enlace para desbloquear el usuario => %s.</p>" ,"mgdbq2json"), admin_url() .  "/user-edit.php?user_id=" . $user->get_id() );
            $header     = array(
                                'Content-Type: text/html; charset=UTF-8'
                                );
            $email = wp_mail($adminemail, $title, $mensaje, $header);
            
            $result = true; // Return this function flag
        }

        return $result;
        
    }

}
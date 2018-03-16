<?php


function check_connector(){
    // Verify Nonce
    wp_verify_nonce($_POST['check_connector_nonce'], 'check_connector');

    // Variables
    $connection = true;

    // Sanitize $_POST Variables
    $engine = sanitize_text_field( $_POST['engine'] );
    $connstring = sanitize_text_field( $_POST['connstring'] );
    $servername = sanitize_text_field( $_POST['servername'] );
    $databasename = sanitize_text_field( $_POST['databasename'] );
    $username = sanitize_text_field( $_POST['username'] );
    $password = sanitize_text_field( $_POST['password'] );


    // Check connection
    try{
        switch ($engine){
            case "oracle":
                if(!oci_connect( $username, $password, $connstring )) $connection = false;
                break;
            case "mysql":
                $timeout = 5;  /* thirty seconds for timeout */
                $conn = new mysqli();
                $conn->options( MYSQLI_OPT_CONNECT_TIMEOUT, $timeout );
                $conn->real_connect($servername, $username, $password, $databasename );
                if($conn->connect_error != "") $connection = false;
                break;
            case "mssql":
                try{
                    $connectionOptions = array(
                        "Database" => $databasename,
                        "Uid" => $username,
                        "PWD" => $password
                    );
                    $conn = sqlsrv_connect($servername, $connectionOptions);
                    if(!$conn) $connection = false;
                }
                catch(Exception $e){
                    $connection = false;
                }
                break;
        }
    }
    catch(Exception $e){
        $connection = false;
    }



    wp_send_json( $connection );
}
add_action( 'wp_ajax_check_connector', 'check_connector' );
add_action( 'wp_ajax_nopriv_check_connector', 'check_connector' );
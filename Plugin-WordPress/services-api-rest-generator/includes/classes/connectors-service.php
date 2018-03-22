<?php
namespace MGDBQ2JSON\Services;


class Connectors extends \Singleton
{

    /**
     * Returns a Connector by its ID
     * @author  Mauricio Gelves <mg@maugelves.com>
     * @param   $connector_id int Connector ID
     * @return  \MGDBQ2JSON\Models\Connector
     */
    public function get_connector_by_id ($connector_id) {
        // Variable
        $connector = false;

        $args = array(
            'post_type'     => 'mg_connector',
            'post_status'   => 'publish',
            'p'             => $connector_id
        );

        $query = new \WP_Query( $args );

        // If connector exist fill the object
        if( $query->have_posts() ){

            $connector = self::get_connector_from_post($query->posts[0]);

        }

        return $connector;
    }


    /**
     * Create a Connector object from a Post row
     *
     * @param   $post \WP_Post
     * @return  \MGDBQ2JSON\Models\Connector
     */
    public function get_connector_from_post( $post ) {

        $connector = new \MGDBQ2JSON\Models\Connector();
        $cmeta = get_fields( $post->ID );

        // Fill the fields
        $connector->set_ID( $post->ID );
        if( !empty( $cmeta["mgdbq2josn_database_name"] ) ) $connector->set_database_name( $cmeta["mgdbq2josn_database_name"] );
        if( !empty( $cmeta["mgdbq2josn_connstring"] ) ) $connector->set_connection_string( $cmeta["mgdbq2josn_connstring"] );
        $connector->set_dbengine( $cmeta["mgdbq2josn_dbengine"] );
        if( !empty( $cmeta["mgdbq2josn_servername"] ) ) $connector->set_servername( $cmeta["mgdbq2josn_servername"] );
        $connector->set_description( $cmeta["mgdbq2josn_descripcion"] );
        if( !empty( $cmeta["mgdbq2josn_username"] ) ) $connector->set_username( $cmeta["mgdbq2josn_username"] );
        if( !empty( $cmeta["mgdbq2josn_password"] ) ) $connector->set_password( $cmeta["mgdbq2josn_password"] );
        $connector->set_title( $post->post_title );

        return $connector;
    }



    /**
     * Executes the query in a MySQL Database
     *
     * @author  Mauricio Gelves <mg@maugelves.com>
     * @param   $dbservice \MGDBQ2JSON\Models\DBService
     * @param   $rendered_query string  Query with the values
     * @since   1.0
     * @return  false|array|WP_Error
     */
    public function get_mysql_query( $dbservice, $rendered_query ) {

        // Variable
        $rows = array();
        $columns = null;
        $connector = $dbservice->get_connector();

        // Create connection
        try{
            $timeout = 5;  /* thirty seconds for timeout */
            $conn = new \mysqli();
            $conn->options( MYSQLI_OPT_CONNECT_TIMEOUT, $timeout );
            $conn->real_connect($connector->get_servername(), $connector->get_username(), $connector->get_password(), $connector->get_database_name() );
            // Check connection
            if ($conn->connect_error) {
                return new \WP_Error( 'db-error', __("Error en la conexión con la base de datos.", "mgdbq2json") );
            }
        }
        catch(\Exception $e){
            return new \WP_Error( 'db-error', __("Error en la conexión con la base de datos.", "mgdbq2json") );
        }


        $result = $conn->query( $rendered_query );

        if ($result->num_rows > 0) {

            while($row = $result->fetch_assoc() ) {
                array_push( $rows, $row );
            }

        }

        $conn->close();

        return $rows;

    }


    /**
     * Executes the query in an Oracle Database
     *
     * @author  Mauricio Gelves <mg@maugelves.com>
     * @param   $dbservice \MGDBQ2JSON\Models\DBService
     * @param   $rendered_query string  Query with the values
     * @since   1.0
     * @return  false|array|WP_Error
     */
    public function get_oracle_query( $dbservice, $rendered_query ) {

        // Variable
        $rows = array();
        $columns = null;
        $connector = $dbservice->get_connector();


        putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
        putenv("NLS_CHARACTERSET=AL32UTF8");
        try{
            $connect = oci_connect( $connector->get_username(), $connector->get_password(), $connector->get_connection_string() );
            if (!$connect) {
                return new \WP_Error( 'db-error', __("Error en la conexión con la base de datos.", "mgdbq2json") );
            }
        }
        catch(\Exception $e){
            return new \WP_Error( 'db-error', __("Error en la conexión con la base de datos.", "mgdbq2json") );
        }


        // Check connection
        if( !$connector ) {
            return new \WP_Error('db-error');
        }

        $result = oci_parse( $connect, $rendered_query );
        oci_execute($result);

        while($row = oci_fetch_assoc( $result ) ) {
            array_push( $rows, $row );
        }

        return $rows;

    }


    /**
     * Executes the query in an MSSQL Database
     *
     * @author  Mauricio Gelves <mg@maugelves.com>
     * @param   $dbservice \MGDBQ2JSON\Models\DBService
     * @param   $rendered_query string  Query with the values
     * @since   1.0
     * @return  false|array|WP_Error
     */
    public function get_mssql_query( $dbservice, $rendered_query ) {

        // Variable
        $conector = $dbservice->get_connector();
        $rows = array();

        $connectionOptions = array(
            "Database" => $conector->get_database_name(),
            "Uid" => $conector->get_username(),
            "PWD" => $conector->get_password()
        );

        try{

            $conn = sqlsrv_connect($conector->get_servername(), $connectionOptions);
            $getResults= sqlsrv_query($conn, $rendered_query);

            if ($getResults == FALSE)
                return new \WP_Error( 'db-error', __("Error en la conexión con la base de datos.", "mgdbq2json") );
            
            while ($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)) {
                array_push( $rows, $row );
            }

            // Free the resources
            sqlsrv_free_stmt($getResults);
        }
        catch(\Exception $ex){
            return new \WP_Error( 'db-error', __("Error en la conexión con la base de datos.", "mgdbq2json") );
        }

        return $rows;

    }

}
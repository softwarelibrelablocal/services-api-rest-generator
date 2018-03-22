<?php
namespace MGDBQ2JSON\Services;


class DBServices extends \Singleton
{

    /**
     * Returns a DBService by its ID
     * @author  Mauricio Gelves <mg@maugelves.com>
     * @param   $dbservice_id int DBService ID
     * @return  \MGDBQ2JSON\Models\DBService
     * @since   1.0
     */
    public function get_dbservice_by_id ($dbservice_id) {

        // Check parameter
        if( empty( $dbservice_id ) || !is_numeric($dbservice_id) ) return false;

        // Variable
        $dbservice = false;

        $args = array(
            'post_type'     => 'mg_service',
            'post_status'   => 'publish',
            'p'             => $dbservice_id
        );

        $query = new \WP_Query( $args );

        // If connector exist fill the object
        if( $query->have_posts() ){

            $dbservice = self::get_dbservice_from_post($query->posts[0]);

        }

        return $dbservice;
    }


    /**
     * Gets a DBService searching by its service-name
     * 
     * @author  Mauricio Gelves <mg@maugelves.com>
     * @param   $name string    Service name.
     * @return  bool|\MGDBQ2JSON\Models\DBService
     * @since   1.0
     */
    public function get_dbservice_by_name ( $name ) {

        // Check Parameter
        if( empty( $name ) || !is_string( $name ) ) return false;

        // Variable
        $dbservice = false;

        $args = array(
            'meta_key'      => 'mgdbq2josn_servicename',
            'meta_value'    => $name,
            'post_type'     => 'mg_service',
            'post_status'   => 'publish',
        );

        $query = new \WP_Query( $args );

        // If connector exist fill the object
        if( $query->have_posts() ){

            $dbservice = self::get_dbservice_from_post($query->posts[0]);

        }

        return $dbservice;

    }


    /**
     * Create a DBService object from a Post row
     *
     * @param   $post \WP_Post
     * @return  \MGDBQ2JSON\Models\DBService
     */
    public function get_dbservice_from_post( $post ) {

        $dbservice = new \MGDBQ2JSON\Models\DBService();
        $cmeta = get_fields( $post->ID );

        // Fill the fields
        $dbservice->set_ID( $post->ID );
        $dbservice->set_description( $cmeta["mgdbq2josn_descripcion"] );
        $dbservice->set_connector_id( $cmeta["mgdbq2josn_connector"][0] );
        $dbservice->set_query( $cmeta["mgdbq2josn_query"] );
        $dbservice->set_slug( $post->post_name );
        $dbservice->set_title( $post->post_title );
        $dbservice->set_users( empty( $cmeta["mgdbq2josn_service_users"] ) ? array() : $cmeta["mgdbq2josn_service_users"] );

        return $dbservice;

    }

}
<?php

namespace MGDBQ2JSON\Models;
use MGDBQ2JSON\Services\Connectors;

class DBService {

    // Variables
    private $ID             = 0;
    private $connector      = null;
    private $connector_id   = 0;
    private $description    = "";
    private $params         = null;
    private $query          = "";
    private $slug           = "";
    private $title          = "";
    private $users          = array();

    
    
    // GETTERS AND SETTERS

    /**
     * @return int
     */
    public function get_ID()
    {
        return $this->ID;
    }

    /**
     * @param int $ID
     */
    public function set_ID($ID)
    {
        $this->ID = $ID;
    }

    /**
     * @return string
     */
    public function get_title()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function set_title($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function get_description()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function set_description($description)
    {
        $this->description = $description;
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
        if( null == $this->connector ) {
            $this->connector = Connectors::getInstance()->get_connector_by_id( $this->connector_id );
        }
        return $this->connector;
    }

    /**
     * @return array()
     */
    public function get_params()
    {
        if( is_null( $this->params ) ) {
            // Get the mandatory parameters from the dbservice query
            preg_match_all("/\{\{\s*(.*?)\s*\}\}/", $this->get_query(), $params);
            $this->params = $params[1];
        }
        return $this->params;
    }

    /**
     * @return string
     */
    public function get_query()
    {
        return $this->query;
    }

    /**
     * @param string $query
     */
    public function set_query($query)
    {
        $this->query = $query;
    }

    /**
     * @return string
     */
    public function get_slug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function set_slug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return array
     */
    public function get_users()
    {
        return $this->users;
    }

    /**
     * @param array $users
     */
    public function set_users($users)
    {
        $this->users = $users;
    }
    
    
    
    // METHODS
    /**
     * This function check if the REST API call brings all the
     * parameters to serve the Query
     *
     * @author  Mauricio Gelves <mg@maugelves.com>
     * @param   $get array $_GET with all the querystrings
     * @return  bool
     * @since   1.0
     */
    public function check_parameters( $get ) {

        // Variable
        $result = true;

        // Check if all the parameters exists in the $_GET variable
        foreach ($this->get_params() as $param ):
            if( empty( $get[$param] ) ) {
                $result = false;
                break;
            }
        endforeach;

        return $result;
    }


    /**
     * Check if a specific user is allowed to consume the service
     *
     * @author      Mauricio Gelves <mg@maugelves.com>
     * @param       $user_id int User ID
     * @return      bool
     * @since       1.0
     */
    public function has_user( $user_id ) {

        // Variable
        $result = false;

        foreach($this->get_users() as $user) {

            if ( $user['ID'] == $user_id ) {
                $result = true;
                break;
            }

        }

        return $result;
    }

}
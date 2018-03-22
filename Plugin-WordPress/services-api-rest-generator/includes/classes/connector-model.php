<?php
namespace MGDBQ2JSON\Models;

class Connector
{

    // Variables
    private $ID                 = 0;
    private $title              = "";
    private $description        = "";
    private $connection_string  = null;
    private $dbengine           = "";
    private $database_name      = "";
    private $password           = "";
    private $servername         = "";
    private $username           = "";
    
    
    
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
     * @return null
     */
    public function get_connection_string()
    {
        return $this->connection_string;
    }

    /**
     * @param null $connection_string
     */
    public function set_connection_string($connection_string)
    {
        $this->connection_string = $connection_string;
    }

    /**
     * @return string
     */
    public function get_dbengine()
    {
        return $this->dbengine;
    }

    /**
     * @param string $dbengine
     */
    public function set_dbengine($dbengine)
    {
        $this->dbengine = $dbengine;
    }

    /**
     * @return string
     */
    public function get_database_name()
    {
        return $this->database_name;
    }

    /**
     * @param string $database_name
     */
    public function set_database_name($database_name)
    {
        $this->database_name = $database_name;
    }

    /**
     * @return string
     */
    public function get_servername()
    {
        return $this->servername;
    }

    /**
     * @param string $servername
     */
    public function set_servername($servername)
    {
        $this->servername = $servername;
    }

    /**
     * @return string
     */
    public function get_username()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function set_username($username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function get_password()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function set_password($password)
    {
        $this->password = $password;
    }
    
}
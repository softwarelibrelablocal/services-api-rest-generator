<?php
/**
 * Created by PhpStorm.
 * User: mauriciogelves
 * Date: 30/11/2016
 * Time: 13:18
 */

namespace MGDBQ2JSON\Models;


class User
{

    // Variables
    private $id         = 0;
    private $apikey     = "";
    private $blocked    = false;
    private $firstname  = "";
    private $lastname   = "";


    // Getters and Setters
    /**
     * @return int
     */
    public function get_id()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function set_id($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function get_api_key()
    {
        return $this->apikey;
    }

    /**
     * @param string $apikey
     */
    public function set_api_key($apikey)
    {
        $this->apikey = $apikey;
    }

    /**
     * @return boolean
     */
    public function is_blocked()
    {
        return $this->blocked;
    }

    /**
     * @param boolean $blocked
     */
    public function set_blocked($blocked)
    {
        $this->blocked = $blocked;
    }

    /**
     * @return string
     */
    public function get_firstname()
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     */
    public function set_firstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @return string
     */
    public function get_lastname()
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     */
    public function set_lastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * @return string
     */
    public function get_fullname(){
        return $this->get_firstname() . " " . $this->get_lastname();
    }




    // Methods
    /**
     * Save the User instance values
     */
    public function save(){

        $isblocked = $this->is_blocked()?"bloqueado":"activo";
        update_user_meta( $this->get_id(), "mgdbq2josn_user_state", $isblocked );

    }

}
<?php

use Phalcon\Mvc\Model;

class Users extends Model{
    public $username;
    public $firstname;
    public $lastname;
    public $password;
    public $email;

    public function initialize()
    {
        $this->setConnectionService('db_f2nt');

        /**
         * param1 : primary_key
         * param2 : Model
         * param3 : foreign_key
         */
        $this->hasMany('username','Tickets','escalated_to');
    }

    public function getFullName(){
        return $this->firstname." ".$this->lastname;
    }

    
}

?>
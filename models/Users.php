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
        
        // $user->tickets
        $this->hasMany('username','Tickets','originator',[
            'alias'=>'tickets'
        ]);

        $this->hasMany('username','Tickets','escalated_to',[
            'alias'=>'assigned_tickets'
        ]);

        $this->hasMany('username', 'Tickets', 'escalated_to', [
            'reusable'=>true,
            'alias' => 'open_tickets',
            'params' => [
                'conditions' => "status = :status:",
                'bind'=>[
                    'status'=>'OPEN'
                ]
            ]
        ]);

    }

    public function getFullName(){
        return $this->firstname." ".$this->lastname;
    }

    
}

?>
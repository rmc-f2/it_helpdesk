<?php

use Phalcon\Mvc\Model;

class Tickets extends Model{

    public function initialize()
    {
        $this->setConnectionService('db_f2nt_itd');

    }

    
}

?>
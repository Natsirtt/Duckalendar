<?php

class Event {
    private $_owner;
    private $_beginDate;
    private $_name;
    private $_description;
    private $_beginTime;
    private $_endTime;
    private $_endDate;
    
    function __construct($login, $date, $name) {
        $this->_owner = $login;
        $this->_beginDate = $date;
        $this->_name = $name;
        
        require_once 'bddconnection.php';
        
        if (!$bdd_connected) {
            $this->_owner = "";
            $this->_beginDate = "";
            $this->_name = "";
        } else {
//            $req = 'SELECT * from events WHERE login="'.$this->_owner." AND date="
        }
    }
}

?>

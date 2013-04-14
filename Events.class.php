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
        $this->_beginDate = new DateTime($date);
        $this->_name = $name;
        
        require_once 'bddconnection.php';
        
        if (!$bdd_connected) {
            $this->_owner = "";
            $this->_beginDate = NULL;
            $this->_name = "";
        } else {
            $dateStr = $this->_beginDate->format("Y-m-d");
            $req = 'SELECT * from events WHERE login="'.$this->_owner." AND date=".$dateStr.'"';
            $reqres = $bdd_connection->query($req);
            if ($res = $reqres->fetch()) {
                $this->_beginTime = $res['beginTime'];
                //TODO
            } else {
                $this->_owner = "";
                $this->_beginDate = NULL;
                $this->_name = "";
            }
        }
    }
    
    public function getLogin() {
        return $this->_owner;
    }
    
    public function getBeginDate() {
        return $this->_beginDate;
    }
    
    public function getEventName() {
        return $this->_name;
    }
    
    public function getDescription() {
        return $this->_description;
    }
    
    public function getBeginTime() {
        return $this->_beginTime;
    }
    
    public function getEndTime() {
        return $this->_endTime;
    }
    
    public function getEndDate() {
        return $this->_endDate;
    }
}

?>

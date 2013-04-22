<?php

require_once 'BddConnection.class.php';
require_once 'NoSuchEventException.class.php';
require_once 'BddInsertionFailedException.class.php';
require_once 'EventAlreadyInDatabaseException.class.php';
require_once 'BddNotConnectedException.class.php';
require_once 'BddDeleteFailedException.class.php';

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

//Définition standard des autres attributs
        $this->_description = "";
        $this->_beginTime = new DateTime("12:00");
        $this->_endTime = new DateTime("12:00");
        $this->_endDate = new DateTime($date);
    }

    /**
     * @return string La chaine de caractère représentant l'évènement
     */
    public function toString() {
        $res = "Begin time = " . $this->_beginTime->format("H:i");
        $res = $res . " End time = " . $this->_endTime->format("H:i");
        $res = $res . " End date = " . $this->_endDate->format("d-m-Y");
        $res = $res . " Begin date = " . $this->_beginDate->format("d-m-Y");
        $res = $res . " Description = " . $this->_description;
        $res = $res . " Owner = " . $this->_owner;
        $res = $res . " Event name = " . $this->_name;
        return $res;
    }

    /**
     * Permet d'initialiser l'évènement d'après la base de données.
     * La table doit avoir 
     * L'exception NoSuchEventException est levée lorsque la
     * clé (owner + beginDate + name) n'existe pas dans la table.
     * @param BddConnection $bddconnection La connexion à la base de données
     * @param string $tableName La table des évènements de la base de données
     * @throws NoSuchEventException
     */
    public function constructFromDatabase($bddconnection, $tableName) {
        if ($bddconnection->isConnected()) {
            $beginDateStr = $this->_beginDate->format("Y-m-d");
            $reqres = $bddconnection->query('SELECT * from ' . $tableName . ' WHERE login="' . $this->_owner . '" AND `date`="' . $beginDateStr . '" AND name="' . $this->_name . '"');
            $res = $reqres->fetch();
            if ($res) {
                $this->_beginTime = new DateTime($res['beginTime']);
                $this->_description = $res['desc'];
                $this->_endTime = new DateTime($res['endTime']);
                $this->_endDate = new DateTime($res['endDate']);
            } else {
                throw new NoSuchEventException();
            }
        } else {
            throw new BddNotConnectedException();
        }
    }

    /**
     * Insère l'évènement construit dans la base de données.
     * Lève une EventAlreadyInDatabaseException si la clé de l'évènement
     * (login + beginDate + eventName) est déjà utilisée.
     * Lève une BddInsertionFailedException si l'insertion dans la base
     * à échoué.
     * Lève une BddNotConnectedException si la connexion passée en
     * paramètre n'est pas connectée à une base de données.
     * 
     * @param BddConnection $bddconnection La connexion à la base de données
     * @param string $tableName La table des évènements de la base de données
     * @throws EventAlreadyInDatabaseException
     * @throws BddInsertionFailedException
     * @throws BddNotConnectedException
     */
    public function putInDatabase($bddconnection, $tableName) {
        if ($bddconnection->isConnected()) {
            $resreq = $bddconnection->query("SELECT * FROM $tableName WHERE login='"
                                                .$this->getLogin()
                                            ."' AND `date`='"
                                                .$this->getBeginDate()->format("Y-m-d")
                                            ."' AND name='"
                                                .$this->getEventName()
                                            ."'");
            $res = $resreq->fetch();
            if ($res) {
                throw new EventAlreadyInDatabaseException();
            }

            $sql = 'INSERT INTO ' . $tableName
                   .' (login, `date`, name, `desc`, beginTime, endTime, endDate) VALUES (?, ?, ?, ?, ?, ?, ?)';
            $values = array($this->getLogin(),
                            $this->getBeginDate()->format("Y-m-d"),
                            $this->getEventName(),
                            $this->getDescription(),
                            $this->getBeginTime()->format("H:i"),
                            $this->getEndTime()->format("H:i"),
                            $this->getEndDate()->format("Y-m-d"));
            $status = $bddconnection->preparedQuery($sql, $values);
            if (!$status) {
                throw new BddInsertionFailedException();
            }
        } else {
            throw new BddNotConnectedException();
        }
    }
    
    /**
     * Modifie l'évènement en base de données.
     * Lève une BddNotConnectedException si la connexion passée en paramètre
     * n'est pas connectée à la base de données.
     * Lève une NoSuchEventException si la clé (login + name + date) n'est
     * pas trouvée en base de données.
     * Lève une BddInsertionFailedException si la mise à jour a échoué.
     * @param BddConnection $bddconnection La connexion à la base de données
     * @param string $tableName La table des évènements de la base de données
     * @throws BddNotConnectedException
     * @throws NoSuchEventException
     * @throws BddInsertionFailedException
     */
    public function updateDatabase($bddconnection, $tableName) {
        if (!$bddconnection->isConnected()) {
            throw new BddNotConnectedException();
        }
        $sql = "UPDATE $tableName SET desc=?, beginTime=?, endTime=?, endDate=?";
        $values = array($this->getDescription(),
                        $this->getBeginTime()->format("H:i"),
                        $this->getEndTime()->format("H:i"),
                        $this->getEndDate()->format("Y-m-d"));
        $status = $bddconnection->preparedQuery($sql, $values);
        if (!$status) {
            throw new BddInsertionFailedException();
        }
    }
    
    /**
     * Supprime l'évènement en base de données.
     * Lève une BddNotConnectedException si la connexion passée en paramètre
     * n'est pas connectée à la base de données.
     * Lève une NoSuchEventException si la clé (login + name + date) n'est
     * pas trouvée en base de données.
     * Lève une BddDeleteFailedException si la suppression a échoué.
     * @param BddConnection $bddconnection La connexion à la base de données
     * @param string $tableName La table des évènements de la base de données
     * @throws BddNotConnectedException
     * @throws NoSuchEventException
     * @throws BddDeleteFailedException
     */
    public function deleteIntoDatabase($bddconnection, $tableName) {
        if (!$bddconnection->isConnected()) {
            throw new BddNotConnectedException();
        }
        $sql = "DELETE FROM $tableName WHERE login=? AND `date`=? AND name=?";
        $values = array($this->getLogin(),
                        $this->getBeginDate()->format("Y-m-d"),
                        $this->getEventName());
        $status = $bddconnection->preparedQuery($sql, $values);
        if (!$status) {
            throw new BddDeleteFailedException();
        }
    }

    /**
     * Retourne le login de l'utilisateur ayant enregistré cet évènement.
     * @return string
     */
    public function getLogin() {
        return $this->_owner;
    }

    /**
     * Retourne un objet DateTime représentant la date de début
     * de l'évènement.
     * @return DateTime
     */
    public function getBeginDate() {
        return $this->_beginDate;
    }

    /**
     * Retourne le nom de l'évènement définit par l'utilisateur.
     * @return string
     */
    public function getEventName() {
        return $this->_name;
    }

    /**
     * Retourne le texte de description de l'évènement.
     * @return string
     */
    public function getDescription() {
        return $this->_description;
    }

    /**
     * Retourne un objet DateTime représentant l'heure de début
     * de l'évènement.
     * @return DateTime
     */
    public function getBeginTime() {
        return $this->_beginTime;
    }

    /**
     * Retourne un objet DateTime représentant l'heure de fin de
     * l'évènement.
     * @return DateTime
     */
    public function getEndTime() {
        return $this->_endTime;
    }

    /**
     * Retourne un objet DateTime représentant la date de fin
     * de l'évènement.
     * @return DateTime
     */
    public function getEndDate() {
        return $this->_endDate;
    }

    /**
     * Change le texte de description de l'évènement.
     * @param string $desc La nouvelle description
     */
    public function setDescription($desc) {
        $this->_description = $desc;
    }

    /**
     * Change l'heure de début de l'évènement d'après une chaine de
     * caractères de la forme "hh:mm[:ss]". Les secondes peuvent être
     * passées en paramètre mais sont ignorées.
     * @param string $time La chaine de caractères représentant l'heure.
     */
    public function setBeginTime($time) {
        $this->_beginTime = new DateTime($time);
    }

    /**
     * Change l'heure de fin de l'évènement d'après une chaine de
     * caractères de la forme "hh:mm[:ss]". Les secondes peuvent être
     * passées en paramètre mais sont ignorées.
     * @param string $time La chaine de caractères représentant l'heure.
     */
    public function setEndTime($time) {
        $this->_endTime = new DateTime($time);
    }

    /**
     * Change la date de fin de l'évènement d'après une chaine
     * de caractères de la forme "dd-mm-yyyy".
     * @param string $date La chaine de caractères représentant la date.
     */
    public function setEndDate($date) {
        $this->_endDate = new DateTime($date);
    }

}

?>

<?php

require_once 'BddConnectionFailedException.class.php';

/**
 * Permet de représenter une connexion PDO à une table d'une base de donnée MySQL
 * via PDO.
 * Toute les requêtes sont des requêtes préparées (quand cela est pertinant).
 */
class BddConnection {
    //Attributs statiques
    public static $mysql = "mysql";
    
    //Attributs
    private $_bddConnection;
    private $_isConnected;
    
    /**
     * Initialise une connexion à la base de donnée dont les caractéristiques
     * sont passées en paramètre.
     * 
     * @param string $bddType Le type de la base de données (exemple, "mysql")
     * @param string $host L'emplacement hôte de la base de données
     * @param string $databaseName Le nom de la base
     * @param string $username L'utilisateur se connectant à la base
     * @param string $passwd Le mot de passe de connexion
     * @throws BddConnectionFailedException
     */
    function __construct($bddType, $host, $databaseName, $username, $passwd) {
        $dsn = $bddType.':host='.$host.';dbname='.$databaseName;
        try {
            $this->_bddConnection = new PDO($dsn, $username, $passwd);
            $this->_isConnected = true;
        } catch (PDOException $e) {
            $this->_isConnected = false;
            $this->_bddConnection = NULL;
//            throw new BddConnectionFailedException("Erreur de connexion à la base de données");
            throw new BddConnectionFailedException();
        }
    }
    
    /**
     * Indique si la connection est établie (true) ou non (false).
     * @return boolean
     */
    public function isConnected() {
        return $this->_isConnected;
    }
    
    /**
     * Exécute une requête préparée.
     * @param string La requête à exécuter
     * @param array Les valeurs à entrer dans la table
     * @return boolean true si la requête a correctement été exécutée, false sinon.
     */
    public function preparedQuery($sql, array $values) {
        if (!$this->isConnected()) {
            throw new IllegalStateException();
        }
        $reqprep = $this->_bddConnection->prepare($sql);
        return $reqprep->execute($values);
    }
    
    /**
     * 
     * @param string La requête à exécuter
     * @return PDOStatement L'objet PDOStatement permettant de récupérer le résultat de la requête.
     */
    public function query($sql) {
        if (!$this->isConnected()) {
            throw new IllegalStateException();
        }
        return $this->_bddConnection->query($sql);        
    }
}

?>

<?php

namespace Chirpchat\Model;

/**
 * Classe gérant la connexion à la base de données.
 */
class Database {

    private static $_instance = null;
    private $dbAccess;
    private \PDO $connection;
    /**
     * Crée une nouvelle instance de la classe Database.
     */
    public function __construct()
    {
        $this->dbAccess = parse_ini_file(realpath('_assets/cred/db.ini'));
        $this->connection = new \PDO($this->dbAccess['dsn'], $this->dbAccess['username'], $this->dbAccess['password'])  or die(mysqli_error($this->connection));
    }
    /**
     * Récupère l'instance unique de la classe Database.
     *
     * @return Database L'instance de la classe Database.
     */
    public static function getInstance(): Database
    {
        if(is_null(self::$_instance)) self::$_instance = new Database();

        return self::$_instance;
    }
    /**
     * Établit la connexion à la base de données.
     */
    public function connect() : void {
        $this->connection = new \PDO($this->dbAccess['dsn'], $this->dbAccess['username'], $this->dbAccess['password']) or die(mysqli_error($this->connection));
    }
    /**
     * Récupère la connexion à la base de données.
     *
     * @return \PDO La connexion à la base de données.
     */
    public function getConnection(){
        // TODO Reconnexion si la connexion est perdu
        //Sif(!$this->connection->) $this->connect();

        return $this->connection;
    }
}

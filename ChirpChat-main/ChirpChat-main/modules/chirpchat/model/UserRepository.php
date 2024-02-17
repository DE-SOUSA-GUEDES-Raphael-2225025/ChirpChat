<?php

namespace ChirpChat\Model;
/**
 * Repository de gestion des utilisateurs sur la plateforme.
 */
class UserRepository{
    /**
     * Crée une nouvelle instance de la classe UserRepository.
     *
     * @param \PDO $connection L'objet de connexion à la base de données.
     */
    public function __construct(private \PDO $connection){ }
    /**
     * Vérifie si un utilisateur avec l'adresse e-mail donnée existe.
     *
     * @param string $email L'adresse e-mail de l'utilisateur à vérifier.
     * @return bool Vrai si l'utilisateur existe, sinon faux.
     */
    public function doesUserExist(string $email) : bool{
        $statement = $this->connection->prepare("SELECT * FROM Utilisateur WHERE EMAIL= ?");
        $statement->execute([$email]);
        if($statement->fetch()) return true;
        return false;
    }
    /**
     * Vérifie si l'ID utilisateur est valide en vérifiant le mot de passe.
     *
     * @param string $email L'adresse e-mail de l'utilisateur.
     * @param string $password Le mot de passe à vérifier.
     * @return bool Vrai si l'ID utilisateur est valide, sinon faux.
     */
    public function isUserIdValid(string $email, string $password) : bool{
        if(!$this->doesUserExist($email)) return false;
        $statement = $this->connection->prepare("SELECT password FROM Utilisateur WHERE EMAIL = ?");
        $statement->execute([$email]);
        return password_verify($password, $statement->fetch()['password']);
    }
    /**
     * Enregistre un nouvel utilisateur.
     *
     * @param string $username Le nom d'utilisateur.
     * @param string $pseudonyme Le pseudonyme.
     * @param string $email L'adresse e-mail.
     * @param string $password Le mot de passe.
     * @param string $birthdate La date de naissance.
     */
    public function register($username, $pseudonyme, $email, $password, $birthdate) : bool{
        if($this->doesUserExist($email)) return false;
        $statement = $this->connection->prepare("INSERT INTO Utilisateur VALUES (?, ?, ?, ?, ?, ?, '', ?, ?, ?)" );
        try{
            $statement->execute([uniqid(),$email,$username, $pseudonyme, password_hash($password,PASSWORD_BCRYPT), $birthdate,  date('Y-m-d H:i:s'),  date('Y-m-d H:i:s'),'USER']);
            return true;
        }catch (\Exception $e){
            return false;
        }
    }
    /**
     * Obtient l'ID de l'utilisateur à partir de l'adresse e-mail et du mot de passe.
     *
     * @param string $email L'adresse e-mail de l'utilisateur.
     * @param string $password Le mot de passe de l'utilisateur.
     * @return string L'ID de l'utilisateur.
     */
    public function getID(string $email, $password) : string{
        if(!$this->isUserIdValid($email, $password)){
            require '_assets/exception/UserDoesNotExistException.php';
        }

        $statement = $this->connection->prepare("SELECT ID FROM Utilisateur WHERE EMAIL = ?");
        $statement->execute([$email]);
        return $statement->fetch()['ID'];
    }
    /**
     * Obtient un objet User à partir de l'ID utilisateur.
     *
     * @param string $id L'ID de l'utilisateur.
     * @return User|null Un objet User si l'utilisateur existe, sinon null.
     */
    public function getUser(string $id) : ?user {
        $statement = $this->connection->prepare("SELECT EMAIL, USERNAME, PSEUDONYME, ROLE, DESCRIPTION FROM Utilisateur WHERE ID = ?");
        $statement->execute([$id]);

        if($row = $statement->fetch()){
            return new User($id, $row['USERNAME'], $row['EMAIL'], $row['PSEUDONYME'],$row['ROLE'], $row['DESCRIPTION']);
        }
        return null;
    }
    /**
     * Modifie le nom d'utilisateur de l'utilisateur.
     *
     * @param User $user L'utilisateur dont le nom d'utilisateur doit être modifié.
     * @param string $newUsername Le nouveau nom d'utilisateur.
     */
    public function setUsername(User $user, string $newUsername) : void{
        $statement = $this->connection->prepare("UPDATE Utilisateur SET USERNAME=? WHERE ID=?");
        $statement->execute([$newUsername, $user->getUserID()]);
    }
    /**
     * Modifie la description de l'utilisateur.
     *
     * @param User $user L'utilisateur dont la description doit être modifiée.
     * @param string $newDescription La nouvelle description.
     */
    public function setDescription(User $user, string $newDescription) : void{
        $statement = $this->connection->prepare("UPDATE Utilisateur SET DESCRIPTION=? WHERE ID=?");
        $statement->execute([$newDescription, $user->getUserID()]);
    }

    public function addVerificationCode(string $email, string $code){
        $statement = $this->connection->prepare("INSERT INTO RECUPERATION VALUES (?,?,NOW())");
        $statement->execute([$email,$code]);
    }

    public function isRecuperationCodeValid(string $email, string $code) : bool{
        $statement = $this->connection->prepare("SELECT * FROM RECUPERATION WHERE EMAIL = ? AND CODE = ? AND DATE < DATE_SUB(NOW(), INTERVAL 10 MINUTE )");
        $statement->execute([$email, $code]);
        if($statement->fetch()) return true;
        return false;
    }

    public function updateUserPassword($email, $newPassword) : void{
        $statement = $this->connection->prepare('UPDATE Utilisateur SET password = ? WHERE email = ?');
        $statement->execute([password_hash($newPassword,PASSWORD_BCRYPT), $email]);
    }

    public function deleteUser(string $userID){
        $statement = $this->connection->prepare('DELETE FROM Utilisateur WHERE ID = ?');
        $statement->execute([$userID]);
    }

    public function isUserSessionValid(string $userId){
        $statement = $this->connection->prepare('SELECT * FROM Utilisateur WHERE ID = ?');
        $statement->execute([$userId]);
        if($statement->fetch()) return true;
        return false;
    }

    public function setNewConnectionDate(string $userId){
        $statement = $this->connection->prepare('UPDATE Utilisateur SET derniere_connexion = ? WHERE ID = ?');
        $statement->execute([date('Y-m-d H:i:s'), $userId]);
    }











}








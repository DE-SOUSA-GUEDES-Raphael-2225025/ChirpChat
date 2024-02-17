<?php

namespace ChirpChat\Model;
use ChirpChat\Model\PrivateMessage;
/**
 * Repository de gestion des messages privés entre utilisateurs.
 */
class PrivateMessageRepository{
    /**
     * Crée une nouvelle instance de la classe PrivateMessageRepository.
     *
     * @param \PDO $connection L'objet de connexion à la base de données.
     */
    public function __construct(private \PDO $connection){ }

    /**
     * Récupère tous les messages privés échangés entre deux utilisateurs.
     *
     * @param string $firstUserID ID du premier utilisateur.
     * @param string $secondUserID ID du deuxième utilisateur.
     *
     * @return PrivateMessage[] Un tableau d'objets PrivateMessage représentant les messages privés.
     */
    public function getPrivateMessageBetweenUsers(string $firstUserID, string $secondUserID) : array{
        $privateMessageList = [];
        $userRepo = new UserRepository($this->connection);
        $statement = $this->connection->prepare("SELECT * FROM PrivateMessage WHERE target IN (:target,:sender) AND sender IN (:target,:sender) ORDER BY creationDate DESC");
        $statement->bindValue(':target', $firstUserID);
        $statement->bindValue(':sender', $secondUserID);
        $statement->execute();

        while($row = $statement->fetch()){
            $privateMessageList[] = new PrivateMessage($userRepo->getUser($row['sender']), $userRepo->getUser($row['target']), $row['message']);
        }

        return $privateMessageList;
    }
    /**
     * Récupère la liste des utilisateurs qui ont envoyé des messages à l'utilisateur donné.
     *
     * @param string $userID ID de l'utilisateur destinataire des messages.
     *
     * @return User[] Un tableau d'objets User représentant les utilisateurs qui ont envoyé des messages.
     */
    public function getUsersWhoSendMessageTo(string $userID) : array{
        $userRepo = new \ChirpChat\Model\UserRepository($this->connection);
        $statement = $this->connection->prepare("SELECT DISTINCT sender FROM PrivateMessage WHERE target = :userID 
                                           UNION SELECT target FROM PrivateMessage WHERE sender = :userID AND sender NOT IN 
                                           (SELECT DISTINCT  sender FROM PrivateMessage WHERE target = :userID)");

        $statement->bindParam(':userID', $userID);
        $statement->execute();

        $usersList = [];

        while($row = $statement->fetch()){
            $usersList[] = $userRepo->getUser($row['sender']);
        }

        return $usersList;
    }
    /**
     * Envoie un message privé à un utilisateur spécifié.
     *
     * @param string $senderID ID de l'utilisateur qui envoie le message.
     * @param string $targetID ID de l'utilisateur destinataire du message.
     * @param string $message Contenu du message privé.
     */
    public function sendMessageToUser(string $senderID, string $targetID, string $message){
        $statement = $this->connection->prepare('INSERT INTO PrivateMessage (sender,target,message,creationDate) VALUES (?,?,?,?)');
        $statement->execute([$senderID, $targetID, $message, date("DD-MM-YYYY")]);
    }

}

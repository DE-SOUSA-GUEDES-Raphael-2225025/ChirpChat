<?php

namespace ChirpChat\Model;

/**
 * Représente un utilisateur de la plateforme.
 */
class User{
    /**
     * Crée une nouvelle instance de la classe User.
     *
     * @param string $userID ID de l'utilisateur.
     * @param string $username Nom d'utilisateur.
     * @param string $email Adresse e-mail de l'utilisateur.
     * @param string $pseudo Pseudo de l'utilisateur.
     * @param string|null $description Description de l'utilisateur (peut être nulle).
     */
    public function __construct(private readonly string $userID,
                                private readonly string $username,
                                private readonly string $email,
                                private readonly string $pseudo,
                                private readonly string $role,
                                private readonly ?string $description){}
    /**
     * Récupère l'ID de l'utilisateur.
     *
     * @return string L'ID de l'utilisateur.
     */
    public function getUserID() : string{
        return htmlspecialchars($this->userID);
    }
    /**
     * Récupère le nom d'utilisateur.
     *
     * @return string Le nom d'utilisateur.
     */
    public function getUsername() : string{
        return htmlspecialchars($this->username);
    }
    /**
     * Récupère l'adresse e-mail de l'utilisateur.
     *
     * @return string L'adresse e-mail de l'utilisateur.
     */
    public function getEmail() : string{
        return htmlspecialchars($this->email);
    }
    /**
     * Récupère le pseudo de l'utilisateur.
     *
     * @return string Le pseudo de l'utilisateur.
     */
    public function getPseudo() : string{
        return htmlspecialchars($this->pseudo);
    }
    /**
     * Récupère la description de l'utilisateur.
     *
     * @return string La description de l'utilisateur (peut être nulle).
     */
    public function getDescription() : string{
        return htmlspecialchars($this->description);
    }
    /**
     * Récupère le chemin vers la photo de profil de l'utilisateur.
     *
     * @return string Le chemin vers la photo de profil de l'utilisateur.
     */
    public function getProfilPicPath() : string {
        foreach(new \DirectoryIterator('_assets/images/user_pic/') as $userPIC){
            if($userPIC->getFilename() === $this->userID . ".jpg"){
                return '_assets/images/user_pic/' . $this->userID . ".jpg";
            }
        }
        return  "_assets/images/user_pic/default.png";
    }

    /**
     * Renvoie le role de l'utilisateur
     *
     * @return string
     */
    public function getRole() : string{
        return $this->role;
    }

    public function isAdmin() : bool{
        return $this->role === 'ADMIN';
    }

    public static function isSessionUserAdmin(){
        if(!self::isUserConnected()) return false;
        $userRepo = new UserRepository(Database::getInstance()->getConnection());
        return $userRepo->getUser($_SESSION['ID'])->getRole() === 'ADMIN';

    }

    public static function isUserConnected() : bool{
        if(isset($_SESSION['ID'])) return true;
        return false;
    }


}

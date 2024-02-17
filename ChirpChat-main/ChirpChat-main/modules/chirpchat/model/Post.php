<?php

namespace ChirpChat\Model;

use \ChirpChat\Model\Category;
/**
 * Classe représentant un poste (post) sur la plateforme.
 */
class Post{
    /**
     * Crée une nouvelle instance de la classe Post.
     *
     * @param string $idPost L'identifiant du post.
     * @param string|null $titre Le titre du post (peut être nul).
     * @param string $message Le contenu du post.
     * @param string $datePubli La date de publication du post.
     * @param \ChirpChat\Model\Category[] $categorie Les catégories associées au post.
     * @param User $utilisateur L'utilisateur qui a créé le post.
     * @param int $commentAmount Le nombre de commentaires sur le post.
     * @param int $likeAmount Le nombre de mentions "J'aime" sur le post.
     */
    public function __construct(public string $idPost, private readonly ?string $titre, public string $message,private string $datePubli, private array $categorie, private $utilisateur, public int $commentAmount, public int $likeAmount ){ }
    /**
     * Récupère l'utilisateur qui a créé le post.
     *
     * @return User L'utilisateur qui a créé le post.
     */
    public function getUser() : User{
        return $this->utilisateur;
    }

    /**
     * Récupère les catégories associées au post.
     *
     * @return \ChirpChat\Model\Category[] Un tableau d'objets Category représentant les catégories du post.
     */
    public function getCategories() : array{
        return $this->categorie;
    }
    /**
     * Récupère le titre du post (peut être nul).
     *
     * @return string|null Le titre du post.
     */
    public function getTitre() : ?string{
        return $this->titre;
    }
    /**
     * Récupère la date de publication du post.
     *
     * @return string La date de publication du post.
     */
    public function getDatePubli() : string{
        return $this->datePubli;
    }

    /**
     * @return int
     */
    public function getLikeAmount(): int
    {
        return $this->likeAmount;
    }
    /**
     * Vérifie si le post est déjà "aimé" par un utilisateur donné.
     *
     * @param mixed $userId L'identifiant de l'utilisateur.
     * @return bool Vrai si l'utilisateur a déjà "aimé" le post, sinon faux.
     */
    public function isLikedByUser($userId) : bool{
        $postRepo = new \ChirpChat\Model\PostRepository(Database::getInstance()->getConnection());
        return $postRepo->isAlreadyLiked($this->idPost, $userId);
    }


}
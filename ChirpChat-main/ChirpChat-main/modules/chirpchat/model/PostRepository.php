<?php

namespace ChirpChat\Model;

use ChirpChat\Model\Post;
use Includes\DatabaseConnection;
use \ChirpChat\Model\Post as Comment;
/**
 * Repository de gestion des publications (posts) sur la plateforme.
 */
class PostRepository{
    /**
     * Crée une nouvelle instance de la classe PostRepository.
     *
     * @param \PDO $connection L'objet de connexion à la base de données.
     */
    public function __construct(private \PDO $connection){ }

    /**
     * Récupère la liste des publications (posts) sur la plateforme.
     *
     * @return Post[] Un tableau d'objets Post représentant les publications.
     */
    public function getPostList(int $limit = 5){
        $userRepo = new \ChirpChat\Model\UserRepository($this->connection);
        $catRepo = new \ChirpChat\Model\CategoryRepository($this->connection);
        $statement =  $this->connection->prepare("SELECT * FROM Post WHERE PARENT_ID IS NULL ORDER BY date_publi DESC LIMIT " . $limit);
        $statement->execute([]);

        $postList = [];

        while ($row = $statement->fetch()){
            $post = new Post($row['id_post'], $row['titre'], $row['message'], $row['date_publi'], $catRepo->getCategoriesForPost($row['id_post']), $userRepo->getUser($row['id_utilisateur']), $row['commentAmount'], $row['likeAmount']);
            $postList[] = $post;
        }

        return $postList;
    }

    public function add(?string $titre, string $message, string $userID, string $parent_id=null) : void {
        if($titre == null) $titre = "";

        $statement = $this->connection->prepare("INSERT INTO Post (titre, message, date_publi, id_utilisateur, PARENT_ID)VALUES (?,?,?,?,?)");
        $statement->execute([$titre, $message,date('Y-m-d H:i:s'), $userID, $parent_id]);

        if($parent_id != null){
            $statement = $this->connection->prepare("UPDATE Post SET CommentAmount = CommentAmount + 1 WHERE ID_POST=?");
            $statement->execute([$parent_id]);
        }
    }

    public function getPostComment(string $id) : array {
        $catRepo = new \ChirpChat\Model\CategoryRepository($this->connection);
        $userRepo = new \ChirpChat\Model\UserRepository($this->connection);
        $statement = $this->connection->prepare("SELECT * FROM Post WHERE PARENT_ID=? LIMIT 10");
        $statement->execute([$id]);

        $commentList = [];
        while($row = $statement->fetch()){
            $commentList[] = new Comment($row['id_post'], $row['titre'], $row['message'], $row['date_publi'], $catRepo->getCategoriesForPost($row['id_post']), $userRepo->getUser($row['id_utilisateur']), $row['commentAmount'], $row['likeAmount']);
        }
        return $commentList;
    }

    public function getPost(string $postId) : ?Post{
        $catRepo = new \ChirpChat\Model\CategoryRepository($this->connection);
        $userRepo = new \ChirpChat\Model\UserRepository($this->connection);
        $statement = $this->connection->prepare("SELECT * FROM Post WHERE PARENT_ID IS NULL AND ID_POST = ? LIMIT 1");
        $statement->execute([$postId]);

        if($row = $statement->fetch()){
            return new Comment($row['id_post'], $row['titre'], $row['message'], $row['date_publi'], $catRepo->getCategoriesForPost($row['id_post']), $userRepo->getUser($row['id_utilisateur']), $row['commentAmount'], $row['likeAmount'], $row['likeAmount']);
        }

        return null;
    }

    public function getComment(string $commentId) : ?Post{
        $catRepo = new \ChirpChat\Model\CategoryRepository($this->connection);
        $userRepo = new \ChirpChat\Model\UserRepository($this->connection);
        $statement = $this->connection->prepare("SELECT * FROM Post WHERE ID_POST = ? LIMIT 1");
        $statement->execute([$commentId]);

        if($row = $statement->fetch()){
            return new Comment($row['id_post'], $row['titre'], $row['message'], $row['date_publi'], $catRepo->getCategoriesForPost($row['id_post']), $userRepo->getUser($row['id_utilisateur']), $row['commentAmount'], $row['likeAmount']);
        }

        return null;
    }

    public function isAlreadyLiked(?int $post_id, string $user_id) : bool {
        $statement = $this->connection->prepare("SELECT POST_ID, USER_ID FROM LIKES WHERE POST_ID = ? AND USER_ID = ?");
        $statement->execute([$post_id, $user_id]);
        if($statement->fetch()) return true;
        return false;
    }

    public function addLike(?int $post_id, string $user_id) : void {
        if($this->isAlreadyLiked($post_id, $user_id)){
            $this->removeLike($post_id, $user_id);
        } else {
            $statement = $this->connection->prepare("INSERT INTO LIKES (POST_ID, USER_ID) VALUES (?, ?)");
            $statement->execute([$post_id, $user_id]);
        }
    }
    /**
     * Supprime un "j'aime" d'une publication.
     *
     * @param int|null $post_id L'identifiant de la publication.
     * @param string $user_id L'identifiant de l'utilisateur.
     */
    public function removeLike(?int $post_id, string $user_id) : void {
        $statement = $this->connection->prepare("DELETE FROM LIKES WHERE POST_ID = ? AND USER_ID = ?");
        $statement->execute([$post_id, $user_id]);
    }
    /**
     * Supprime une publication.
     *
     * @param int|null $post_id L'identifiant de la publication à supprimer.
     */
    public function deletePost(?int $post_id) : void {
        $statement = $this->connection->prepare("DELETE FROM Post WHERE Post.id_post = ?");
        $statement->execute([$post_id]);
    }


    /**
     * Recherche des publications en fonction d'un filtre donné.
     *
     * @param string $filter Le filtre de recherche.
     * @return Post[] Un tableau d'objets Post représentant les publications correspondant au filtre.
     */
    public function searchPostOrComment(string $filter, string $userName = "") : array{
        $filter = '%' . $filter . '%';
        $userName = '%' . $userName . '%';
        $userRepo = new \ChirpChat\Model\UserRepository($this->connection);
        $statement = $this->connection->prepare("SELECT * FROM Post JOIN Utilisateur ON Post.id_utilisateur = Utilisateur.ID WHERE (message LIKE ? OR titre LIKE ?) AND Utilisateur.pseudonyme LIKE ? LIMIT 15");
        $statement->execute([$filter, $filter, $userName]);

        $postList = [];

        while ($row = $statement->fetch()){
            $post = $this->getComment($row['id_post']);
            $postList[] = $post;
        }

        return $postList;
    }

    public function getLastPostID() : int {
        $statement = $this->connection->prepare('SELECT MAX(id_post) m FROM Post');
        $statement->execute();
        return $statement->fetch()['m'];
    }

    public function getUserPost(string $userID) : array{
        $statement = $this->connection->prepare('SELECT id_post FROM Post WHERE id_utilisateur = ? AND PARENT_ID IS NULL ORDER BY date_publi DESC LIMIT 15');
        $statement->execute([$userID]);
        $postList = [];

        while($row = $statement->fetch()){
            $postList[] = $this->getPost($row['id_post']);
        }

        return $postList;
    }

    public function setPostTitle(string $postID, string $title) : void{
        $statement = $this->connection->prepare('UPDATE Post SET titre = ? WHERE id_post = ?');
        $statement->execute([$title, $postID]);
    }

    public function setPostMessage(string $postID, string $message) : void{
        $statement = $this->connection->prepare('UPDATE Post SET message = ? WHERE id_post = ?');
        $statement->execute([$message, $postID]);
    }

    public function getPostListInCategory(string $catId) : array{
        $statement = $this->connection->prepare('SELECT * FROM PostCategory WHERE id_cat = ?');
        $statement->execute([$catId]);

        $postList = [];

        while($row = $statement->fetch()){
            $postList[] = $this->getPost($row['id_post']);
        }

        return $postList;
    }

    public function getTopOfTheWeek() : array{
        $statement = $this->connection->prepare('SELECT * FROM Post WHERE date_publi > DATE_SUB(NOW(), INTERVAL 7 DAY ) AND PARENT_ID IS NULL ORDER BY likeAmount DESC LIMIT 10');
        $statement->execute([]);

        $postList = [];

        while($row = $statement->fetch()){
            $postList[] = $this->getPost($row['id_post']);
        }

        return $postList;
    }


}

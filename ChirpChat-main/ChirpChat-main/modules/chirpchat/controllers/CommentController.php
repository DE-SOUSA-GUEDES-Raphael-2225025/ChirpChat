<?php

namespace ChirpChat\Controllers;

use Chirpchat\Model\Database;
use Chirpchat\Model\PostRepository;
use ChirpChat\Model\UserRepository;
use ChirpChat\Views\HomePageView;

/**
 * Classe Comment pour la gestion des commentaires.
 */
class CommentController{

    /**
     * Affiche tous les commentaires d'un message à partir de l'identifiant du message dans l'URL.
     *
     * Cette méthode récupère l'identifiant du message depuis l'URL, puis utilise le PostRepository
     * pour obtenir la liste des commentaires associés à ce message.
     *
     * @return void
     */
    public function displayComment() : void {
        if(!isset($_GET['id'])) return;

        $user = null;

        if(isset($_SESSION['ID'])){
            $user = (new UserRepository(Database::getInstance()->getConnection()))->getUser($_SESSION['ID']);
        }

        $postRepository = new PostRepository(Database::getInstance()->getConnection());
        $postID = $_GET['id'];
        $commentList = $postRepository->getPostComment($postID);
        $commentPage = (new \chirpchat\views\post\PostCommentView());

        $post = $postRepository->getPost($postID);
        if($post == null){ //Réponse a un commentaire
            $post = $postRepository->getComment($postID);
        }


        $commentPage
            ->setMainPost($post, $user)
            ->setPostComments($commentList)
            ->displayCommentPage();
    }

    /**
     * Ajoute un commentaire si l'utilisateur est connecté.
     *
     * Cette méthode ajoute un commentaire en utilisant les données POST fournies si un identifiant de message
     * est présent dans l'URL.
     *
     * @return void
     */
    public function addComment() : void {
        $comment = $_POST['comment'];
        if(!isset($_GET['id'])) {
            (new HomePageView())->displayHomePageView(null);
            return;
        }

        $parentId = $_GET['id'];
        (new \ChirpChat\Model\PostRepository(Database::getInstance()->getConnection()))->add(null, $comment, $_SESSION['ID'], $parentId);
        header("Location:index.php?action=comment&id=" . $parentId);
    }
}

<?php

namespace ChirpChat\Controllers;

use ChirpChat\Model\CategoryRepository;
use Chirpchat\Model\Database;
use ChirpChat\Model\PostRepository;
use chirpchat\utils\Notification;
use ChirpChat\Views\HomePageView;
use chirpchat\views\post\PostView;

/**
 * Contrôleur de gestion des publications (posts).
 */
class PostController{
    /**
     * Ajoute une nouvelle publication (post) avec des catégories associées.
     *
     * Cette méthode récupère les données du formulaire POST, puis crée une nouvelle publication
     * en utilisant le PostRepository. Les catégories associées à la publication sont également gérées.
     * Ensuite, elle redirige l'utilisateur vers la page d'accueil.
     *
     * @return void
     */
    public function addPost() : void{

        $categoryRepo = new \ChirpChat\Model\CategoryRepository(Database::getInstance()->getConnection());
        $postRepo = new \ChirpChat\Model\PostRepository(Database::getInstance()->getConnection());
        $titre = htmlspecialchars($_POST['titre']);
        $message = htmlspecialchars($_POST['message']);
        $categoriesNames = $_POST['categories'];


        /* Erreur si le post est vide */
        if(empty($titre) || empty($message)){
            Notification::createErrorMessage("Le message ne peut pas être vide");
            header("Location:index.php");
            return;
        }

        $postRepo->add($titre, $message, $_SESSION['ID']);

        /* Verification que la catégorie existe bien */
        foreach ($categoriesNames as $category){
            $catId = $categoryRepo->getCategoryId($category);
            if($catId != -1){
                $categoryRepo->addPostToCategory($postRepo->getLastPostID(), $catId);
            }
        }

        Notification::createSuccessMessage("Post crée avec succès");

        header("Location:index.php");
    }


    /**
     * Recherche des publications (posts) en fonction du filtre.
     *
     * Cette méthode effectue une recherche de publications en fonction du filtre fourni dans le formulaire POST.
     * Elle affiche les résultats de la recherche sur la page d'accueil en utilisant la vue HomePageView.
     *
     * @return void
     */
    public function searchPost() : void{
        $filter = str_replace( ' ', '+', $_POST['filter']);
        $categorieList = (new CategoryRepository(Database::getInstance()->getConnection()))->getAllCategories();
        $postList = (new \Chirpchat\Model\PostRepository(Database::getInstance()->getConnection()))->searchPost($filter);
        $homePageView = new HomePageView();

        if(!empty($postList)) {
            $homePageView->setPostListView($postList, $categorieList, 1, false);
        }else{
            $homePageView->displayNoPostFoundError();
        }

        $homePageView->displayHomePageView(null);
    }


    /**
     * Supprime une publication (post) en fonction de son identifiant.
     *
     * Cette méthode supprime une publication en utilisant le PostRepository, en fonction de l'identifiant fourni.
     * Ensuite, elle redirige l'utilisateur vers la page d'accueil.
     *
     * @param string $postID L'identifiant de la publication à supprimer.
     *
     * @return void
     */
    public function deletePost(string $postID) : void {
        $postRepo = new PostRepository(Database::getInstance()->getConnection());
        if(!isset($_SESSION['ID'])) return;

        /* Verification que le post est supprimé par un admin ou celui qui l'a posté */
        if($_SESSION['ID'] === $postRepo->getComment($postID)->getUser()->getUserID() || \ChirpChat\Model\User::isSessionUserAdmin()){
            $postRepo->deletePost($postID);
        }

        Notification::createSuccessMessage("Post supprimé avec succès");

        header('Location:index.php'); // Redirection vers la page d'accueil
    }

    public function updatePost() : void{
        if(!isset($_SESSION['ID']) || !isset($_GET['id'])) return;
        if(!isset($_POST['title']) || !isset($_POST['message'])) return;
        $postRepo = new PostRepository(Database::getInstance()->getConnection());

        if($postRepo->getComment($_GET['id'])->getUser()->getUserID() !== $_SESSION['ID']) return;

        $postRepo->setPostTitle($_GET['id'],$_POST['title']);
        $postRepo->setPostMessage($_GET['id'],$_POST['message']);

        Notification::createSuccessMessage("Post modifié avec succès");

        header("Location:index.php");
    }

    /* Affiche la page d'édition d'un post */
    public function displayEditPostPage() : void{
        if(!isset($_SESSION['ID']) || !isset($_GET['id'])) return;
        $postRepo = new PostRepository(Database::getInstance()->getConnection());
        $post = $postRepo->getComment($_GET['id']);
        if($post->getUser()->getUserID() !== $_SESSION['ID']) return;

        (new PostView($post))->showPostEditView();
    }
}

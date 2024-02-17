<?php

namespace ChirpChat\Controllers;

use chirpchat\model\Database;
/**
 * Contrôleur de la page d'accueil.
 */
class HomepageController {

    private int $pageNb = 1;

    /** Permet de choisir le numero de page (5 posts par page)
     * @param int $newNbPage
     * @return $this
     */
    public function setPageNb(int $newNbPage) : HomepageController{
        $this->pageNb = $newNbPage;
        return $this;
    }

    /**
     * Exécute le contrôleur de la page d'accueil.
     *
     * Cette méthode récupère la liste des publications (posts) et des catégories depuis la base de données.
     * Si l'utilisateur est connecté, elle récupère également les informations de l'utilisateur connecté.
     * Ensuite, elle utilise la vue HomePageView pour afficher la page d'accueil avec les données récupérées.
     *
     * @return void
     */
    public function execute() : void{
        $postRepo = new \ChirpChat\Model\PostRepository(Database::getInstance()->getConnection());
        $postList = $postRepo->getPostList($this->pageNb * 5);
        $categoriesList = (new \ChirpChat\Model\CategoryRepository(Database::getInstance()->getConnection()))->getAllCategories();
        if(isset($_SESSION['ID'])) {
            $user = (new \ChirpChat\Model\UserRepository(Database::getInstance()->getConnection()))->getUser($_SESSION['ID']);
        }
        else{
            $user = null;
        }
        $bestPostList = $postRepo->getTopOfTheWeek();
        $homePageView = new \ChirpChat\Views\HomePageView();
        $homePageView
            ->setCategoriesView($categoriesList)
            ->setPostListView($postList, $categoriesList, $this->pageNb)
            ->setBestPostView($bestPostList)
            ->displayHomePageView($user);
    }

}

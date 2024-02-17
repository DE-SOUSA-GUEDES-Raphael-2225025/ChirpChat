<?php

namespace ChirpChat\Controllers;

use ChirpChat\Model\CategoryRepository;
use Chirpchat\Model\Database;
use ChirpChat\Model\User;
use ChirpChat\Model\UserRepository;
use chirpchat\views\category\CategoryCreationView;
use chirpchat\views\category\CategoryListView;
use ChirpChat\Views\HomePageView;

/**
 * Contrôleur de gestion des catégories.
 */
class CategoryController{
    /**
     * Crée une nouvelle catégorie à partir des données POST.
     *
     * Cette méthode crée une nouvelle catégorie en utilisant les données POST
     * fournies, puis dirige l'utilisateur vers la page de la liste des catégories.
     *
     * @return void
     */
    public function createCategory() : void {
        $categoriesRepo = new CategoryRepository(Database::getInstance()->getConnection());
        $categoryName = ucfirst($_POST['categoryName']);
        $categoryDescription = $_POST['categoryDescription'];
        $categoryColor = $_POST['color'];

        if(User::isSessionUserAdmin()){
            $categoriesRepo->createCategory($categoryName, $categoryDescription, $categoryColor);
        }

        header('Location:index.php?action=categoryList');
    }
    /**
     * Affiche la page de création de catégories.
     *
     * Cette méthode génère et affiche le formulaire de création de catégories.
     *
     * @return void
     */
    public function displayCategoryCreationPage() : void{
        $categoryCreationView = new CategoryCreationView();
        if(User::isSessionUserAdmin()){
            $categoryCreationView->displayCategoryCreationForm();
        }else{
            (new HomePageView())->displayHomePageView(null);
        }
    }

    /** Cette methode affiche la page de modification des catégories
     * @return void
     */
    public function displayCategoryUpdatePage() : void{
        if(!isset($_GET['id'])) return;
        if(!\ChirpChat\Model\User::isSessionUserAdmin()){
            (new HomePageView())->displayHomePageView(null);
        }

        $idCat = $_GET['id'];
        $categoryRepo = new CategoryRepository(Database::getInstance()->getConnection());

        if(!$categoryRepo->isCategoryExist($idCat)) return;

        (new CategoryCreationView())->displayCategoryModificationForm($categoryRepo->getCategory($idCat));
    }


    /**
     * Affiche la liste des catégories.
     *
     * Cette méthode récupère la liste de toutes les catégories depuis la base de données
     * et les affiche sur la page. Elle vérifie également si l'utilisateur est un administrateur
     *
     * @return void
     */
    public function displayCategoryListPage() : void{
        $categoriesRepo = new CategoryRepository(Database::getInstance()->getConnection());
        $userRepo = new UserRepository(Database::getInstance()->getConnection());

        $categoryListView = new CategoryListView();
        $categoriesList = $categoriesRepo->getAllCategories();

        if(isset($_SESSION['ID']) && $userRepo->getUser($_SESSION['ID'])->isAdmin()) {
            $categoryListView->displayCreationButton();
        }

        $categoryListView->displayAllCategories($categoriesList);
    }


    /** Supprime une catégorie
     * @return void
     */
    public function deleteCategory(){
        if(\ChirpChat\Model\User::isSessionUserAdmin() && isset($_GET['id'])){
            $categoryRepo = new CategoryRepository(Database::getInstance()->getConnection());
            $categoryRepo->deleteCategory($_GET['id']);
        }
        header("Location:index.php?action=categoryList");
    }


    /** Met a jour une catégorie
     * @return void
     */
    public function updateCategory(){
        if(!isset($_GET['id'])) return;
        if(!\ChirpChat\Model\User::isSessionUserAdmin()) return;

        $idCat = $_GET['id'];
        $categoryRepo = new CategoryRepository(Database::getInstance()->getConnection());

        $libelle = $_POST['categoryName'];
        $description = $_POST['categoryDescription'];
        $categoryColor = $_POST['color'];

        if(!empty($libelle)){
            $categoryRepo->setLibelle($idCat, $libelle);
        }

        if(!empty($description)){
            $categoryRepo->setDescription($idCat, $description);
        }

        if(!empty($categoryColor)){
            $categoryRepo->setColorCode($idCat, $categoryColor);
        }

        header("Location:index.php?action=categoryList");
    }

}

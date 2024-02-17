<?php

namespace chirpchat\views\category;

use ChirpChat\Model\Category;
use Chirpchat\Model\Database;
use ChirpChat\Model\UserRepository;

/**
 * Class CategoryCreationView
 *
 * Cette classe gère l'affichage du formulaire de création de catégorie.
 */
class CategoryListView{


    public function __construct()
    {
        ob_start();
    }

    /**
     * Affiche le formulaire de création de catégorie.
     *
     * Cette méthode génère et affiche le formulaire permettant de créer une nouvelle catégorie.
     *
     * @return void
     */
    public function displayCreationButton() : void{
        echo '<a id="creation-category-button" href="index.php?action=categoryCreation"><p >Créer une nouvelle catégorie</p></a>';
    }

    /**
     * Affiche la liste de catégories.
     *
     * @param Category[] $categories Un tableau d'objets Category représentant les catégories à afficher.
     * @return void
     */
    public function displayAllCategories(array $categories) : void {
        echo '<div id="category-list">';
            foreach($categories as $category){
                ?><div class="category" style="background-color: <?= $category->getColorCode() ?>">

                    <!-- Partie avant de la catégorie -->
                    <div class="category-front">
                        <h3> <?=$category->getLibelle() ?> </h3>
                        <h4> <?= $category->getNbPostInCategory() ?> Posts </h4>
                    </div>

                    <!-- Partie arrière de la catégorie -->
                    <div class="category-back">
                        <p> <?=$category->getDescription() ?> </p>
                        <a aria-label="Decouvrez la catégorie <?=$category->getLibelle()?>" href="index.php?action=searchPostInCategory&id=<?=$category->getIdCat()?>" style="font-family: 'League Spartan', serif; font-size: 1.8em; font-weight: 600">Découvrir</a>
                    </div>

                    <!-- Boutons d'administration -->
                    <?php $userRepo = new UserRepository(Database::getInstance()->getConnection());
                    if(isset($_SESSION['ID']) && $userRepo->getUser($_SESSION['ID'])->isAdmin())
                    { ?>
                        <div class="admin-buttons">
                            <a href="index.php?action=updateCategoryPage&id=<?= $category->getIdCat()?>">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                                    <path d="M21.731 2.269a2.625 2.625 0 00-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 000-3.712zM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 00-1.32 2.214l-.8 2.685a.75.75 0 00.933.933l2.685-.8a5.25 5.25 0 002.214-1.32L19.513 8.2z" />
                                </svg>
                            </a>
                            <a href="index.php?action=deleteCategory&id=<?= $category->getIdCat()?>">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                                    <path fill-rule="evenodd" d="M16.5 4.478v.227a48.816 48.816 0 013.878.512.75.75 0 11-.256 1.478l-.209-.035-1.005 13.07a3 3 0 01-2.991 2.77H8.084a3 3 0 01-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 01-.256-1.478A48.567 48.567 0 017.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 013.369 0c1.603.051 2.815 1.387 2.815 2.951zm-6.136-1.452a51.196 51.196 0 013.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 00-6 0v-.113c0-.794.609-1.428 1.364-1.452zm-.355 5.945a.75.75 0 10-1.5.058l.347 9a.75.75 0 101.499-.058l-.346-9zm5.48.058a.75.75 0 10-1.498-.058l-.347 9a.75.75 0 001.5.058l.345-9z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        </div>
                    <?php } ?>
                </div>
            <?php }
        echo '</div>';
        $pageContent = ob_get_clean();
        (new \chirpchat\views\layout\MainLayout("Liste des catégories", $pageContent))->show(['categoryList.css']);
    }
}

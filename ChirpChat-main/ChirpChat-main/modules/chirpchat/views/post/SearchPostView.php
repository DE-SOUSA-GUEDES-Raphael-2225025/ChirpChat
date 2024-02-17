<?php

namespace chirpchat\views\post;

use ChirpChat\Model\Category;
use ChirpChat\Model\Post;
use ChirpChat\Utils\Background;
use chirpchat\views\category\CategoryView;
use chirpchat\views\layout\MainLayout;

class SearchPostView{

    /**
     * @param Post[] $postCommentList
     * @param Category[] $categoryList
     * @return void
     */
    public function show(array $postCommentList, array $categoryList, string $filter) : void{
        ob_start();
        ?>
        <script src="/_assets/js/searchBarSectionSwitch.js"></script>
        <header id="search-header">
            <?php if(!empty($categoryList)){ ?>
                <button class="authButtons" onclick="showPostSection()">Post/Commentaire</button>
                <button class="authButtons" onclick="showCategorySection()">Categories</button>
            <?php } ?>
        </header>
        <section id="post-commentaire-list">
            <?php if(!empty($filter)){ ?>
                <form id="user-filter-form" action="index.php?action=search" method="post">
                    <label>Filtrer par utilisateur
                        <input class="inputField" type="text" name="username" placeholder="Nom d'Utilisateur ">
                    </label>
                    <input type="hidden" name="filter" value="<?=$filter?>">
                    <input class="authButtons" type="submit" value="Rechercher">
                </form>
            <?php } ?>

            <?php foreach ($postCommentList as $post){
                (new PostView($post))->show();
            }?>

        </section>

        <section id="category-list" style="display: none">
            <?php foreach ($categoryList as $category){
                (new CategoryView($category))->show();
            } ?>
        </section>

        <?php
        Background::displayBackgroundShapes();
        $content = ob_get_clean();
        (new MainLayout('Recherche',$content))->show(['styles.css', 'searchPage.css', 'post.css']);
    }
}
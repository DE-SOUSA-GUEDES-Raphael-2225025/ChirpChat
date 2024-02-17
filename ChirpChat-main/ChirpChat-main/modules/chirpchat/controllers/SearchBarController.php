<?php

namespace ChirpChat\Controllers;

use ChirpChat\Model\CategoryRepository;
use Chirpchat\Model\Database;
use ChirpChat\Model\PostRepository;
use ChirpChat\Views\HomePageView;
use chirpchat\views\post\SearchPostView;

class SearchBarController{

    /** Recherche des post, commentaire et catégorie en fonction du filtre ($_POST['filter)
     *
     * @return void
     */
    public function search(){
        if(!isset($_POST['filter'])){
            (new HomePageView())->displayHomePageView(null);
            return;
        }

        $username = '';

        if(isset($_POST['username'])){
            $username = $_POST['username'];
        }

        $postRepo = new PostRepository(Database::getInstance()->getConnection());
        $postCommentList = $postRepo->searchPostOrComment($_POST['filter'], $username);


        $categoryRepo = new CategoryRepository(Database::getInstance()->getConnection());
        $categoryList = $categoryRepo->searchCategory($_POST['filter']);

        (new SearchPostView())->show($postCommentList, $categoryList, $_POST['filter']);
    }

    /** Recherche des post dans la catégorie précisé dans le $_GET['id'] */
    public function searchPostInCategorie(){
        if(!isset($_GET['id'])){
            (new HomePageView())->displayHomePageView(null);
            return;
        }

        $postRepo = new PostRepository(Database::getInstance()->getConnection());
        $postList = $postRepo->getPostListInCategory($_GET['id']);

        (new SearchPostView())->show($postList, [], "");
    }
}

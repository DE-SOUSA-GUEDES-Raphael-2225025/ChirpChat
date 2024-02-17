<?php

namespace ChirpChat\Views;

use ChirpChat\Model\Category;
use Chirpchat\Model\Database;
use ChirpChat\Model\Post;
use ChirpChat\Model\UserRepository;
use ChirpChat\Utils\Background;

/**
 * Class HomePageView
 *
 * Cette classe gère l'affichage de la page d'accueil, y compris les catégories, les publications et les publications populaires.
 */
class HomePageView {
    /**
     * Vue des catégories.
     *
     * @var string
     */
    private string $categoriesView = "";
    private string $postListView = "";
    private string $bestPostView = "";

    /**
     * Définit la vue des catégories.
     *
     * @param Category[] $categoriesList Un tableau d'objets Category représentant les catégories.
     *
     * @return HomePageView
     */
    public function setCategoriesView(array $categoriesList) : HomePageView{
        ob_start();
        ?> <div id="categories">
            <h3 class="sectionTitle">Catégories</h3>
            <div id="slider">
                <script src="../../../_assets/js/categoriesCreation.js"></script>
                <?php for ($i = 0; $i < count($categoriesList) && $i < 5; $i++){
                    $category = $categoriesList[$i]?>
                    <div class="cat-container" style="background-color: <?= $category->getColorCode() ?>">
                        <a class="link" aria-label="Decouvrez la catégorie <?=$category->getLibelle()?>" href="index.php?action=searchPostInCategory&id=<?=$category->getIdCat()?>"></a>
                        <h4><?= $category->getLibelle() ?></h4>
                        <p><?= $category->getNbPostInCategory()?> Posts</p>
                        <svg onload="placeStarElement(this)" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                        </svg>
                    </div>
                <?php } ?>
                <a id="more-category" href="index.php?action=categoryList" aria-label="Plus de catégories">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <p>Plus de catégories</p>
                </a>
            </div>
        </div>

        <?php
        $this->categoriesView = ob_get_clean();
        return $this;
    }

    /**
     * @param Post[] $postList
     * @param $categories
     * @param $pageNb
     * @return $this
     */
    public function setPostListView($postList, $categories, $pageNb = 1, $showPostCreation = true) : HomePageView {
        ob_start();
        ?><div id="postList">
        <?php if(isset($_SESSION['ID'])){
            $user = (new UserRepository(Database::getInstance()->getConnection()))->getUser($_SESSION['ID']);
            if($showPostCreation){
            ?>

            <form action="index.php?action=sendPost" id="writePostSection" method="post">
                <img alt="photo de profil" class="profile-picture" src="<?= $user->getProfilPicPath()?>">
                <div id="userInputContent">
                    <input type="text" placeholder="Donnez un titre !" name="titre" required>
                    <textarea spellcheck="false" maxlength="160" placeholder="Envoyez un message !" name="message" required></textarea>
                    <select id="category" multiple name="categories[]">
                        <?php $this->getCategoriesList($categories) ?>
                    </select>
                    <script>
                        new SlimSelect({
                            select: '#category',
                            settings: {
                                placeholderText: '<p class="black">Choisir une catégorie</p>',
                                searchPlaceholder: 'Rechercher',
                            }
                        })
                    </script>
                    <input type="submit" value="POSTER">
                </div>
            </form>

        <?php }}
        if(count($postList) > 1){
            $lastPost = $postList[0];
        }
            foreach($postList as $post) {
                $lastPost = $post;
                (new \chirpchat\views\post\PostView($post))->show();
            }
        if(count($postList) >= 5) {
            echo '<a href="index.php?page=' . $pageNb + 1 . '#' . $lastPost->idPost . '" class="authButtons" id="see-more-button">Voir Plus</a>';
        }

        ?>
        </div><?php

        $this->postListView = ob_get_clean();
        return $this;
    }

    /**
     * Définit la vue des publications populaires.
     *
     * @param Post[] $bestPostList
     * @return HomePageView
     */
    public function setBestPostView(array $bestPostList) : HomePageView {
        ob_start();
        ?>
        <div id="bestPost">
            <h3 class="sectionTitle">Top de la semaine</h3>
            <div id="best-post-list">
                <?php foreach ($bestPostList as $post){
                    ?>
                    <div class="bestPost">
                        <a class="link" aria-label="Post de <?=$post->getUser()->getPseudo()?>" href="index.php?action=comment&id=<?=$post->idPost?>"></a>
                        <img alt="profile picture" src="<?=$post->getUser()->getProfilPicPath()?>">
                        <h4><?= $post->getUser()->getPseudo() ?></h4>
                        <p><?= $post->getTitre() ?></p>
                        <div class="likes">
                            <p><?= $post->likeAmount?></p>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                                <path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z" />
                            </svg>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
        <?php
        $this->bestPostView = ob_get_clean();
        return $this;
    }

    public function displayHomePageView($user) : void {
        ob_start();
        ?><div id="catContainer">
        <?= $this->categoriesView ?>
        <?= $this->postListView ?>
        <?= $this->bestPostView ?>
        </div>
        <?php
        $content = ob_get_clean();
        (new layout\mainLayout("Accueil", $content))->show(['homePage.css', 'post.css']);
    }

    /**
     * Affiche la liste des catégories.
     *
     * @param Category[] $categoriesList Un tableau d'objets Category représentant les catégories.
     *
     * @return void
     */
    public function getCategoriesList(array $categoriesList) : void{
        foreach($categoriesList as $category){
            echo '<option>' . $category->getLibelle() . '</option>';
        }
    }
}

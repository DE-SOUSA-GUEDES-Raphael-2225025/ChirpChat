<?php

namespace chirpchat\views\post;

use Chirpchat\Model\Database;
use ChirpChat\Model\UserRepository;

/**
 * Class PostView
 *
 * Cette classe gère l'affichage des détails d'un post.
 */
class PostView{

    /**
     * @param \ChirpChat\Model\Post $post Le post à afficher.
     */
    public function __construct(private \ChirpChat\Model\Post $post) {}

    /**
     * Affiche le contenu du post.
     */
    public function show() : void{
        ob_start();
        ?>
            <div class="post">

                <!-- Lien pour commenter et photo de profil -->
                <a class="commentLink link" aria-label="Commentez le post de <?= $this->post->getUser()->getPseudo()?>" href="index.php?action=comment&id=<?=$this->post->idPost?>"></a>
                <a id="<?= $this->post->idPost?>" href="index.php?action=profile&id=<?= $this->post->getUser()->getUserID() ?>">
                    <img alt="author profile picture" class="profile-picture" src="<?=$this->post->getUser()->getProfilPicPath()?>">
                </a>

                <!-- Header du post -->
                <div class="postHeader">
                    <div class="authorInfo">
                        <?php
                        echo '<h2>' . $this->post->getUser()->getPseudo() . '</h2>';
                        echo '<h3>' . $this->getDatePublicString($this->post->getDatePubli()) . '</h3>';
                        ?>
                    </div>

                    <!-- Liste des catégories -->
                    <div class="postCategories">
                        <?php foreach ($this->post->getCategories() as $cat){
                            echo '<p class="category" style="background-color:' . $cat->getColorCode() . '">' . strtoupper($cat->getLibelle()) . '</p>';
                        }?>
                    </div>
                </div>

                <!-- Menu déroulant des paramètres du post -->
                <?php
                $userRepo = new UserRepository(Database::getInstance()->getConnection());
                if(isset($_SESSION['ID']) && ($this->post->getUser()->getUserID() === $_SESSION['ID'] || $userRepo->getUser($_SESSION['ID'])->isAdmin())){ ?>
                    <div class="postSettings" onclick="openCloseUserMenu(this)">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <div class="menuClose postSettingsMenu">
                            <ul>
                                <?php if($this->post->getUser()->getUserID() === $_SESSION['ID']){ ?>
                                <li>
                                    <a href="index.php?action=editPost&id=<?=$this->post->idPost?>"></a>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                        </svg>
                                        <p>Modifier</p>
                                </li>
                                <?php } ?>
                                <li>
                                    <a href="index.php?action=deletepost&id=<?php echo $this->post->idPost?>"></a>
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                        <p>Supprimer</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                <?php } ?>

                <span class="separator"></span>

                <!-- Contenu du post -->
                <div class="postContent">
                        <h3><?php if($this->post->getTitre() != null) echo $this->post->getTitre() ?> </h3>
                            <p>
                                <?php echo $this->post->message?>
                            </p>
                </div>

                <!-- Bas du post avec like et commentaire -->
                <div class="postFooter">
                    <div>
                        <form action="index.php?action=like&id=<?php echo $this->post->idPost?>" method="post">
                            <button type="submit" aria-label="like post">
                                <?php $this->getLikeButtonSvg() ?>
                            </button>
                        </form>
                        <p><?php echo $this->post->likeAmount ?></p>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.76c0 1.6 1.123 2.994 2.707 3.227 1.068.157 2.148.279 3.238.364.466.037.893.281 1.153.671L12 21l2.652-3.978c.26-.39.687-.634 1.153-.67 1.09-.086 2.17-.208 3.238-.365 1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" />
                        </svg>
                        <p><?php echo $this->post->commentAmount ?></p>
                    </div>
                </div>
            </div>
        <?php

        echo ob_get_clean();
    }

    /** Retourne le bouton "J'aime" avec l'image appropriée en fonction des likes de l'utilisateur.
    *@return string
     */
    public function getLikeButtonSvg() : void
    {
        // User not connected or hasn't liked
        if (isset($_SESSION['ID']) && $this->post->isLikedByUser($_SESSION['ID'])) {
            echo '
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#FF3A3AFF" class="w-6 h-6">
                <path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z" />
            </svg>';
        } else {
            echo '
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
            </svg>';
        }
    }
    /**
     * Convertit la date en une chaîne de texte relative à la date actuelle.
     *
     * @param string $date La date à formater au format 'Y-m-d H:i:s'.
     * @return string La chaîne de texte relative à la date donnée.
     */
    public function getDatePublicString(string $date) : string{
        $todayDate = strtotime(date('Y-m-d H:i:s'));
        $timeSincePostUpload = $todayDate - strtotime($date);
        if($timeSincePostUpload < 60){
            return 'Il y a ' .  $timeSincePostUpload . ' secondes';
        }
        if($timeSincePostUpload < 3600){
            return 'Il y a ' . (round($timeSincePostUpload/60)) . ' minutes';
        }
        if($timeSincePostUpload < 86400){
            return 'Il y a ' . (round($timeSincePostUpload/60/60)) . ' heures';
        }
        else{
            return explode(' ', $date)[0];
        }
    }

    public function showPostEditView() : void{
        ob_start();
        ?>
        <div class="post" id="postEdit">
            <a id="<?= $this->post->idPost?>" href="index.php?action=profile&id=<?= $this->post->getUser()->getUserID() ?>">
                <img alt="author profile picture" class="profile-picture" src="<?=$this->post->getUser()->getProfilPicPath()?>" />
            </a>
                <!-- Header du post -->
                <div class="postHeader">
                    <div class="authorInfo">
                        <?php
                        echo '<h2>' . $this->post->getUser()->getPseudo() . '</h2>';
                        echo '<h3>' . $this->getDatePublicString($this->post->getDatePubli()) . '</h3>';
                        ?>
                    </div>

                    <!-- Liste des catégories -->
                    <div class="postCategories">
                        <?php foreach ($this->post->getCategories() as $cat){
                            echo '<p class="category" style="background-color:' . $cat->getColorCode() . '">' . strtoupper($cat->getLibelle()) . '</p>';
                        }?>
                    </div>
                </div>

                <span class="separator"></span>

                <!-- Contenue du post -->
                <div class="postContent">
                    <form action="index.php?action=editPost&id=<?=$this->post->idPost?>" method="post">
                        <input type="text" name="title" value="<?= $this->post->getTitre()?>">
                        <textarea name="message"><?= $this->post->message?></textarea>
                        <input type="submit" class="authButtons" value="Modifier">
                    </form>
                </div>
        </div>
        <?php
        $content = ob_get_clean();
        (new \chirpchat\views\layout\MainLayout('Modification du post', $content))->show(['styles.css','post.css']);
    }
}

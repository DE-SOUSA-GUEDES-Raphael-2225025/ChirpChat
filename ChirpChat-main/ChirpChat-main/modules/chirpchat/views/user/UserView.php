<?php

namespace chirpchat\views\user;

use ChirpChat\Model\Post;
use ChirpChat\Model\User;

class UserView{

    /**
     * Affiche le profil de l'utilisateur avec ses informations et ses publications.
     *
     * @param User $user L'utilisateur dont le profil est affiché.
     * @param Post[] $userPostList Un tableau de publications de l'utilisateur.
     * @return void
     */
    public function displayUserProfile(User $user, array $userPostList) : void{
        ob_start();
        ?>
        <div id="profile-page-container">
            <header id="profile-page-header">
                <img id="profile-picture" alt="profile picture" src="<?= $user->getProfilPicPath() ?>">
                <h2><?= $user->getUsername() ?></h2>

                <div id="profile-buttons">
                    <?php if(isset($_SESSION['ID']) && $_SESSION['ID'] === $user->getUserID())
                        {
                            echo '<input type="button" value="Modifier Profil" onclick="openUserSettings()">';
                        } else
                        {
                            echo '<a href="index.php?action=privateMessage&id=' . $user->getUserID() . '"><input id="send-message-button" type="button" value="Message"></a>';
                        }
                        if(User::isSessionUserAdmin()){
                            echo '<a class="ban-button" href="index.php?action=banUser&id=' . $user->getUserID() . '">Bannir</a>';
                        }
                    ?>
                </div>

                <p><?= $user->getDescription() ?></p>
            </header>

            <script src="/_assets/js/userProfile.js"></script>
            <div id="profile-settings-menu">
                <svg id='close-settings' onclick="closeUserSettings()" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
                <form method="post" action="index.php?action=modifyProfile" enctype="multipart/form-data">
                    <h3>Modifier le profil</h3>
                    <label>Pseudo
                        <input type="text" value="<?= $user->getUsername() ?>" class="inputField" name="username">
                    </label>

                    <label>Description
                        <textarea class="inputField" name="description"><?= $user->getDescription() ?></textarea>
                    </label>

                    <label>Photo de profil
                        <input type="file" name="img_upload">
                    </label>

                    <input type="submit" name="img_submit" value="Modifier" class="authButtons">
                </form>
            </div>

            <div id="postList">
            <?php
            foreach($userPostList as $post){
                (new \chirpchat\views\post\PostView($post))->show();
            }
        ?>
            </div>
        </div>
        <?php

        $content = ob_get_clean();
        (new \chirpchat\views\layout\MainLayout("Profil de " . $user->getUsername(), $content))->show(['profile.css', 'post.css']);
    }
}

<?php

namespace chirpchat\views\auth;
use ChirpChat\Utils\Background;

/**
 * Vue pour la page d'inscription.
 */
class InscriptionView{
    /**
     * Affiche le formulaire d'inscription.
     *
     * @return void
     */
    public function show() : void{
        ob_start();
        ?>
        <form id="registerForm" action="index.php?action=registerUser" method="post">

            <h2 id="inscriptionTitle">INSCRIPTION</h2>

            <label>Nom d'utilisateur
                <input class="inputField" type="text" name="username">
            </label>

            <label>Pseudo
                <input class="inputField" type="text" name="pseudonyme">
            </label>

            <label>Email
                <input class="inputField" type="text" name="email">
            </label>

            <label>Date de naissance
                <input class="inputField" type="date" name="birthdate">
            </label>

            <label>Mot de passe
                <input class="inputField" type="password" name="password">
            </label>

            <input class="authButtons" type="submit" value="S'inscrire">

            <p id="liensLogin"><a href="index.php?action=connexion"> Vous avez déja un compte ? </a></p>
        </form>

        <?php

        Background::displayBackgroundShapes();
        $content = ob_get_clean();
        (new \chirpchat\views\layout\MainLayout("Inscription", $content))->show(['authentification.css']);
    }

}

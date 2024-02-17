<?php

namespace Chirpchat\Views\auth;
use ChirpChat\Utils\Background;

/**
 * Vue pour la page de connexion.
 */
class LoginView {
    /**
     * Constructeur de la classe LoginView.
     *
     *
     */
    public function __construct() {}
    /**
     * Affiche le formulaire de connexion.
     *
     * @return void
     */
    public function show() : void {
        ob_start();
        ?><form id="loginForm" action="index.php?action=loginUser" method="post">
            <h2>CONNEXION</h2>
            <label>Email
                <input class="inputField" type="text" name="email" placeholder="E-mail"> <br> <!-- L'utilisateur rentre son e-mail ici -->
            </label>

            <label>Mot de passe
                <input class="inputField" type="password" name="password" placeholder="Mot de passe"> <br> <!-- L'utilisateur rentre son mot de passe ici -->
            </label>

            <input class="authButtons" type="submit" value="Se connecter"><br> <!-- Bouton pour valider la connexion -->
            <a href="index.php?action=inscription" class="authButtons">S'inscrire</a>

            <a href="index.php?action=recuperation" id="lienForgetPassword">Mot de passe oublié?</a> <!-- Bouton pour aller vers la page pour récupérer le mot de passe -->
        </form>

        <?php
        Background::displayBackgroundShapes();
        $content = ob_get_clean();
        (new \chirpchat\views\layout\MainLayout("Connexion", $content))->show(['authentification.css']);
    }
}
?>

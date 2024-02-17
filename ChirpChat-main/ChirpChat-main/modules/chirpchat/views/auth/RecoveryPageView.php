<?php

namespace chirpchat\views\auth;
use ChirpChat\Utils\Background;

/**
 * Vue pour la page de récupération de mot de passe.
 */
class RecoveryPageView {
/**
 * Constructeur de la classe Recovery.
 *
 * @param string $errorMessage Message d'erreur affiché lors de la récupération de mot de passe.
 */
    public function __construct(private string $errorMessage = '') {}
/**
 * Affiche le formulaire de récupération de mot de passe.
 *
 * @return void
 */
    public function displayEmailSendView() : void {
        ob_start();
        if(!empty($this->errorMessage))
        {?>
            <div class="errorMessage">
                <h2><?= $this->errorMessage ?></h2>
            </div><?php
        }
        ?>

        <form id="passwordRecupForm" action="index.php?action=sendVerificationMail" method="post">
            <h2>RECUPERATION DU MOT DE PASSE</h2>
            <label>Email
                <input class="inputField" type="text" name="email" placeholder="E-mail de récuperation"> <!-- L'utilisateur rentre son e-mail ici -->
            </label>
                <input class="authButtons" type="submit" value="Envoyer un email"> <!-- Bouton pour valider les champs -->
        </form>

        <?php
        Background::displayBackgroundShapes();
        $content = ob_get_clean();
        (new \chirpchat\views\layout\MainLayout("Mot de passe oublié", $content))->show(['styles.css','authentification.css']);
    }

    public function displayPasswordChangeView(string $code, string $email) : void{
        ob_start(); ?>
        <form id="passwordChangeForm" action="index.php?action=changePassword" method="post">
            <h2>CHANGER LE MOT DE PASSE</h2>
            <label>Code
                <input class="inputField" type="text" name="code" value="<?= $code ?>" readonly><!-- L'utilisateur rentre son e-mail ici -->
            </label>

            <label style="display: none">Email
                <input type="hidden" name="email" value="<?= $email ?>">
            </label>

            <label>Mot de passe
                <input class="inputField" type="password" name="password" placeholder="Mot de passe"><!-- L'utilisateur rentre son e-mail ici -->
            </label>

            <label>Confirmer mot de passe
                <input class="inputField" type="password" name="passwordConfirm" placeholder="Confirmer mot de passe"><!-- L'utilisateur rentre son e-mail ici -->
            </label>

            <input id="forget-password-button" class="authButtons" type="submit" value="CHANGER LE MOT DE PASSE"> <!-- Bouton pour valider les champs -->
        </form>

        <?php
        Background::displayBackgroundShapes();
        $content = ob_get_clean();
        (new \chirpchat\views\layout\MainLayout("Mot de passe oublié", $content))->show(['styles.css','authentification.css']);
    }
}
?>

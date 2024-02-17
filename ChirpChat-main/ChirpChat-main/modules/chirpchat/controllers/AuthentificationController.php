<?php

namespace ChirpChat\Controllers;

use Chirpchat\Views\auth\LoginView;

class AuthentificationController
{
    /** Affiche la page de connection
     *
     * @return void
     */
    public function displayLoginPage() : void{
        (new LoginView())->show();
    }

    /** Affiche la page d'inscription
     * @return void
     */
    public function displayRegisterPage() : void{
        (new \chirpchat\views\auth\InscriptionView())->show();
    }

    /** Affiche la page de mot de passe oublié
     * @return void
     */
    public function displayPasswordForgetPage() : void{
        (new \chirpchat\views\auth\RecoveryPageView())->displayEmailSendView();
    }
}

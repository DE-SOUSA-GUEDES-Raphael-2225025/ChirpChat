<?php

namespace chirpchat\views\layout;
use Chirpchat\Model\Database;use ChirpChat\Model\UserRepository;use ChirpChat\Views\layout;
/**
 * Class MainLayout
 *
 * Cette classe gère la mise en page principale d'une page web.
 */
class MainLayout {
/**
 * Constructeur de la classe MainLayout.
 *
 * @param string $title   Le titre de la page.
 * @param string $content Le contenu de la page.
 */
    public function __construct(private string $title, private string $content) { }

/**
 * Affiche la page web avec un ensemble de styles.
 *
 * @param array $styles Un tableau contenant les noms des fichiers de styles à inclure.
 *
 * @return void
 */
    public function show(array $styles) : void {
?><!doctype html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=1">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="author" content="ATROKHOV, BAMAS, BENDJEDDOU, DE SOUSA, DA SILVA SANTOS, SCIACCA">
        <meta name="description" content="Découvrez ChirpChat le nouveau réseaux social n°1, envoyer des posts, likez et parlez par message ! ">
        <link rel="stylesheet" href="../../../_assets/styles/styles.css">
        <link rel="stylesheet" href="../../../_assets/styles/navbar.css">
        <script src="https://unpkg.com/slim-select@latest/dist/slimselect.min.js"></script> <!-- SCRIPT MENU DEROULANT A CHOIX MULTIPLE -->
        <script src="/_assets/js/navbar.js"></script>
        <link href="https://unpkg.com/slim-select@latest/dist/slimselect.css" rel="stylesheet">

        <!-- Ajout des styles donnés -->
        <?php
            foreach($styles as $style)
            {
                echo '<link rel="stylesheet" href="/_assets/styles/' . $style . '">';
            }
        ?>

        <link rel="stylesheet" href="_assets/styles/styles.css">
        <link rel="icon" href="/favicon.png">
        <title><?= $this->title?></title>
    </head>
    <body>
        <!-- AFFICHE LA NAVBAR -->
        <?php
        if(isset($_SESSION['ID'])){
            $userRepo = new UserRepository(Database::getInstance()->getConnection());
            (new layout\NavBarLayout($userRepo->getUser($_SESSION['ID'])))->displayNavBar();
        }else{
            (new layout\NavBarLayout(null))->displayNavBar();
        }

        /* Une page est affiché on peut afficher la notification */
        $_SESSION['notification']['show'] = true;
        ?>

        <!-- CONTENU DE LA PAGE -->
        <main id="pageContent">
            <?= $this->content; ?>
        </main>
    </body>
    </html>
<?php
    }
}
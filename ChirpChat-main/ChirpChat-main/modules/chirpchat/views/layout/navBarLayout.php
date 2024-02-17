<?php

namespace chirpchat\views\layout;

use ChirpChat\Model\User;

/**
 * Classe NavBarLayout
 *
 * Cette classe gère la mise en page de la barre de navigation (navbar) sur le site.
 */
class NavBarLayout{
    /**
     * Constructeur de la classe NavBarLayout.
     *
     * @param User|null $user L'utilisateur connecté (ou null si non connecté).
     */
    public function __construct(private ?User $user){ }

    /**
     * Affiche la barre de navigation.
     *
     * @return void
     */
    function displayNavBar() : void{
        ?>
        <nav>
            <script src="/_assets/js/navBarSearch.js"></script>
            <a id="top"></a>
            <!-- TOP NAV BAR -->
            <div id="topNavBar">
                <div id="logoDiv">
                    <img alt="Logo" id="logo" src="/_assets/images/Logo.png">
                    <h1 style="margin-top: 15px"><a href="index.php">ChirpChat</a></h1>
                </div>

                <!-- BARRE DE RECHERCHE ORDINATEUR/TABLETTE -->
                <form action="index.php?action=search" method="post">
                    <button type="submit" aria-label="recherche">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </button>
                    <input type="text" name="filter" placeholder="Rechercher...">
                </form>
                <?php $this->displayActionIconsList() ?>

            </div>

            <!-- NAVBAR MOBILE -->
            <div id="mobileViewBottomBar">
                <?php $this->displayActionIconsList() ?>
                <div id="addPostButtonContainer">
                    <a href="index.php#top" aria-label="haut de page" class="link"></a>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                        <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm.53 5.47a.75.75 0 00-1.06 0l-3 3a.75.75 0 101.06 1.06l1.72-1.72v5.69a.75.75 0 001.5 0v-5.69l1.72 1.72a.75.75 0 101.06-1.06l-3-3z" clip-rule="evenodd" />
                    </svg>
                </div>
            </div>

            <!-- BARRE DE RECHERCHE MOBILE-->
            <form id="searchBar" action="index.php?action=search" method="post">
                <button type="submit" aria-label="rechercher">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </button>

                <input type="text" name="filter" placeholder="Rechercher...">
                <button onclick="closeNavBarSearch()">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </form>

        </nav>
<?php
    }

    function displayActionIconsList() : void{
        ?>
            <ul>
                <!-- SECTION ACCUEIL -->
                <li>
                    <a href="index.php" class="link" aria-label="Page d'accueil"></a>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                    <p>Accueil</p>
                </li>

                <!-- SECTION MESSAGES -->
                <li>
                    <a href="index.php?action=privateMessage" class="link" aria-label="Page de messages privés"></a>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                    </svg>
                    <p>Messages</p>
                </li>

                <!-- SECTION RECHERCHE -->
                <li onclick="openNavBarSearch()">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                    <p>Rechercher</p>
                </li>

                <!-- SECTION PROFIL-->
                <li onclick="openCloseUserMenu(this)">
                    <?php if(isset($_SESSION['ID'])){
                        ?>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>

                        <!-- MENU SCROLL PROFILE -->
                        <div class="menuClose scrolledProfile">
                            <ul>
                                <li>
                                    <a href="index.php?action=profile&id=<?= $_SESSION['ID'] ?>" class="link" aria-label="Page de profil"></a>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" fill-rule="evenodd" d="M8 7a4 4 0 1 1 8 0a4 4 0 0 1-8 0Zm0 6a5 5 0 0 0-5 5a3 3 0 0 0 3 3h12a3 3 0 0 0 3-3a5 5 0 0 0-5-5H8Z" clip-rule="evenodd"/></svg>
                                    <p>Profil</p>
                                </li>
                                <li class="logoutMenu">
                                    <a href="index.php?action=logout" class="link" aria-label="déconnexion"></a>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 36 36"><path fill="currentColor" d="M23 4H7a2 2 0 0 0-2 2v24a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6h-9.37a1 1 0 0 1-1-1a1 1 0 0 1 1-1H25V6a2 2 0 0 0-2-2Z" class="clr-i-solid clr-i-solid-path-1"/><path fill="currentColor" d="M28.16 17.28a1 1 0 0 0-1.41 1.41L30.13 22H25v2h5.13l-3.38 3.46a1 1 0 1 0 1.41 1.41l5.84-5.8Z" class="clr-i-solid clr-i-solid-path-2"/><path fill="none" d="M0 0h36v36H0z"/></svg>
                                    <p>Se déconnecter</p>
                                </li>
                            </ul>
                        </div>
                        <p>Profil</p>

                        <?php
                    }else{
                    ?>
                    <!-- SECTION CONNECTION -->
                    <a href="index.php?action=connection">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                        </svg>
                        <p>Connexion</p>
                    </a>
                        <?php
                        }?>
                </li>
            </ul>
        <?php
    }
}

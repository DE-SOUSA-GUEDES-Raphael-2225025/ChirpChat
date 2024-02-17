<?php

    require '_assets/includes/autoloader.php';

    session_start();

    /** VERIFIE SI LA SESSION DE L'UTILISATEUR EST TOUJOURS VALIDE */
    if(isset($_SESSION['ID'])) {
        $userRepository = new \ChirpChat\Model\UserRepository(\Chirpchat\Model\Database::getInstance()->getConnection());
        $userRepository->setNewConnectionDate($_SESSION['ID']);
        if (!$userRepository->isUserSessionValid($_SESSION['ID'])) { /* LE COMPTE N'EXISTE PLUS*/
            session_destroy();
        }
    }

    if(filter_input(INPUT_GET, 'action')) {
        if ($_GET['action'] === 'inscription') {
            (new \ChirpChat\Controllers\AuthentificationController())->displayRegisterPage();
        } else if ($_GET['action'] === 'connexion') {
            (new \ChirpChat\Controllers\AuthentificationController())->displayLoginPage();
        } else if ($_GET['action'] === 'recuperation') {
            (new \ChirpChat\Controllers\AuthentificationController())->displayPasswordForgetPage();
        } else if ($_GET['action'] === 'registerUser') {
            (new \ChirpChat\Controllers\UserController())->register();
        } else if ($_GET['action'] === 'loginUser') {
            (new \ChirpChat\Controllers\UserController())->login();
        }  else if ($_GET['action'] === 'sendVerificationMail') {
            (new \ChirpChat\Controllers\UserController())->sendVerificationMail();
        } else if ($_GET['action'] === 'changePasswordView') {
            (new \ChirpChat\Controllers\UserController())->displayChangePasswordPage();
        }else if ($_GET['action'] === 'changePassword') {
            (new \ChirpChat\Controllers\UserController())->changePassword();
        }else if ($_GET['action'] === 'comment'){
            if(isset($_GET['id'])){
                (new \ChirpChat\Controllers\CommentController())->displayComment();
            }
        }  else if ($_GET['action'] === 'search'){
            (new \ChirpChat\Controllers\SearchBarController())->search();
        } else if ($_GET['action'] === 'deleteCategory'){
            (new \ChirpChat\Controllers\CategoryController())->deleteCategory();
        } else if ($_GET['action'] === 'categoryList'){
            (new \ChirpChat\Controllers\CategoryController())->displayCategoryListPage();
        } else if ($_GET['action'] === 'categoryCreation'){
            (new \ChirpChat\Controllers\CategoryController())->displayCategoryCreationPage();
        } else if ($_GET['action'] === 'createCategory'){
            (new \ChirpChat\Controllers\CategoryController())->createCategory();
        } else if ($_GET['action'] === 'profile'){
            if(isset($_GET['id'])){
                (new \ChirpChat\Controllers\UserController())->displayUserProfile($_GET['id']);
            }
        } else if($_GET['action'] === 'searchPostInCategory'){
            (new \ChirpChat\Controllers\SearchBarController())->searchPostInCategorie();
        }

        // ---- A BESOIN QUE L'UTILISATEUR SOIT CONNECTÉ ----

        else if (!isset($_SESSION['ID'])){
            header("Location:index.php?action=connexion");
        }
        else if ($_GET['action'] === 'sendPost') {
            (new \ChirpChat\Controllers\PostController())->addPost();
        } else if ($_GET['action'] === 'like'){
            (new \ChirpChat\Model\PostRepository(\Chirpchat\Model\Database::getInstance()->getConnection()))->addLike($_GET['id'],$_SESSION['ID']);
            header("Location:index.php#" . $_GET['id']);
        }
        else if ($_GET['action'] === 'addComment') {
            (new \ChirpChat\Controllers\CommentController())->addComment();
        } else if ($_GET['action'] === 'privateMessage'){
            (new \ChirpChat\Controllers\PrivateMessageController())->displayPrivateMessagePage($_SESSION['ID']);
        }
        else if ($_GET['action'] === 'sendMessageTo'){
            if(isset($_GET['id'])){
                (new \ChirpChat\Controllers\PrivateMessageController())->sendMessageTo($_GET['id']);
            }
        }
        else if ($_GET['action'] === 'deletepost'){
            if(isset($_GET['id'])){
                (new \ChirpChat\Controllers\PostController())->deletePost($_GET['id']);
            }
        }
        else if ($_GET['action'] === 'logout'){
            (new \ChirpChat\Controllers\UserController())->logout();
        }

        else if($_GET['action'] === 'modifyProfile'){
            (new \ChirpChat\Controllers\UserController())->modifyProfile();
        }
        else if($_GET['action'] === 'updateCategory'){
            (new \ChirpChat\Controllers\CategoryController())->updateCategory();
        }else if($_GET['action'] === 'updateCategoryPage'){
            (new \ChirpChat\Controllers\CategoryController())->displayCategoryUpdatePage();
        }else if($_GET['action'] === 'editPost'){
            if(isset($_POST['message'])){
                (new \ChirpChat\Controllers\PostController())->updatePost();
            }else{
                (new \ChirpChat\Controllers\PostController())->displayEditPostPage();
            }
        } else if($_GET['action'] === 'banUser'){
            (new \ChirpChat\Controllers\UserController())->banUser();
        }

    }
    else {
        if(isset($_GET['page']) && $_GET['page'] > 0){
            (new \ChirpChat\Controllers\HomepageController())
                ->setPageNb($_GET['page'])
                ->execute();
        }else{
            (new \ChirpChat\Controllers\HomepageController())->execute();
        }
    }

    if(isset($_SESSION['notification']) && $_SESSION['notification']['show']){
        if(isset($_SESSION['notification']['type'])) {
            switch ($_SESSION['notification']['type']) {
                case 'ERROR':
                    echo '<p class="error notification">' . $_SESSION['notification']['message'] . '</p>';
                    break;
                case 'SUCCESS':
                    echo '<p class="success notification">' . $_SESSION['notification']['message'] . '</p>';
                    break;
                case 'INFO':
                    echo '<p class="info notification">' . $_SESSION['notification']['message'] . '</p>';
                    break;
            }
            unset($_SESSION['notification']);
        }
    }




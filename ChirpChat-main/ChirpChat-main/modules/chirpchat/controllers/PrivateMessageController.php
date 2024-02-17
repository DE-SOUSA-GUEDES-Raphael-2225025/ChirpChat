<?php

namespace ChirpChat\Controllers;

use Chirpchat\Model\Database;
use ChirpChat\Model\PrivateMessageRepository;
use ChirpChat\Model\UserRepository;
use chirpchat\views\privatemessage\PrivateMessageView;

class PrivateMessageController{

    public function displayPrivateMessagePage(string $userID) : void {
        if(!isset($_SESSION['ID'])) return;

        $userRepo = new UserRepository(Database::getInstance()->getConnection());
        $firstUser = $userRepo->getUser($_SESSION['ID']);
        $privateMessageRepo = new PrivateMessageRepository(Database::getInstance()->getConnection());
        $privateMessageView = new PrivateMessageView();
        $userList = $privateMessageRepo->getUsersWhoSendMessageTo($_SESSION['ID']);

        if(isset($_GET['id'])){
            $messageList = $privateMessageRepo->getPrivateMessageBetweenUsers($firstUser->getUserID(), $_GET['id']);
            $privateMessageView->setTargetUser($userRepo->getUser($_GET['id']));
            $privateMessageView->setPrivateMessageWithUserList($messageList);
        }

        $privateMessageView->setUserList($userList);
        $privateMessageView->displayPrivateMessageView();
    }


    public function sendMessageTo(string $targetID) : void{
        $privateMessageRepo = new PrivateMessageRepository(Database::getInstance()->getConnection());
        $message = $_POST['message'];
        $privateMessageRepo->sendMessageToUser($_SESSION['ID'], $targetID, $message);

        header('Location:index.php?action=privateMessage&id=' . $targetID);
    }
}
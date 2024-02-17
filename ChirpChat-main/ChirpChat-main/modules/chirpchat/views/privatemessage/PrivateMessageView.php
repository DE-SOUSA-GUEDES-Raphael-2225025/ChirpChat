<?php

namespace chirpchat\views\privatemessage;

use ChirpChat\Model\PrivateMessage;
use ChirpChat\Model\User;
use ChirpChat\Views\layout;

class PrivateMessageView {

    private array $userList;
    private array $privateMessageWithUserList;
    private User $targetUser;

    public function setUserList(array $userList) : void{
        $this->userList = $userList;
    }

    public function setPrivateMessageWithUserList(array $messageList) : void{
        $this->privateMessageWithUserList = $messageList;
    }

    public function setTargetUser(User $targetUser) : void {
        $this->targetUser = $targetUser;
    }

    /**
     * @param PrivateMessage[] $privateMessages
     * @return void
     */
    public function displayPrivateMessageWithUser() : PrivateMessageView {
        ?>
        <script src="/_assets/js/togglePrivateMessageUserList.js"></script>

        <div id="privateMessagesContainer">
            <button id="toggle-user-btn" onclick="toggleMenu()">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                </svg>
            </button>

            <header>
                <img alt='profile picture' class="profile-picture" src="<?= $this->targetUser->getProfilPicPath() ?>">
                <h2><?= $this->targetUser->getUsername() ?></h2>
                <p><?= $this->targetUser->getDescription() ?></p>
            </header>
            <div id="privateMessageList"> <?php
                foreach ($this->privateMessageWithUserList as $message){
                    if ($message->getAuthor()->getUserID() == $_SESSION['ID']){
                        echo '<div class="privateMessageSent privateMessage"><p>' .  $message->getMessage() . '</p></div>';
                    } else{
                        echo '<div class="privateMessageReceived privateMessage"><p>' .  $message->getMessage() . '</p></div>';
                    }
                }
                ?></div>
            <form id="privateMessageForm" action="index.php?action=sendMessageTo&id=<?= $this->targetUser->getUserID() ?> " method="post">
                <input type="text" placeholder="Message" name="message" required>
                <input type="submit" value="ENVOYER">
            </form>
        </div>
        <?php
        return $this;
    }

    public function displayEmptyMessageBox(){
        echo '<div id="privateMessagesContainer"> </div>';
    }

    /**
     * @param User[] $userList
     * @return void
     */
    public function displayUserList(array $userList) : void{
        ?>
        <div id="all-users-container" <?php if(isset($_GET['id'])) echo 'class="close"' ?> >
            <?php foreach ($userList as $user){
                ?>
                    <div class="user-container">
                        <a class="link" href="index.php?action=privateMessage&id=<?=$user->getUserID()?>"></a>
                        <img alt='profile picture' class="profile-picture" src="<?= $user->getProfilPicPath(); ?>" >
                        <h3><?= $user->getUsername(); ?></h3>
                    </div>
            <?php } ?>
        </div>
        <?php
    }


    public function displayPrivateMessageView() : void{
        ob_start();
        echo '<div id="private-message-container">';
        $this->displayUserList($this->userList);
        if(!isset($this->targetUser)){
            $this->displayEmptyMessageBox();
        }else{
            $this->displayPrivateMessageWithUser();
        }
        $pageContent = ob_get_clean() . '</div>';

        (new layout\MainLayout('Private message', $pageContent))->show(['privateMessage.css']);
    }

}
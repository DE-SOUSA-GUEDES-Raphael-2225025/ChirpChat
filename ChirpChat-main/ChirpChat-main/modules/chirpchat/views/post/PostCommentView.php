<?php

namespace chirpchat\views\post;
use ChirpChat\Model\Post;
use ChirpChat\Model\User;
use ChirpChat\Utils\Background;

/**
 * Class PostCommentView
 *
 * Cette classe gère l'affichage de la page de commentaires pour un post donné.
 */
class PostCommentView{
    /**
     * @var string Contenu principal de la page de commentaires.
     */
    private string $content;
    private string $mainPostView;
    private string $commentListView;
    /**
     * Configure la vue du post principal.
     *
     * @param Post $post Le post principal.
     * @return PostCommentView $this pour permettre la chaîne d'appels.
     */
    public function setMainPost($post, ?User $user) : PostCommentView{
        ob_start();
        ?> <h2 id="commentSectionTitle">Répondre à <?php echo $post->getUser()->getUsername()?></h2>
            <?php (new \chirpchat\views\post\PostView($post))->show(); ?>
            <form id="ChampReponse" action="index.php?action=addComment&id=<?php echo $post->idPost?>" method="post">

                <?php if($user != null){ ?><img alt="profilePicture" src="<?=$user->getProfilPicPath()?>"> <?php }
                else{ ?> <img alt="profilePicture" src="https://cdn-icons-png.flaticon.com/512/168/168724.png"> <?php } ?>

                <textarea name="comment" placeholder="Donnez votre avis !"></textarea>
                <input type="submit" value="ENVOYER">
            </form>
        <?php
        $this->mainPostView = ob_get_clean();
        return $this;
    }
    /**
     * Configure la vue de la liste de commentaires.
     *
     * @param array $commentList La liste des commentaires.
     * @return PostCommentView $this pour permettre la chaîne d'appels.
     */
    public function setPostComments($commentList) : PostCommentView {
        ob_start();
        ?><div id="commentList">
            <?php
            foreach ($commentList as $comment){
                (new \chirpchat\views\post\PostView($comment))->show();
            }?>
        </div>
        <?php
        Background::displayBackgroundShapes();
        $this->commentListView = ob_get_clean();
        return $this;
    }
    /**
     * Affiche la page de commentaires.
     */
    public function displayCommentPage() : void {
        $this->content =
            '<div id="commentPage">' .
            $this->mainPostView .
            $this->commentListView .
            '</div>';
        (new \chirpchat\views\layout\MainLayout("Commentaire", $this->content))->show(['postComment.css', 'post.css']);
    }
}
<?php

namespace ChirpChat\Model;

use ChirpChat\Model\User;
/**
 * Représente un message privé envoyé entre utilisateurs.
 */
class PrivateMessage{
    /**
     * Crée une nouvelle instance de la classe PrivateMessage.
     *
     * @param User $author L'utilisateur qui a envoyé le message.
     * @param User $target L'utilisateur destinataire du message.
     * @param string $message Le contenu du message privé.
     */
    public function __construct(private readonly User $author, private readonly User $target, private readonly string $message){ }
    /**
     * Récupère l'utilisateur qui a envoyé le message.
     *
     * @return User L'utilisateur qui a envoyé le message.
     */
    public function getAuthor(): \ChirpChat\Model\User
    {
        return $this->author;
    }
    /**
     * Récupère l'utilisateur destinataire du message.
     *
     * @return User L'utilisateur destinataire du message.
     */
    public function getTarget(): \ChirpChat\Model\User
    {
        return $this->target;
    }
    /**
     * Récupère le contenu du message privé.
     *
     * @return string Le contenu du message privé.
     */
    public function getMessage(): string
    {
        return $this->message;
    }

}

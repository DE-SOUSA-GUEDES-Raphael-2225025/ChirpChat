<?php

use PHPUnit\Framework\TestCase;

final class UserTest extends TestCase
{

    private $userWithoutDesc;
    private $userWithDesc;

    public function setUp() : void {
        $this->userWithoutDesc = new \ChirpChat\Model\User("651841813ee15", "testutilisateur", "user@gmail.com", "pseudoTEST123", "USER", null);
        $this->userWithDesc = new \ChirpChat\Model\User("651841813ee20", "toto", "toto@gmail.com", "supertoto", "ADMIN", "test Description.");
    }

    public function testClassConstructor(): void {
        /* constructeur de  userWithoutDesc*/
        $this->assertSame("651841813ee15", $this->userWithoutDesc->getUserID());
        $this->assertSame("testutilisateur", $this->userWithoutDesc->getUsername());
        $this->assertSame("user@gmail.com", $this->userWithoutDesc->getEmail());
        $this->assertSame("pseudoTEST123", $this->userWithoutDesc->getPseudo());
        $this->assertSame("USER", $this->userWithoutDesc->getRole());
        $this->assertEmpty($this->userWithoutDesc->getDescription());
        /* constructeur de  userWithDesc*/
        $this->assertSame("651841813ee20", $this->userWithDesc->getUserID());
        $this->assertSame("toto", $this->userWithDesc->getUsername());
        $this->assertSame("toto@gmail.com", $this->userWithDesc->getEmail());
        $this->assertSame("supertoto", $this->userWithDesc->getPseudo());
        $this->assertSame("ADMIN", $this->userWithDesc->getRole());
        $this->assertSame("test Description.", $this->userWithDesc->getDescription());
    }

    public function testGetUserId(): void {
        /* UserId de userWithoutDesc*/
        $this->assertIsString($this->userWithoutDesc->getUserID());
        $this->assertSame("651841813ee15", $this->userWithoutDesc->getUserID());
        /* UserId de userWithDesc*/
        $this->assertIsString($this->userWithDesc->getUserID());
        $this->assertSame("651841813ee20", $this->userWithDesc->getUserID());
    }

    public function testGetUsername(): void {
        /* Username de userWithoutDesc*/
        $this->assertIsString($this->userWithoutDesc->getUsername());
        $this->assertSame("testutilisateur", $this->userWithoutDesc->getUsername());
        /* Username de userWithDesc*/
        $this->assertIsString($this->userWithDesc->getUsername());
        $this->assertSame("toto", $this->userWithDesc->getUsername());
    }

    public function testGetEmail(): void {
        /* Email de userWithoutDesc*/
        $this->assertIsString($this->userWithoutDesc->getEmail());
        $this->assertSame("user@gmail.com", $this->userWithoutDesc->getEmail());
        /* Email de userWithDesc*/
        $this->assertIsString($this->userWithDesc->getEmail());
        $this->assertSame("toto@gmail.com", $this->userWithDesc->getEmail());
    }

    public function testGetPseudo(): void {
        /* Pseudo de userWithoutDesc*/
        $this->assertIsString($this->userWithoutDesc->getPseudo());
        $this->assertSame("pseudoTEST123", $this->userWithoutDesc->getPseudo());
        /* Pseudo de userWithDesc*/
        $this->assertIsString($this->userWithDesc->getPseudo());
        $this->assertSame("supertoto", $this->userWithDesc->getPseudo());
    }

    public function testGetDescription(): void {
        /* Description de userWithDesc*/
        $this->assertIsString($this->userWithDesc->getDescription());
        $this->assertSame("test Description.", $this->userWithDesc->getDescription());
        /* Description de userWithoutDesc*/
        $this->assertEmpty($this->userWithoutDesc->getDescription());
    }

    public function testGetProfilPicPath(): void {
        /* Chemin de pdp de userWithoutDesc*/
        $this->assertIsString($this->userWithoutDesc->getProfilPicPath());
        $this->assertSame("_assets/images/user_pic/default.png", $this->userWithoutDesc->getProfilPicPath());
        /* Chemin de pdp de userWithDesc*/
        $this->assertIsString($this->userWithDesc->getProfilPicPath());
        $this->assertSame("_assets/images/user_pic/default.png", $this->userWithDesc->getProfilPicPath());
    }

}
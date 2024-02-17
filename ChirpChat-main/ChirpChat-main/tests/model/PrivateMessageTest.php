<?php

use PHPUnit\Framework\TestCase;

final class PrivateMessageTest extends TestCase
{
    private $expediteur;
    private $recepteur;
    private $message;
    private $pm;

    public function setUp() : void {
        $this->expediteur = new \ChirpChat\Model\User("651841813ee15", "toto", "toto@gmail.com", "supertoto", "USER", null);
        $this->recepteur = new \ChirpChat\Model\User("651841813ee20", "tata", "tata@gmail.com", "supertata", "USER", null);
        $this->message = "Ceci est un message privé.";
        $this->pm = new \ChirpChat\Model\PrivateMessage($this->expediteur, $this->recepteur, $this->message);
    }

    public function testClassConstructor() : void {
        $this->assertSame($this->expediteur, $this->pm->getAuthor());
        $this->assertSame($this->recepteur, $this->pm->getTarget());
        $this->assertSame($this->message, $this->pm->getMessage());
    }

    public function testGetAuthor() : void {
        $this->assertInstanceOf(\ChirpChat\Model\User::class, $this->pm->getAuthor());
        $this->assertSame($this->expediteur, $this->pm->getAuthor());
    }

    public function testGetTarget() : void {
        $this->assertInstanceOf(\ChirpChat\Model\User::class, $this->pm->getTarget());
        $this->assertSame($this->recepteur, $this->pm->getTarget());
    }

    public function testGetMessage() : void {
        $this->assertIsString(\ChirpChat\Model\User::class, $this->pm->getMessage());
        $this->assertSame($this->message, $this->pm->getMessage());
    }

}
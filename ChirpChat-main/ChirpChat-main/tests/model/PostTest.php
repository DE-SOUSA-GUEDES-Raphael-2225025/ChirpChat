<?php

use PHPUnit\Framework\TestCase;

final class PostTest extends TestCase
{

    private $user;
    private $post;

    public function setUp() : void {
        $this->user = new \ChirpChat\Model\User("651841813ee15", "toto", "toto@gmail.com", "supertoto", "USER", "description trop cool");
        $this->post = new \ChirpChat\Model\Post("13", "Test Titre", "Lorem ipsum dolor sit amet.", "2023-10-25 15:12:43", [], $this->user, 25, 123);
    }

    public function testClassConstructor(): void {
        $this->assertSame("13", $this->post->idPost);
        $this->assertSame("Test Titre", $this->post->getTitre());
        $this->assertSame("Lorem ipsum dolor sit amet.", $this->post->message);
        $this->assertSame("2023-10-25 15:12:43", $this->post->getDatePubli());
        $this->assertEmpty($this->post->getCategories());
        $this->assertSame($this->user, $this->post->getUser());
        $this->assertSame(25, $this->post->commentAmount);
        $this->assertSame(123, $this->post->likeAmount);
    }

    public function testGetUser() : void{
        $this->assertInstanceOf(\ChirpChat\Model\User::class, $this->post->getUser());
        $this->assertSame($this->user, $this->post->getUser());
    }

    public function testGetCategories() : void{
        $this->assertEmpty($this->post->getCategories());
        $this->assertSame([], $this->post->getCategories());
    }

    public function testGetTitre() : void{
        $this->assertIsString($this->post->getTitre());
        $this->assertSame("Test Titre", $this->post->getTitre());
    }

    public function testGetDatePubli() : void{
        $this->assertIsString($this->post->getDatePubli());
        $this->assertSame("2023-10-25 15:12:43", $this->post->getDatePubli());
    }

}
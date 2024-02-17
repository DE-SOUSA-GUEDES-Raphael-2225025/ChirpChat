<?php

use PHPUnit\Framework\TestCase;

final class CategoryTest extends TestCase
{
    private $cat;
    public function setUp() : void {
        $this->cat = new \ChirpChat\Model\Category(1, "animaux", "les animaux sont nos amis.", "#FF3030");
    }

    public function testClassConstructor(): void {
        $this->assertSame(1, $this->cat->getIdCat());
        $this->assertSame("les animaux sont nos amis.", $this->cat->getDescription());
        $this->assertSame("animaux", $this->cat->getLibelle());
        $this->assertSame("#FF3030", $this->cat->getColorCode());
    }

    public function testGetIdCat(): void {
        $this->assertIsInt($this->cat->getIdCat());
        $this->assertSame(1, $this->cat->getIdCat());
    }

    public function testGetDescription(): void {
        $this->assertIsString($this->cat->getDescription());
        $this->assertSame("les animaux sont nos amis.", $this->cat->getDescription());
    }

    public function testGetLibelle(): void {
        $this->assertIsString($this->cat->getLibelle());
        $this->assertSame("animaux", $this->cat->getLibelle());
    }

    public function testGetColorCode(): void {
        $this->assertIsString($this->cat->getColorCode());
        $this->assertSame("#FF3030", $this->cat->getColorCode());
    }
}
<?php

namespace App\Tests\Entity;

use App\Entity\Product;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    public function testGettersAndSetters()
    {
        $product = new Product();

        $this->assertNull($product->getId());

        $product->setName('Test Product');
        $this->assertSame('Test Product', $product->getName());

        $product->setValue(99);
        $this->assertSame(99, $product->getValue());
    }
}

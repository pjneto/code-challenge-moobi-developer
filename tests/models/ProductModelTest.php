<?php 

require_once "src/models/Product.php";
require_once "src/controllers/ProductController.php";

use PHPUnit\Framework\TestCase;

class ProductModelTest extends TestCase {

    private $product = null;
    private $controller = null;

    protected function setUp(): void {

        $id = 3;
        $this->controller = is_null($this->controller) ? new ProductController : $this->controller;
        $this->product = is_null($this->product) ? $this->controller->get_product($id) : $this->product;
    }

    public function testHasAttributes() {

        $this->assertObjectHasAttribute("id", $this->product);
        $this->assertObjectHasAttribute("codStatus", $this->product);
        $this->assertObjectHasAttribute("stock", $this->product);
        $this->assertObjectHasAttribute("name", $this->product);
        $this->assertObjectHasAttribute("description", $this->product);
        $this->assertObjectHasAttribute("barcode", $this->product);
        $this->assertObjectHasAttribute("price", $this->product);
        $this->assertObjectHasAttribute("date", $this->product);
        $this->assertObjectHasAttribute("dateUpdate", $this->product);
    }

    public function testPriceGreaterThanZero() {

        $this->assertGreaterThan(0, $this->product->price);
    }

    public function testStockGreaterThanZero() {
        $this->assertGreaterThan(0, $this->product->stock);
    }

    public function testIncStock() {
        $stock = 22;
        $inc = 3;

        $this->product->stock = $stock;
        $this->product->inc_quantity($inc);
        $this->assertEquals($stock + $inc, $this->product->stock);
    }

    public function testDecStock() {
        $stock = 10;
        $dec = 5;

        $this->product->stock = $stock;
        $this->product->dec_quantity($dec);

        $this->assertEquals($stock - $dec, $this->product->stock);
    }

    public function testValuesNotNull() {
        $this->assertFalse($this->product->invalid_values());
    }
}
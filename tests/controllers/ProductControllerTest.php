<?php 

require_once "src/models/Product.php";
require_once "src/controllers/ProductController.php";

use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\Framework\TestCase;

class ProductControllerTest extends TestCase {

    use TestCaseTrait;

    private $controller = null;
    private $conn = null;
    private static $pdo = null;

    public function getConnection() {

        $this->controller = is_null($this->controller) ? new ProductController : $this->controller;

        try {
            $host = DB_HOST;
            $dbName = DBConnection::get_db_name();
            $username = DB_USER;
            $password = DB_PASSWORD;
            $port = DB_PORT;

            $options = array(
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8'
            );
            $strConnection = "mysql:host=$host;port=$port;dbname=$dbName";

            if (is_null($this->conn)) {
                if (is_null(self::$pdo)) {
                    self::$pdo = new PDO($strConnection, $username, $password, $options);
                    self::$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                    self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                }
                $this->conn = $this->createDefaultDBConnection(self::$pdo, $dbName);
            }
            return $this->conn;

        } catch (PDOException $e) {
            echo "Falha: " . $e->getMessage() . "\n";
        }
        return null;
    }
 
    protected function getDataSet() {
        return $this->createFlatXmlDataSet('./tests/persistence/product_db.xml');
    }

    public function testRowsCount() {
        $expectedTable = $this->createFlatXmlDataSet("./tests/persistence/product_db.xml")
                ->getTable("tb_product");
        $rows = $expectedTable->getRowCount();
        $this->assertGreaterThanOrEqual(0, $rows);
    }

    public function testAddNewProduct() {
        $values = [
            Product::NAME => "Test add product #1",
            Product::BARCODE => "0000000000",
            Product::PRICE => 120,
            Product::STOCK => 20,
            Product::DESCRIPTION => "New Product add from tests. ID must be 6.",
            Product::COD_STATUS => 1,
            Product::DATE => ValuesUtil::format_date(),
            Product::DATE_UPDATE => ValuesUtil::format_date(),
        ];
        $product = new Product;
        $product->from_values($values);
        $id = $this->controller->insert($product);
        $this->assertGreaterThan(0, $id);
    }

    public function testAddNewProductInvalidPrice() {
        $values = [
            Product::NAME => "Test add product #1",
            Product::BARCODE => "0000000000",
            Product::PRICE => 0,
            Product::STOCK => 20,
            Product::DESCRIPTION => "New Product with invalid price from tests. ID must be 7.",
            Product::COD_STATUS => 1,
            Product::DATE => ValuesUtil::format_date(),
            Product::DATE_UPDATE => ValuesUtil::format_date(),
        ];
        $product = new Product;
        $product->from_values($values);
        $id = $this->controller->insert($product);
        $this->assertLessThan(0, $id);
    }

    public function testGetProductById() {
        $id = 5;
        $product = $this->controller->get_product($id);
        $this->assertEquals($id, $product->id);
    }

    
    public function testUpdateProduct() {
        $id = 3;
        $product = $this->controller->get_product($id);
        $product->name = "Updated product #3";
        $product->description = "Description of product updated by test charge";
        $product->stock = 33;
        $product->price = 33;
        $product->barcode = "3333333333333";
        $id = $this->controller->update($product);
        $this->assertGreaterThan(0, $id);
    }

    public function testChangeStatusProduct() {
        $id = 5;
        $id = $this->controller->change_status($id);
        $this->assertGreaterThan(0, $id);
    }

    public function testeDeleteAllProducts() {
        $rows = $this->controller->delete(1);
        $this->assertGreaterThan(0, $rows);
    }
}
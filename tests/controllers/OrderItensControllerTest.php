<?php 

require_once "src/models/OrderItem.php";
require_once "src/controllers/OrderController.php";

use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\Framework\TestCase;

class OrderItensControllerTest extends TestCase {

    use TestCaseTrait;

    private $controller = null;
    private $conn = null;
    private static $pdo = null;

    public function getConnection() {

        $this->controller = is_null($this->controller) ? new OrderController : $this->controller;
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
        return $this->createFlatXmlDataSet('./tests/persistence/order_itens_db.xml');
    }

    public function testOrderItensCount() {
        $expectedTable = $this->createFlatXmlDataSet("./tests/persistence/order_itens_db.xml")
                ->getTable("tb_order_itens");
        $rows = $expectedTable->getRowCount();
        $this->assertEquals(6, $rows);
    }

    public function testAddItemAtOrderWithStock() {
        $currentDate = ValuesUtil::format_date();
        $orderItem = new OrderItem;
        $orderItem->from_values([
            OrderItem::ID_ORDER => 4,
            OrderItem::ID_PRODUCT => 5,
            OrderItem::QUANTITY => 5,
            OrderItem::DATE => $currentDate,
            OrderItem::DATE_UPDATE => $currentDate,
        ]);
        $id = $this->controller->insert_item($orderItem);
        $this->assertGreaterThan(0, $id);
    }

    public function testAddItemAtOrderWithoutStock() {
        $currentDate = ValuesUtil::format_date();
        $orderItem = new OrderItem;
        $orderItem->from_values([
            OrderItem::ID_ORDER => 4,
            OrderItem::ID_PRODUCT => 5,
            OrderItem::QUANTITY => 5,
            OrderItem::DATE => $currentDate,
            OrderItem::DATE_UPDATE => $currentDate,
        ]);
        $id = $this->controller->insert_item($orderItem);
        $this->assertLessThan(0, $id);
    }

    public function testDeleteOrderItem() {
        $id = 3;
        $result = $this->controller->delete_order_item($id);
        $this->assertEquals($id, $result);
    }
}
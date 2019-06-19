<?php 

require_once "src/models/Order.php";
require_once "src/controllers/OrderController.php";

use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\Framework\TestCase;

class OrderControllerTest extends TestCase {

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
        return $this->createFlatXmlDataSet('./tests/persistence/order_db.xml');
    }

    public function testOrdersCount() {
        $expectedTable = $this->createFlatXmlDataSet("./tests/persistence/order_db.xml")
                ->getTable("tb_order");
        $rows = $expectedTable->getRowCount();
        $this->assertEquals(5, $rows);
    }

    public function testAddNewOrders() {
        $orderBankSlip = new Order;
        $orderBankSlip->from_values([
            Order::VALUE => 2500,
            Order::DISCOUNT => 0,
            Order::NUM_PARCEL => 12,
            Order::VALUE_PARCEL => 0,
            Order::PAYMENT => "Bank Slip",
            Order::COD_PAYMENT => 2,
            Order::STATUS => "Open",
            Order::COD_STATUS => 2,
            Order::DATE => ValuesUtil::format_date(),
            Order::DATE_UPDATE => ValuesUtil::format_date(),
        ]);

        $orderCash = new Order;
        $orderCash->from_values([
            Order::VALUE => 3500,
            Order::DISCOUNT => 0,
            Order::NUM_PARCEL => 12,
            Order::VALUE_PARCEL => 0,
            Order::PAYMENT => "Cash",
            Order::COD_PAYMENT => 0,
            Order::STATUS => "Open",
            Order::COD_STATUS => 2,
            Order::DATE => ValuesUtil::format_date(),
            Order::DATE_UPDATE => ValuesUtil::format_date(),
        ]);

        
        $orderCredit = new Order;
        $orderCredit->from_values([
            Order::VALUE => 10000,
            Order::DISCOUNT => 0,
            Order::NUM_PARCEL => 12,
            Order::VALUE_PARCEL => 0,
            Order::PAYMENT => "Credit Card",
            Order::COD_PAYMENT => 1,
            Order::STATUS => "Open",
            Order::COD_STATUS => 2,
            Order::DATE => ValuesUtil::format_date(),
            Order::DATE_UPDATE => ValuesUtil::format_date(),
        ]);

        $idBank = $this->controller->insert($orderBankSlip);
        $idCash = $this->controller->insert($orderCash);
        $idCredit = $this->controller->insert($orderCredit);
        $this->assertGreaterThan(0, $idBank);
        $this->assertGreaterThan(0, $idCash);
        $this->assertGreaterThan(0, $idCredit);
    }

    public function testDeleteOrderPerId() {
        $id = 2;
        $idOrder = $this->controller->delete($id);
        $this->assertGreaterThan(0, $idOrder);
    }
}
<?php 

require_once "src/models/Order.php";
require_once "src/controllers/OrderController.php";

use PHPUnit\Framework\TestCase;

class OrderModelTest extends TestCase {

    private $order = null;
    private $controller = null;
    
    protected function setUp(): void {

        $id = 1;
        $this->controller = is_null($this->controller) ? new OrderController : $this->controller;
        $this->order = is_null($this->order) ? $this->controller->get_by_id($id) : $this->order;
    }

    public function testHasAttributes() {

        $this->assertObjectHasAttribute("id", $this->order);
        $this->assertObjectHasAttribute("discount", $this->order);
        $this->assertObjectHasAttribute("value", $this->order);
        $this->assertObjectHasAttribute("numParcel", $this->order);
        $this->assertObjectHasAttribute("valueParcel", $this->order);
        $this->assertObjectHasAttribute("payment", $this->order);
        $this->assertObjectHasAttribute("codPayment", $this->order);
        $this->assertObjectHasAttribute("status", $this->order);
        $this->assertObjectHasAttribute("codStatus", $this->order);
        $this->assertObjectHasAttribute("date", $this->order);
        $this->assertObjectHasAttribute("dateUpdate", $this->order);
    }

    public function testValueGreaterThanSuccess() {
        $this->assertGreaterThan(0, $this->order->value);
    }

    public function testDiscountPerPayment() {
        $payment = $this->order->codPayment;
        $value = $this->order->value;
        
        $discountExpected = $payment === PAY_CASH ? $value * 0.1
                : ($payment === PAY_BANK_SLIP ? $value * 0.05 : 0);
        $this->assertEquals($this->order->discount, $discountExpected);
    }

    public function testNumParcelsPerPayment() {
        $payment = $this->order->codPayment;
        $numParcel = $this->order->numParcel;
        $numParcelsExpected = $payment === PAY_CREDIT_CARD ? $numParcel : 1;
        $this->assertEquals($numParcel, $numParcelsExpected);
    }
}
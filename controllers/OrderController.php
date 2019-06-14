<?php
require_once "controllers/Controller.php";
require_once "models/Product.php";
require_once "persistence/OrderPersistence.php";
require_once "persistence/ProductPersistence.php";

class OrderController extends Controller {

    private $persistence, $productPersistence;

    function __construct() {
        parent::__construct("btn-details");
        $this->persistence = new OrderPersistence;
        $this->productPersistence = new ProductPersistence;
    }

    public function post(string $input): int {

        switch($input) {
            case "btn-details": return $this->details($input);
        }
        return OK;
    }

    public function products(): array {
        return $this->productPersistence->select_all_active();
    }
}

<?php
require_once "controllers/Controller.php";
require_once "models/Product.php";
require_once "persistence/OrderPersistence.php";
require_once "persistence/ProductPersistence.php";

class OrderController extends Controller {

    private $persistence, $productPersistence;

    function __construct() {
        parent::__construct("btn-details", "btn-add-cart", "btn-search", "btn-finish");
        
        $this->persistence = new OrderPersistence;
        $this->productPersistence = new ProductPersistence;
        
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    public function post(string $input): int {

        switch($input) {
            case "btn-details": return $this->details($input);
            case "btn-add-cart": return $this->add_cart($input);
            case "btn-finish": return $this->finish();
        }
        return OK;
    }

    public function products(): array {
        $searchable = isset($_POST['searchable']) ? $_POST['searchable'] : "";
        if (isset($_POST['btn-search']) && strlen($searchable) > 0) {
            return $this->productPersistence->select_by_search($searchable);
        }
        return $this->productPersistence->select_all_active();
    }

    private function add_cart(string $input): int {
        $id = intval($_POST[$input]);
        $cart = $_SESSION['cart'];
        if (!isset($cart[$id])) {
            $product = $this->productPersistence->select_by_id($id);
            $cart[$id] = [
                "product" => $product,
                "quantity" => 1,
            ];
            $_SESSION['cart'] = $cart;
        };
        return 0;
    }

    private function finish(): int {
        return 0;
    }
}

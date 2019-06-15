<?php
require_once "controllers/Controller.php";
require_once "models/Product.php";
require_once "models/Order.php";
require_once "models/OrderItem.php";
require_once "persistence/OrderPersistence.php";
require_once "persistence/ProductPersistence.php";

class OrderController extends Controller {

    private $persistence, $productPersistence;

    function __construct() {
        parent::__construct("btn-remove", "btn-quantity", "btn-add-cart", "btn-search", 
                "btn-confirm", "btn-back", "btn-finish");
        
        $this->persistence = new OrderPersistence;
        $this->productPersistence = new ProductPersistence;
        
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    public function post(string $input): int {

        switch($input) {
            case "btn-remove": return $this->remove($input);
            case "btn-quantity": return $this->quantity($input);
            case "btn-add-cart": return $this->add_cart($input);
            case "btn-back": return $this->back();
            case "btn-confirm": return $this->confirm();
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

    public function order_itens(): stdClass {
        $itens = new stdClass;
        $itens->products = $_SESSION['cart'];
        $itens->titles = [
            [ "width" => 5, "text" => "Code" ],
            [ "width" => 40, "text" => "Name" ],
            [ "width" => 10, "text" => "Price" ],
            [ "width" => 20, "text" => "Barcode" ],
            [ "width" => 15, "text" => "Quantity" ],
            [ "width" => 10, "text" => "Remove" ],
        ];
        return $itens;
    }

    private function quantity(string $input): int {
        $id = intval($_POST[$input]);
        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        if (array_key_exists($id, $cart)) {
            $cart[$id]['quantity'] = intval($_POST['quantitys'][$id]);
            $_SESSION['cart'] = $cart;
        }
        return 0;
    }

    private function remove(string $input): int {
        $id = intval($_POST[$input]);
        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        if (array_key_exists($id, $cart)) {
            unset($cart[$id]);
            $_SESSION['cart'] = $cart;
        }
        return 0;
    }

    private function add_cart(string $input): int {
        $id = intval($_POST[$input]);
        $cart = $_SESSION['cart'];
        if (!isset($cart[$id])) {
            $product = $this->productPersistence->select_by_id($id);
            $cart[$id] = [
                "item" => $product,
                "quantity" => 1,
            ];
            $_SESSION['cart'] = $cart;
        };
        return 0;
    }

    private function finish() {
        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        if (sizeof($cart) === 0) {
            return -1;
        }
        $payment = intval($_POST['payment']);
        $parcels = $payment === PAY_CREDIT_CARD && isset($_POST['parcels']) && intval($_POST['parcels']) > 0 
                ? intval($_POST['parcels']) 
                : 1;
        $orderValue = array_reduce($cart, function($acc, $c){
            $product = $c['item'];
            $quantity = intval($c['quantity']);
            $acc += $product->price * $quantity;
            return $acc;
        }, 0);

        $discount = $payment === PAY_BANK_SLIP ? 0.05 
                : ($payment === PAY_CASH ? 0.1 : 0);
                
        $order = new Order;
        $order->value = $orderValue;
        $order->discount = $orderValue * $discount;
        $order->numParcel = $parcels;
        $order->valueParcel = $orderValue / $parcels;
        $order->codPayment = $payment;
        $order->codStatus = ORD_OPEN;
        $order->date = ValuesUtil::format_date();
        $order->dateUpdate = ValuesUtil::format_date();
        
        $order->id = $this->persistence->insert($order);
        $this->insert_itens($order->id, $cart);
        
        return $order->id;
    }

    private function insert_itens(int $idOrder, array $cart): int {
        $itens = [];
        $products = [];
        $currentDate = ValuesUtil::format_date();
        foreach ($cart as $c) {
            $item = $c['item'];

            $order = new OrderItem;
            $order->from_values([
                OrderItem::ID_PRODUCT => $item->id,
                OrderItem::ID_ORDER => $idOrder,
                OrderItem::QUANTITY => $c['quantity'],
                OrderItem::DATE => $currentDate,
                OrderItem::DATE_UPDATE => $currentDate,
            ]);
            $product = $item;
            $product->dec_quantity($item->quantity);
            $products[] = $product;
            $itens[] = $order;
        }
        $result = $this->persistence->insert_itens($itens);
        $this->update_stock($products);
        unset($_SESSION['cart']);
        return $result;
    }

    private function update_stock(array $products) {
        foreach ($products as $product) {
            $this->productPersistence->update($product);
        }
    }

    private function back(): int {
        return $this->go_to(Controller::base_url("pedido/novo"));
    }

    private function confirm(): int {
        return $this->go_to(Controller::base_url("pedido/finalizar"));
    }

    private function go_to(string $url): int {
        header("Location: $url");
        return 0;
    }
}

<?php
require_once "src/controllers/Controller.php";
require_once "src/models/Product.php";
require_once "src/models/Order.php";
require_once "src/models/OrderItem.php";
require_once "src/persistence/OrderPersistence.php";
require_once "src/persistence/ProductPersistence.php";

class OrderController extends Controller {

    private $persistence, $productPersistence;

    function __construct() {
        parent::__construct("btn-remove", "btn-quantity", "btn-add-cart", "btn-search", 
                "btn-confirm", "btn-back", "btn-finish", "btn-new", "btn-products",
                "btn-details"
            );
        
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
            case "btn-details": return $this->details($input);
            case "btn-back": return $this->back();
            case "btn-confirm": return $this->confirm();
            case "btn-finish": return $this->finish();
            case "btn-new": return $this->new_order();
            case "btn-products": return $this->products_list();
        }
        return OK;
    }

    public function table_data(): stdClass {

        $data = new stdClass;
        $data->titles = [
            [ "width" => 5, "text" => "Code" ],
            [ "width" => 20, "text" => "Date" ],
            [ "width" => 20, "text" => "Price" ],
            [ "width" => 20, "text" => "Discount" ],
            [ "width" => 20, "text" => "Payment" ],
            [ "width" => 10, "text" => "Status" ],
            [ "width" => 5, "text" => "Details" ],
        ];
        $data->orders = $this->persistence->select_all();

        return $data;
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
        $itens->products = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
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

    public function order_details(): stdClass {
        $idOrder = $_GET['value'];
        $details = new stdClass;
        $itensP = $this->persistence->select_itens_by_order($idOrder);
        $itens = [];
        foreach ($itensP as $item) {
            $itens[$item->idProduct] = $item;
        }

        $details->order = $this->persistence->select_by_id($idOrder);
        $details->itens = $itens;
        $details->products = $this->productPersistence->select_by_order($idOrder);
        $details->titleItens = [
            [ "width" => 5, "text" => "Code" ],
            [ "width" => 45, "text" => "Name" ],
            [ "width" => 10, "text" => "Price" ],
            [ "width" => 20, "text" => "Barcode" ],
            [ "width" => 10, "text" => "Quantity" ],
        ];
        return $details;
    }

    public function get_by_id(int $id): Order {
        return $this->persistence->select_by_id($id);
    }

    public function get_item_by_id_product(int $id): OrderItem {
        return $this->persistence->select_item_by_id_product($id);
    }

    public function insert(Order $order): int {
        
        $discount = $order->codPayment === PAY_BANK_SLIP ? 0.05 
                : ($order->codPayment === PAY_CASH ? 0.1 : 0);
        $order->discount = $order->value * $discount;
        $order->numParcel = $order->numParcel <= 0 || $order->codPayment !== PAY_CREDIT_CARD ? 1 
                : $order->numParcel;
        $order->valueParcel = $order->codPayment === PAY_CASH || $order->codPayment === PAY_BANK_SLIP
                ? $order->value - $order->discount 
                : $order->value / $order->numParcel;

        if ($order->invalid_values()) {
            return ERROR;
        }
        
        $order->id = $this->persistence->insert($order);
        return $order->id <= 0 ? ERR_ORDER_SAVE : $order->id;
    }

    public function insert_itens(int $orderId, array $itens): int {
        $result = $this->persistence->insert_itens($itens);
        return $result <= 0 ? ERR_ORDER_ITEM_SAVE : $result;
    }

    public function update_products(array $products): int {
        $count = 0;
        foreach ($products as $product) {
            $count += $this->productPersistence->update($product);
        }
        return $count;
    }

    public function delete(int $id): int {
        $idDeleted = $this->persistence->delete($id);
        return $idDeleted <= 0 ? ERR_ORDER_DELETE : $idDeleted;
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

    private function details(string $input): int {
        $id = $_POST[$input];
        return $this->go_to("pedido/detalhes/$id");
    }

    private function finish() {
        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        if (sizeof($cart) === 0) {
            return -1;
        }
        $payment = intval($_POST['payment']);
        $parcels = isset($_POST['parcels']) ? intval($_POST['parcels']) : 1;
        $orderValue = array_reduce($cart, function($acc, $c){
            $product = $c['item'];
            $quantity = intval($c['quantity']);
            $acc += $product->price * $quantity;
            return $acc;
        }, 0);

        $order = new Order;
        $order->value = $orderValue;
        $order->discount = 0;
        $order->numParcel = $parcels;
        $order->valueParcel = 0;
        $order->codPayment = $payment;
        $order->payment = $this->persistence->select_payment_by_code($order->codPayment);
        $order->codStatus = ORD_OPEN;
        $order->status = $this->persistence->select_status_by_code($order->codStatus);
        $order->date = ValuesUtil::format_date();
        $order->dateUpdate = ValuesUtil::format_date();
        
        $idOrder = $this->insert($order);
        if ($idOrder <= 0) {
            return ERR_ORDER_SAVE;
        }

        $itens = [];
        $products = [];
        $currentDate = ValuesUtil::format_date();
        foreach ($cart as $c) {
            $item = $c['item'];

            $orderItem = new OrderItem;
            $orderItem->from_values([
                OrderItem::ID_PRODUCT => $item->id,
                OrderItem::ID_ORDER => $idOrder,
                OrderItem::QUANTITY => $c['quantity'],
                OrderItem::DATE => $currentDate,
                OrderItem::DATE_UPDATE => $currentDate,
            ]);
            $product = $item;
            $product->dec_quantity($orderItem->quantity);
            $products[] = $product;
            $itens[] = $orderItem;
        }

        $result = $this->insert_itens($idOrder, $itens);
        if ($result <= 0) {
            return ERR_ORDER_ITEM_SAVE;
        }

        $result = $this->update_products($products);
        if ($result <= 0) {
            return ERR_PRODUCT_EDIT;
        }

        unset($_SESSION['cart']);
        return $result;
    }

    private function new_order(): int {
        return $this->go_to(Controller::base_url("pedido/novo"));
    }

    private function products_list(): int {
        return $this->go_to(Controller::base_url("produto"));
    }

    private function back(): int {
        $action = isset($_GET['action']) ? $_GET['action'] : "pedido";
        $url = $action === "finalizar" ? "pedido/novo" : "pedido";
        return $this->go_to(Controller::base_url($url));
    }

    private function confirm(): int {
        return $this->go_to(Controller::base_url("pedido/finalizar"));
    }

    private function go_to(string $url): int {
        header("Location: $url");
        return 0;
    }
}

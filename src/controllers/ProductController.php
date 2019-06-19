<?php 
require_once "src/controllers/Controller.php";
require_once "src/models/Product.php";
require_once "src/persistence/ProductPersistence.php";

class ProductController extends Controller {

    private $persistence;

    function __construct() {
        parent::__construct("btn-save", "btn-edit", "btn-details", "btn-inactivate", 
                "btn-back", "btn-new", "btn-orders");
        $this->persistence = new ProductPersistence;
    }

    public function post(string $input): int {

        switch($input) {
            case "btn-save": return $this->save();
            case "btn-edit": return $this->edit($input);
            case "btn-details": return $this->details($input);
            case "btn-inactivate": return $this->inactivate($input);
            case "btn-new": return $this->new();
            case "btn-back": return $this->back();
            case "btn-orders": return $this->orders();
        }
        return OK;
    }

    public function table_data(): stdclass {
        $values = new stdClass;
        $values->products = $this->search_products();
        $values->titles = [
            [ "width" => 5, "text" => "Code" ],
            [ "width" => 40, "text" => "Name" ],
            [ "width" => 10, "text" => "Price" ],
            [ "width" => 15, "text" => "Barcode" ],
            [ "width" => 10, "text" => "Status" ],
            [ "width" => 10, "text" => "Inactivate" ],
            [ "width" => 10, "text" => "Details" ],
        ];
        return $values;
    }

    public function get_product(int $id): Product {
        return $this->persistence->select_by_id($id);
    }

    public function searchable_value(): string {
        return isset($_POST['searchable']) ? $_POST['searchable'] : "";
    }

    public function insert(Product $product): int {
        if ($product->invalid_values()) {
            return ERROR;
        }
        $id = $this->persistence->insert($product);
        return $id > 0 ? $id : ERR_PRODUCT_SAVE;
    }

    public function update(Product $product): int {
        if ($product->invalid_values()) {
            return ERROR;
        }
        $id = $this->persistence->update($product);
        return intval($id) < 0 ? ERR_PRODUCT_EDIT : $id;
    }
    
    public function delete(int $id): int {
        return $this->persistence->delete($id);
    }

    public function delete_all(): int {
        return $this->persistence->delete_all();
    }

    public function change_status(int $id): int {
        $product = $this->get_product($id);
        $product->change_status();
        return $this->update($product);
    }

    private function search_products(): array {
        $searchable = $this->searchable_value();
        if (isset($_POST['btn-search']) && strlen($searchable) > 0) {
            return $this->persistence->select_by_search($searchable);
        }
        return $this->persistence->select_all();
    }

    private function save(): int {

        $values = $_POST;
        $values['date'] = ValuesUtil::format_date();
        $values['date_update'] = ValuesUtil::format_date();
        $values['cod_status'] = PRO_ACTIVE;

        $product = new Product;
        $product->from_values($values);
        return $this->insert($product);
    }

    private function edit(string $input): int {
        $id = $_POST[$input];
        $product = $this->get_product($id);

        $values = $_POST;
        $values['id'] = $product->id;
        $values['date'] = $product->date;
        $values['date_update'] = ValuesUtil::format_date();
        $values['cod_status'] = $product->codStatus;
        $product->from_values($values);
        return $this->update($product);
    }

    private function inactivate(string $input): int {
        $id = intval($_POST[$input]);
        return $this->change_status($id);
    }

    private function details(string $input): int {
        $id = intval($_POST[$input]);
        $url = $id > 0 ? "produto/detalhes/$id" : "produto";
        return $this->go_to(Controller::base_url($url));
    }

    private function new(): int {
        return $this->go_to(Controller::base_url("produto/novo"));
    }

    private function back(): int {
        return $this->go_to(Controller::base_url("produto"));
    }

    private function orders(): int {
        return $this->go_to(Controller::base_url("pedido"));
    }

    private function go_to(string $url): int {
        header("Location: $url");
        return 0;
    }
}
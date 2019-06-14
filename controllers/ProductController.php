<?php 
require_once "controllers/Controller.php";
require_once "models/Product.php";
require_once "persistence/ProductPersistence.php";

class ProductController extends Controller {

    private $persistence;

    function __construct() {
        parent::__construct("btn-save", "btn-edit", "btn-details", "btn-inactivate", "btn-back", "btn-new");
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
        }
        return OK;
    }

    public function table_data(): stdclass {
        $values = new stdClass;
        $values->products = $this->persistence->select_all();
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

    private function save(): int {

        $values = $_POST;
        $values['date'] = ValuesUtil::format_date();
        $values['date_update'] = ValuesUtil::format_date();
        $values['cod_status'] = PRO_ACTIVE;

        $product = new Product;
        $product->from_values($values);

        if ($product->invalid_values()) {
            return ERR_PRODUCT_SAVE;
        }
        $id = $this->persistence->insert($product);
        return $id > 0 ? $id : ERR_PRODUCT_SAVE;
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
        return $this->persistence->update($product);
    }

    private function inactivate(string $input): int {
        $id = intval($_POST[$input]);
        $product = $this->get_product($id);
        $product->codStatus = $product->codStatus === PRO_INACTIVE ? PRO_ACTIVE : PRO_INACTIVE;
        $product->dateUpdate = ValuesUtil::format_date();
        return $this->persistence->update($product);
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

    private function go_to(string $url): int {
        header("Location: $url");
        return 0;
    }
}
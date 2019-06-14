<?php 
require_once "controllers/Controller.php";
require_once "models/Product.php";
require_once "persistence/ProductPersistence.php";

class ProductController extends Controller {

    private $persistence;

    function __construct() {
        parent::__construct("btn-save");
        $this->persistence = new ProductPersistence;
    }

    public function post(string $input): int {

        switch($input) {
            case "btn-save": return $this->save();
        }
        return OK;
    }

    public function table_data(): stdclass {
        $values = new stdClass;
        $values->products = $this->persistence->select_all();
        $values->titles = [
            [ "width" => 5, "text" => "Code" ],
            [ "width" => 55, "text" => Product::NAME ],
            [ "width" => 10, "text" => Product::PRICE ],
            [ "width" => 20, "text" => Product::BARCODE ],
            [ "width" => 10, "text" => "Inactivate" ],
            [ "width" => 10, "text" => "Details" ],
        ];
        return $values;
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
}
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
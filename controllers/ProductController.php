<?php 
require_once "controllers/Controller.php";
require_once "models/Product.php";

class ProductController extends Controller {

    function __construct() {
        parent::__construct("btn-save");
    }

    public function post(string $input): int {

        switch($input) {
            case "btn-save": return $this->save();
        }
        return OK;
    }

    private function save(): int {

        $product = new Product;
        $product->from_values($_POST);

        if ($product->invalid_values()) {
            return ERR_PRODUCT_SAVE;
        }
        return PRODUCT_SAVE;
    }
}
<?php

require_once "interfaces/IModel.php";
require_once "utils/ValuesUtil.php";

class Product implements IModel {

    private $id;
    private $name, $description, $barcode, $price;

    function __construct() {
        $this->id = -1;
    }

    public function invalid_values(): bool {
        return ValuesUtil::is_null_or_empty($this->name)
                || ValuesUtil::is_null_or_empty($this->description)
                || ValuesUtil::is_null_or_empty($this->barcode)
                || ValuesUtil::is_null_or_empty($this->price);
    }

    public function as_json(): string {
        $values = [
            "id" => $this->id,
            "name" => $this->name,
            "description" => $this->description,
            "barcode" => $this->barcode,
            "price" => $this->price,
        ];
        return json_encode($values);
    }

    public function from_values(array $values) {

        $this->name = ValuesUtil::value_or_default($values, "name");
        $this->barcode = ValuesUtil::value_or_default($values, "barcode");
        $this->price = floatval(ValuesUtil::value_or_default($values, "price", -1));
        $this->description = ValuesUtil::value_or_default($values, "description");
    }
}
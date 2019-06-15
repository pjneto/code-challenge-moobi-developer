<?php

require_once "utils/ValuesUtil.php";

class Product {

    const TABLE = "tb_product";
    const ID = "id";
    const NAME = "name";
    const PRICE = "price";
    const DESCRIPTION = "description";
    const COD_STATUS = "cod_status";
    const BARCODE = "barcode";
    const DATE = "date";
    const DATE_UPDATE = "date_update";

    public $id, $codStatus;
    public $name, $description, $barcode; 
    public $price;
    public $date, $dateUpdate; 

    function __construct() {
        $this->id = -1;
    }

    public function invalid_values(): bool {
        return ValuesUtil::is_null_or_empty($this->name)
                || ValuesUtil::is_null_or_empty($this->description)
                || ValuesUtil::is_null_or_empty($this->barcode)
                || ValuesUtil::is_null_or_empty($this->price);
    }

    public function db_values(bool $widthId = false): array {
        $values = [
            "f" . Product::NAME => $product->name,
            "f" . Product::DESCRIPTION => $product->description,
            "f" . Product::BARCODE => $product->barcode,
            "f" . Product::PRICE => $product->price,
            "f" . Product::COD_STATUS => $product->codStatus,
            "f" . Product::DATE => $product->date,
            "f" . Product::DATE_UPDATE => $product->dateUpdate,
        ];
        if ($widthId) {
            $values[":f" . self::ID ] = $this->id;
        }
        return $values;
    }

    public function from_values(array $values) {
        
        $this->id = intval(ValuesUtil::value_or_default($values, self::ID, -1));
        $this->name = ValuesUtil::value_or_default($values, self::NAME, null);
        $this->barcode = ValuesUtil::value_or_default($values, self::BARCODE, null);
        $this->price = floatval(ValuesUtil::value_or_default($values, self::PRICE, -1));
        $this->description = ValuesUtil::value_or_default($values, self::DESCRIPTION, null);
        $this->codStatus = intval(ValuesUtil::value_or_default($values, self::COD_STATUS, -1));
        $this->date = ValuesUtil::value_or_default($values, self::DATE, null);
        $this->dateUpdate = ValuesUtil::value_or_default($values, self::DATE_UPDATE, null);
    }
}
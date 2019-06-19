<?php

require_once "src/utils/ValuesUtil.php";

class Product {

    const TABLE = "tb_product";
    const ID = "id";
    const NAME = "name";
    const PRICE = "price";
    const STOCK = "stock";
    const DESCRIPTION = "description";
    const COD_STATUS = "cod_status";
    const BARCODE = "barcode";
    const DATE = "date";
    const DATE_UPDATE = "date_update";

    public $id, $codStatus, $stock;
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

    public function inc_quantity(int $quantity) {
        $this->stock += $quantity;
    }

    public function dec_quantity(int $quantity) {
        $this->stock -= $quantity;
        if ($this->stock <= 0) {
            $this->stock = 0;
        }
    }

    public function db_values(bool $widthId = false): array {
        $values = [
            ":f" . Product::NAME => $this->name,
            ":f" . Product::DESCRIPTION => $this->description,
            ":f" . Product::BARCODE => $this->barcode,
            ":f" . Product::PRICE => $this->price,
            ":f" . Product::STOCK => $this->stock,
            ":f" . Product::COD_STATUS => $this->codStatus,
            ":f" . Product::DATE => $this->date,
            ":f" . Product::DATE_UPDATE => $this->dateUpdate,
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
        $this->stock = intval(ValuesUtil::value_or_default($values, self::STOCK, 0));
        $this->description = ValuesUtil::value_or_default($values, self::DESCRIPTION, null);
        $this->codStatus = intval(ValuesUtil::value_or_default($values, self::COD_STATUS, -1));
        $this->date = ValuesUtil::value_or_default($values, self::DATE, null);
        $this->dateUpdate = ValuesUtil::value_or_default($values, self::DATE_UPDATE, null);
    }
}
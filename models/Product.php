<?php

require_once "interfaces/IModel.php";
require_once "utils/ValuesUtil.php";

class Product implements IModel {

    const TABLE = "tb_product";
    const ID = "id";
    const NAME = "name";
    const PRICE = "price";
    const DESCRIPTION = "description";
    const COD_STATUS = "cod_status";
    const BARCODE = "barcode";
    const DATE = "date";
    const DATE_UPDATE = "date_update";

    private $id;
    private $name; 
    private $description;
    private $barcode; 
    private $price; 
    private $codStatus;
    private $date; 
    private $dateUpdate; 

    function __construct() {
        $this->id = -1;
    }

    public function invalid_values(): bool {
        return ValuesUtil::is_null_or_empty($this->name)
                || ValuesUtil::is_null_or_empty($this->description)
                || ValuesUtil::is_null_or_empty($this->barcode)
                || ValuesUtil::is_null_or_empty($this->price);
    }

    public function as_values(): array {
        return [
            self::ID => $this->id,
            self::NAME => $this->name,
            self::PRICE => $this->price,
            self::DESCRIPTION => $this->description,
            self::BARCODE => $this->barcode,
            self::COD_STATUS => $this->codStatus,
            self::DATE => $this->date,
            self::DATE_UPDATE => $this->dateUpdate
        ];
    }

    public function as_json(): string {
        $values = $this->as_values();
        return json_encode($values);
    }

    public function from_values(array $values) {

        $this->name = ValuesUtil::value_or_default($values, self::NAME, null);
        $this->barcode = ValuesUtil::value_or_default($values, self::BARCODE, null);
        $this->price = floatval(ValuesUtil::value_or_default($values, self::PRICE, -1));
        $this->description = ValuesUtil::value_or_default($values, self::DESCRIPTION, null);
        $this->codStatus = ValuesUtil::value_or_default($values, self::COD_STATUS, -1);
        $this->date = ValuesUtil::value_or_default($values, self::DATE, null);
        $this->dateUpdate = ValuesUtil::value_or_default($values, self::DATE_UPDATE, null);
    }
}
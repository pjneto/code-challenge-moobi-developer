<?php 

require_once "utils/ValuesUtil.php";

class OrderItem {

    const TABLE = "tb_order_itens";
    const ID = "id";
    const ID_PRODUCT = "id_product";
    const ID_ORDER = "id_order";
    const QUANTITY = "quantity";
    const DATE = "date";
    const DATE_UPDATE = "date_update";

    public $id, $idProduct, $idOrder, $quantity;
    public $date, $dateUpdate;

    function __construct() {
        $this->id = -1;
    }

    public function db_values(bool $withId = false) {
        $values = [
            ":f" . self::ID_ORDER => $this->idOrder,
            ":f" . self::ID_PRODUCT => $this->idProduct,
            ":f" . self::QUANTITY => $this->quantity,
            ":f" . self::DATE => $this->date,
            ":f" . self::DATE_UPDATE => $this->dateUpdate,
        ];
        if ($withId) {
            $values[":f" . self::ID] = $this->id;
        }
        return $values;
    }

    public function from_values(array $values) {
        $this->id = intval(ValuesUtil::value_or_default($values, self::ID, -1));
        $this->idOrder = intval(ValuesUtil::value_or_default($values, self::ID_ORDER, -1));
        $this->idProduct = intval(ValuesUtil::value_or_default($values, self::ID_PRODUCT, -1));
        $this->quantity = intval(ValuesUtil::value_or_default($values, self::QUANTITY, 0));
        $this->date = ValuesUtil::value_or_default($values, self::DATE, null);
        $this->dateUpdate = ValuesUtil::value_or_default($values, self::DATE_UPDATE, null);
    }
}
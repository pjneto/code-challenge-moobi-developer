<?php

class Order {

    const TABLE = "tb_order";
    const ID = "id";
    const DISCOUNT = "discount";
    const VALUE = "value";
    const NUM_PARCEL = "num_parcel";
    const VALUE_PARCEL = "value_parcel";
    const COD_PAYMENT = "cod_payment";
    const COD_STATUS = "cod_status";
    const DATE = "date";
    const DATE_UPDATE = "date_update";

    public $id, $numParcel, $codStatus, $codPayment;
    public $value, $valueParcel, $discount;
    public $date, $dateUpdate;

    function __construct() {
        $this->id = -1;
    }

    public function db_values(bool $withId = false): array {
        $values = [
            ":f" . Order::VALUE => $order->value,
            ":f" . Order::DISCOUNT => $order->discount,
            ":f" . Order::NUM_PARCEL => $order->numParcel,
            ":f" . Order::VALUE_PARCEL => $order->valueParcel,
            ":f" . Order::COD_STATUS => $order->codStatus,
            ":f" . Order::COD_PAYMENT => $order->codPayment,
            ":f" . Order::DATE => $order->date,
            ":f" . Order::DATE_UPDATE => $order->dateUpdate,
        ];
        if ($withId) {
            $values[":f" . self::ID] = $this->id;
        }
        return $values;
    }
    
    public function from_values(array $values) {
        $this->id = intval(ValuesUtil::value_or_default($values, self::ID, -1));
        $this->value = floatval(ValuesUtil::value_or_default($values, self::VALUE, -1));
        $this->discount = floatval(ValuesUtil::value_or_default($values, self::DISCOUNT, -1));
        $this->numParcel = intval(ValuesUtil::value_or_default($values, self::NUM_PARCEL, -1));
        $this->valueParcel = floatval(ValuesUtil::value_or_default($values, self::VALUE_PARCEL, -1));
        $this->codPayment = intval(ValuesUtil::value_or_default($values, self::COD_PAYMENT, -1));
        $this->codStatus = intval(ValuesUtil::value_or_default($values, self::COD_STATUS, -1));
        $this->date = ValuesUtil::value_or_default($values, self::DATE, null);
        $this->dateUpdate = ValuesUtil::value_or_default($values, self::DATE_UPDATE, null);
    }
}
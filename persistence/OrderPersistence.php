<?php

require_once "models/Order.php";

class OrderPersistence {

    const INSERT = "INSERT INTO tb_order "
            . "(value, discount, num_parcel, value_parcel, cod_status, cod_payment, date, date_update) "
            . "VALUES (:fvalue, :fdiscount, :fnum_parcel, :fvalue_parcel, :fcod_status, :fcod_payment, :fdate, :fdate_update); ";

    public function insert(Order $order): int {
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
        var_dump($values);
        echo "<br><br>";
        var_dump($order);
        echo "<br><br>";
        $query = self::INSERT;
        $db = new DBConnection;
        return $db->insert($query, $values);   
    }
}
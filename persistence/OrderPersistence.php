<?php

require_once "models/Order.php";
require_once "models/OrderItem.php";

class OrderPersistence {

    const REPLACE_VALUES = "replaceValues";

    const INSERT = "INSERT INTO tb_order "
            . "(value, discount, num_parcel, value_parcel, cod_status, cod_payment, date, date_update) "
            . "VALUES (:fvalue, :fdiscount, :fnum_parcel, :fvalue_parcel, :fcod_status, :fcod_payment, :fdate, :fdate_update); ";
    
    const INSERT_ALL_ITENS = "INSERT INTO tb_order_itens (id_order, id_product, quantity, date, date_update) "
            . self::REPLACE_VALUES;

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
        $query = self::INSERT;
        $db = new DBConnection;
        return $db->insert($query, $values);   
    }

    public function insert_itens(array $itens): int {

        if (sizeof($itens) === 0) {
            return 0;
        }

        $args = "VALUES ";
        $values = [];
        foreach ($itens as $item) {
            $args .= "(?, ?, ?, ?, ?), ";
            $values[] = $item->idProduct;
            $values[] = $item->idOrder;
            $values[] = $item->quantity;
            $values[] = $item->date;
            $values[] = $item->dateUpdate;
        }
        
        $query = str_replace(self::REPLACE_VALUES, $args, self::INSERT_ALL_ITENS);
        $query = substr($query, 0, strlen($query) - 2) . ";";
        
        $db = new DBConnection;
        return $db->insert($query, $values);   
    }
}
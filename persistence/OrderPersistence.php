<?php

require_once "models/Order.php";
require_once "models/OrderItem.php";

class OrderPersistence {

    const REPLACE_VALUES = "replaceValues";
    const REPLACE_WHERE = "WHERE FALSE ";

    const SELECT = "SELECT O.id, O.value, O.discount, O.num_parcel, O.value_parcel, O.date, O.date_update, " 
            . "OS.code as cod_status, OS.description as status, PS.code as cod_payment, PS.description as payment "
            . "FROM tb_order O "
            . "JOIN tb_order_status OS on OS.code = O.cod_status "
            . "JOIN tb_payment_status PS on PS.code = O.cod_payment "
            . self::REPLACE_WHERE;

    const INSERT = "INSERT INTO tb_order "
            . "(value, discount, num_parcel, value_parcel, cod_status, cod_payment, date, date_update) "
            . "VALUES (:fvalue, :fdiscount, :fnum_parcel, :fvalue_parcel, :fcod_status, :fcod_payment, :fdate, :fdate_update); ";
    
    const INSERT_ALL_ITENS = "INSERT INTO tb_order_itens (id_order, id_product, quantity, date, date_update) "
            . self::REPLACE_VALUES;

    public function select_all(): array {
        $where = "";
        $query = str_replace(self::REPLACE_WHERE, $where, self::SELECT);
        
        $db = new DBConnection;
        $values = $db->select($query);
        
        return array_map(function($v){
            $order = new Order;
            $order->from_values($v);
            return $order;
        }, $values);
    }

    public function insert(Order $order): int {
        $query = self::INSERT;
        $values = $order->db_values();
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
            $values[] = $item->idOrder;
            $values[] = $item->idProduct;
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
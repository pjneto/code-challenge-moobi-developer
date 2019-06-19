<?php

require_once "src/models/Order.php";
require_once "src/models/OrderItem.php";

class OrderPersistence {

    const REPLACE_VALUES = "replaceValues";
    const REPLACE_WHERE = "WHERE FALSE ";

    const SELECT_PAYMENT_STATUS = "SELECT PS.id, PS.code, PS.description FROM tb_payment_status PS "
            . self::REPLACE_WHERE;
            
    const SELECT_ORDER_STATUS = "SELECT OS.id, OS.code, OS.description FROM tb_order_status OS "
            . self::REPLACE_WHERE;

    const SELECT = "SELECT O.id, O.value, O.discount, O.num_parcel, O.value_parcel, O.date, O.date_update, " 
            . "OS.code as cod_status, OS.description as status, PS.code as cod_payment, PS.description as payment "
            . "FROM tb_order O "
            . "JOIN tb_order_status OS on OS.code = O.cod_status "
            . "JOIN tb_payment_status PS on PS.code = O.cod_payment "
            . self::REPLACE_WHERE;

    const SELECT_ITENS = "SELECT OI.id, OI.id_order, OI.id_product, OI.quantity, OI.date, OI.date_update "
            . "FROM tb_order_itens OI "
            . self::REPLACE_WHERE;

    const INSERT = "INSERT INTO tb_order (value, discount, num_parcel, value_parcel, status, cod_status, "
            . "payment, cod_payment, date, date_update) "
            . "VALUES (:fvalue, :fdiscount, :fnum_parcel, :fvalue_parcel, :fstatus, :fcod_status, "
            . ":fpayment, :fcod_payment, :fdate, :fdate_update); ";
    
    const INSERT_ALL_ITENS = "INSERT INTO tb_order_itens (id_order, id_product, quantity, date, date_update) "
            . self::REPLACE_VALUES;

    public function select_all(): array {
        return $this->select_order("");
    }

    public function select_by_id(int $id): Order {
        $where = "WHERE O.id = :fid";
        $args = [ ":fid" => $id ];
        $orders = $this->select_order($where, $args);
        return sizeof($orders) > 0 ? array_pop($orders) : new Order;
    }

    public function select_item_by_id_product(int $product): OrderItem {
        $where = "WHERE OI.id_product = :fid_product ";
        $args = [ ":fid_product" => $product ];
        $orders = $this->select_order_itens($where, $args);
        return sizeof($orders) > 0 ? array_pop($orders) : new OrderItem;
    }

    public function select_itens_by_order(int $idOrder): array {
        $where = "WHERE OI.id_order = :fid_order ";
        $args = [ ":fid_order" => $idOrder ];
        return $this->select_order_itens($where, $args);
    }

    public function select_payment_by_code(int $code): string {
        $where = "WHERE PS.code = :fcode ";
        $query = str_replace(self::REPLACE_WHERE, $where, self::SELECT_PAYMENT_STATUS);
        $db = new DBConnection();
        $values = $db->select($query, [ ":fcode" => $code ]);
        $payment = sizeof($values) > 0 ? array_pop($values) : [];
        return isset($payment['description']) ? $payment['description'] : null;
    }

    public function select_status_by_code(int $code): string {
        $where = "WHERE OS.code = :fcode ";
        $query = str_replace(self::REPLACE_WHERE, $where, self::SELECT_ORDER_STATUS);
        $db = new DBConnection();
        $values = $db->select($query, [ ":fcode" => $code ]);
        $status = sizeof($values) > 0 ? array_pop($values) : [];
        return isset($status['description']) ? $status['description'] : null;
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

    private function select_order(string $where = null, array $args = []): array {
        $values = $this->execute_select(self::SELECT, $where, $args);
        return array_map(function($v){
            $order = new Order;
            $order->from_values($v);
            return $order;
        }, $values);
    }

    private function select_order_itens(string $where = null, array $args = []): array {
        $values = $this->execute_select(self::SELECT_ITENS, $where, $args);
        return array_map(function($v){
            $orderItem = new OrderItem;
            $orderItem->from_values($v);
            return $orderItem;
        }, $values);
    }

    private function execute_select(string $select, string $where = null, array $args = []): array {
        $query = is_null($where) ? $select : str_replace(self::REPLACE_WHERE, $where, $select);
        $db = new DBConnection;
        return $db->select($query, $args);
    }
}
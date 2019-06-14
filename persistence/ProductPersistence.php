<?php

require_once "persistence/DBConnection.php";
require_once "models/Product.php";

class ProductPersistence {

    const REPLACE_WHERE = "WHERE P.id > 0 ";
    const REPLACE_ORDER_BY = "ORDER BY P.id ";

    const SELECT = "SELECT P.id, P.name, P.price, P.description, P.barcode, P.cod_status, P.date, P.date_update "
                . "FROM tb_product P "
                . self::REPLACE_WHERE
                . self::REPLACE_ORDER_BY;

    public function select_all(): array {
        $args = [ 
            ":" . Product::COD_STATUS => PRO_ACTIVE 
        ];
        $where = "WHERE P.cod_status = :cod_status ";
        $query = str_replace(self::REPLACE_WHERE, $where, self::SELECT);
        
        $db = new DBConnection;
        $values = $db->select($query, $args);

        $products = array_map(function($v) {
            $product = new Product;
            $product->from_values($v);
            return $product;
        }, $values);
        return $products;
    }

    public function insert(Product $product): int {
        $values = [
            "f" . Product::NAME => $product->name,
            "f" . Product::DESCRIPTION => $product->description,
            "f" . Product::BARCODE => $product->barcode,
            "f" . Product::PRICE => $product->price,
            "f" . Product::COD_STATUS => $product->codStatus,
            "f" . Product::DATE => $product->date,
            "f" . Product::DATE_UPDATE => $product->dateUpdate,
        ];
        $query = "INSERT INTO tb_product (name, price, description, barcode, cod_status, date, date_update) 
                VALUES (:fname, :fprice, :fdescription, :fbarcode, :fcod_status, :fdate, :fdate_update); ";
        $db = new DBConnection;
        return $db->insert($query, $values);
    }
}
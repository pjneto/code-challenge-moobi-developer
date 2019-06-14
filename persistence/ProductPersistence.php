<?php

require_once "persistence/DBConnection.php";
require_once "models/Product.php";

class ProductPersistence {

    const REPLACE_WHERE = "WHERE false ";
    const REPLACE_ORDER_BY = "ORDER BY P.id ";

    const SELECT = "SELECT P.id, P.name, P.price, P.description, P.barcode, P.cod_status, P.date, P.date_update "
                . "FROM tb_product P "
                . self::REPLACE_WHERE
                . self::REPLACE_ORDER_BY;

    const INSERT = "INSERT INTO tb_product (name, price, description, barcode, cod_status, date, date_update) "
                . "VALUES (:fname, :fprice, :fdescription, :fbarcode, :fcod_status, :fdate, :fdate_update); ";

    const UPDATE = "UPDATE tb_product set name = :fname, price = :fprice, description = :fdescription, " 
                . "barcode = :fbarcode, cod_status = :fcod_status, date_update = :fdate_update "
                . self::REPLACE_WHERE;

    public function select_by_id(int $id): Product {
        $args = [ 
            ":" . Product::ID => $id,
        ];
        $where = "WHERE P.id = :id ";
        $products = $this->execut_select($where, $args);
        return sizeof($products) > 0 ? array_pop($products) : new Product;
    }

    public function select_all(): array {
        $args = [ 
            ":" . Product::ID => 0 
        ];
        $where = "WHERE P.id > :id ";
        return $this->execut_select($where, $args);
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
        $query = self::INSERT;
        $db = new DBConnection;
        return $db->insert($query, $values);
    }

    public function update(Product $product): int {
        $values = [
            "f" . Product::ID => $product->id,
            "f" . Product::NAME => $product->name,
            "f" . Product::DESCRIPTION => $product->description,
            "f" . Product::BARCODE => $product->barcode,
            "f" . Product::PRICE => $product->price,
            "f" . Product::COD_STATUS => $product->codStatus,
            "f" . Product::DATE_UPDATE => $product->dateUpdate,
        ];
        $where = "WHERE id = :fid ";
        $query = str_replace(self::REPLACE_WHERE, $where, self::UPDATE);
        
        $db = new DBConnection;
        return $db->update($query, $values);
    }    

    private function execut_select(string $where = null, array $args = []): array {
        $query = is_null($where) || sizeof($args) === 0 
                ? self::SELECT_ALL
                : str_replace(self::REPLACE_WHERE, $where, self::SELECT);
        $db = new DBConnection;
        $values = $db->select($query, $args);

        return array_map(function($v) {
            $product = new Product;
            $product->from_values($v);
            return $product;
        }, $values);
    }
}
<?php

require_once "persistence/DBConnection.php";

class ProductPersistence {

    public function insert(Product $product): int {
        $values = $product->as_values();
        if (isset($values)) {
            unset($values["id"]);
        }
        $query = "INSERT INTO tb_product (name, price, description, barcode, cod_status, date, date_update) 
                VALUES (:name, :price, :description, :barcode, :cod_status, :date, :date_update); ";
        $db = new DBConnection;
        return $db->insert($query, $values);
    }
}
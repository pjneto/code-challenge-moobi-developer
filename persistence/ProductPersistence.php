<?php

require_once "persistence/DBConnection.php";
require_once "models/Product.php";

class ProductPersistence {

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
<?php 

require_once "controllers/ProductController.php";

$prodController = new ProductController;
$result = $prodController->post($prodController->input_name());
$table = $prodController->table_data();
$searchable = $prodController->searchable_value();
?>
<title>Products</title>
<style>

    div.btn-action {
        width: 50%; 
        margin: 20px 0;  
        padding-left: 5px;
    }
    div input,
    div.btn-action button {
        width: 100%;
    }
</style>

<form method="POST">
    <div class="flex">
        <div style="width: 40%">
            <h2>Product List</h2>
        </div>
        <div style="padding: 20px 0; width: 30%;">
            <input type="text" name="searchable" placeholder="Anything to search" value="<?= $searchable ?>">
        </div>
        <div style="display: flex; width: 30%;">
            <div class="btn-action">
                <button name="btn-search">Search</button>
            </div>
            <div class="btn-action">
                <button name="btn-new">New Product</button>
            </div>
            <div class="btn-action">
                <button name="btn-orders">Orders</button>
            </div>
        </div>
    </div>
    <table style="width: 100%">
        <thead>
            <tr>
                <?php 
                    foreach ($table->titles as $title):
                        $width = $title['width'] . "%";
                        $text = strtoupper($title['text']);
                        echo "<th style='width: $width'>$text</th>";
                    endforeach;
                ?>
            </tr>
        </thead>
        <tbody>
            <?php 
                foreach ($table->products as $product):
                    $price = ValuesUtil::format_money($product->price);
                    $status = $product->codStatus === PRO_INACTIVE ? "Inactive" : "Active";
                    $btnStatus = $product->codStatus === PRO_INACTIVE ? "Activate" : "Inactivate";
                    echo "
                        <tr>
                            <td>$product->id</td>
                            <td>$product->name</td>
                            <td>$price</td>
                            <td>$product->barcode</td>
                            <td>$status</td>
                            <td class='t-align-c'>
                                <button name='btn-inactivate' value='$product->id'>$btnStatus</button>
                            </td>
                            <td class='t-align-c'>
                                <button name='btn-details' value='$product->id'>Details</button>
                            </td>
                        </tr>
                    ";
                endforeach;
            ?>
        </tbody>
    </table>
</form>
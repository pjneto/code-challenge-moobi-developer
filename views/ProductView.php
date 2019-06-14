<?php 

require_once "controllers/ProductController.php";

$prodController = new ProductController;
$result = $prodController->post($prodController->input_name());
$table = $prodController->table_data();
?>
<title>Products</title>

<h2>Product List</h2>
<form method="POST">
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
                    echo "
                        <tr>
                            <td>$product->id</td>
                            <td>$product->name</td>
                            <td>$price</td>
                            <td>$product->barcode</td>
                            <td class='t-align-c'>
                                <button name='btn-inactivate' value='$product->id'>Inactivate</button>
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
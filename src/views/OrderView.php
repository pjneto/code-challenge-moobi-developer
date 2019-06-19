<?php 

require_once "controllers/OrderController.php";

$ordController = new OrderController;
$result = $ordController->post($ordController->input_name());
$table = $ordController->table_data();
?>
<title>Orders</title>
<style>

    div.btn-action {
        width: 50%; 
        margin: 20px 0;  
        padding-left: 5px;
    }
    div.btn-action button {
        width: 100%;
    }
</style>

<form method="POST">
    <div class="flex">
        <div style="width: 80%">
            <h2>Orders List</h2>
        </div>
        <div style="display: flex; width: 20%;">
            <div class="btn-action">
                <button name="btn-new">New Order</button>
            </div>
            <div class="btn-action">
                <button name="btn-products">Products</button>
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
                foreach ($table->orders as $order):
                    $date = ValuesUtil::show_date($order->date, true);
                    $value = ValuesUtil::format_money($order->value);
                    $discount = ValuesUtil::format_money($order->discount);
                    echo "
                        <tr>
                            <td>$order->id</td>
                            <td>$date</td>
                            <td>$value</td>
                            <td>$discount</td>
                            <td>$order->payment</td>
                            <td>$order->status</td>
                            <td class='t-align-c'>
                                <button name='btn-details' value='$order->id'>Details</button>
                            </td>
                        </tr>
                    ";
                endforeach;
            ?>
        </tbody>
    </table>
</form>
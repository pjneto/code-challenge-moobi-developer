<?php 

require_once "controllers/OrderController.php";

$ordController = new OrderController;
$result = $ordController->post($ordController->input_name());
$itens = $ordController->order_itens();
$disabledBtnFinish = sizeof($itens->products) === 0 ? "disabled" : "";
?>
<title>Confirm Order</title>
<style>

    div.actions {
        width: 10%; 
        margin: 20px 0;  
        padding-left: 5px;
    }
    div.actions button {
        width: 100%;
    }
</style>

<form method="POST">
    <div class="flex">
        <div style="width: 90%">
            <h2>Order itens</h2>
        </div>
        <div class="actions">
            <button style="width: 100%" name="btn-back">Back</button>
        </div>
    </div>
    <table style="width: 100%">
        <thead>
            <tr>
                <?php 
                    foreach ($itens->titles as $title):
                        $width = $title['width'] . "%";
                        $text = strtoupper($title['text']);
                        echo "<th style='width: $width'>$text</th>";
                    endforeach;
                ?>
            </tr>
        </thead>
        <tbody>
            <?php 
                foreach ($itens->products as $itens):
                    $product = $itens['item'];
                    $quantity = intval($itens['quantity']);
                    $price = ValuesUtil::format_money($product->price);
                    echo "
                        <tr>
                            <td>$product->id</td>
                            <td>$product->name</td>
                            <td>$price</td>
                            <td>$product->barcode</td>
                            <td>
                                <div class='flex'>
                                    <input type='number' name='quantitys[$product->id]' value='$quantity' style='width: 70%'/>
                                    <button name='btn-quantity' value='$product->id' style='width: 30%'>OK</button>
                                </div>
                            </td>
                            <td class='t-align-c'>
                                <button name='btn-remove' value='$product->id'>Remove</button>
                            </td>
                        </tr>
                    ";
                endforeach;
            ?>
        </tbody>
    </table>
    <div class="flex" style="justify-content: flex-end; padding-top: 20px;">
        <div>
            <label for="payment">Payment: </label>
        </div>
        <div style="width: 10%; padding-right: 10px;">
            <select style="width: 100%; height: 100%" id="payment" name="payment" onchange="changePayment(this)">
                <option value="0">Cash</option>
                <option value="1">Credt Card</option>
                <option value="2">Bank Slip</option>
            </select>
        </div>
        <div>
            <label for="payment">Parcels: </label>
        </div>
        <div style="width: 10%; padding-right: 10px;">
            <input type="number" style="width: 100%;" id="parcels" name="parcels" disabled>
        </div>
        <div style="width: 10%">
            <button style="width: 100%" name="btn-finish" <?= $disabledBtnFinish ?>>Finish</button>
        </div>
    </div>
</form>

<script>
    function changePayment(e) {
        var parcels = document.getElementById("parcels");
        parcels.disabled = parseInt(e.value) === 2;
    }
</script>
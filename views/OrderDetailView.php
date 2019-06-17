<?php 

require_once "controllers/OrderController.php";

$ordController = new OrderController;
$result = $ordController->post($ordController->input_name());
$details = $ordController->order_details();

?>
<title>Order Details</title>
<style>

    div.actions {
        width: 10%; 
        margin: 20px 0;  
        padding-left: 5px;
    }
    div.actions button {
        width: 100%;
    }
    div.form-field h4 {
        margin: 0;
    }
</style>

<form method="POST">
    
    <div class="flex">
        <div style="width: 90%">
            <h2>Order Details</h2>
        </div>
        <div class="actions">
            <button style="width: 100%" name="btn-back">Back</button>
        </div>
    </div>
    <div>
        <div class="form-field">
            <label for="name">Parcels: </label>
            <h4><?= $details->order->numParcel ?></h4>
        </div>
        <div class="form-field">
            <label for="name">Parcel value: </label>
            <h4><?= ValuesUtil::format_money($details->order->valueParcel) ?></h4>
        </div>
        <div class="form-field">
            <label for="name">Value: </label>
            <h4><?= ValuesUtil::format_money($details->order->value) ?></h4>
        </div>
        <div class="form-field">
            <label for="name">Payment: </label>
            <h4><?= $details->order->payment ?></h4>
        </div>
        <div class="form-field">
            <label for="name">Discount: </label>
            <h4><?= ValuesUtil::format_money($details->order->discount) ?></h4>
        </div>
        <div class="form-field">
            <label for="name">Status: </label>
            <h4><?= $details->order->status ?></h4>
        </div>
        <div class="form-field">
            <label for="name">Date: </label>
            <h4><?= ValuesUtil::show_date($details->order->date, true) ?></h4>
        </div>
    </div>
    <div class="flex">
        <h2>Order itens</h2>
    </div>
    <table style="width: 100%">
        <thead>
            <tr>
                <?php 
                    foreach ($details->titleItens as $title):
                        $width = $title['width'] . "%";
                        $text = strtoupper($title['text']);
                        echo "<th style='width: $width'>$text</th>";
                    endforeach;
                ?>
            </tr>
        </thead>
        <tbody>
            <?php 
                foreach ($details->products as $key => $product):
                    $price = ValuesUtil::format_money($product->price);
                    $item = $details->itens[$product->id];
                    echo "
                        <tr>
                            <td>$product->id</td>
                            <td>$product->name</td>
                            <td>$price</td>
                            <td>$product->barcode</td>
                            <td>$item->quantity</td>
                        </tr>
                    ";
                endforeach;
            ?>
        </tbody>
    </table>
</form>

<script>
    function changePayment(e) {
        var parcels = document.getElementById("parcels");
        parcels.disabled = parseInt(e.value) === 2;
    }
</script>
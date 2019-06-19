<?php 
    
require_once "controllers/OrderController.php";
$ordController = new OrderController;
$result = $ordController->post($ordController->input_name());
$products = $ordController->products();
?>
<title>New Order</title>

<style>
    .products {
        display: flex; 
        width: 100%;
    }
    .product-item {
        background-color: #f0f0f0;
        border: 1px solid #eaeaea;
        width: 30%;
        margin: 8px;
        padding: 0 5px 5px 5px;
        text-align: center;
        
        display: flex; 
        flex-direction: column;
        justify-content: space-between;
    }
    .product-item p,
    .product-item h4 {
        margin: 10px 0 4px 0;
    }
    div.actions {
        justify-content: flex-end;
        display: flex;
        width: 60%;
    }

    div.actions div.w-15, 
    div.actions div.w-50 {
        margin: 20px 0;  
        padding-left: 5px;
    }

    div.actions div.w-15 {
        width: 15%;
    }

    div.actions div.w-50 {
        width: 50%;
    }

    div.actions button, 
    div.actions input {
        width: 100%;
    }

</style>
<form method="post">
    <div class="flex">
        <div style="width: 40%">
            <h2>New Order</h2>
        </div>
        <div class="actions">
            <div class="w-50">
                <input type="text" name="searchable" placeholder="Anything to search">
            </div>
            <div class="w-15">
                <button name="btn-search">Search Product</button>
            </div>
            <div class="w-15">
                <button name="btn-confirm">Confirm Order</button>
            </div>
            <div class="w-15">
                <button name="btn-back">Back</button>
            </div>
        </div>
    </div>
    <?php 
        $n = 5;
        for ($i = 0; $i < sizeof($products); $i += $n):
            echo "<div class='products'>";
            $block = array_slice($products, $i, $n);
            foreach ($block as $product):
                $disableBtnCart = $product->stock === 0 ? "disabled" : "";
    ?>
                <div class="product-item">
                    <div>
                        <h4><?= $product->name ?></h4>
                        <p style="text-align: justify"><?= $product->description ?></p>
                    </div>
                    <div>
                        <p>Price: <?= ValuesUtil::format_money($product->price) ?>. Stock: <?= $product->stock ?> </p>
                        <button name="btn-add-cart" value="<?= $product->id ?>" <?= $disableBtnCart ?>>Add to cart</button>
                    </div>
                </div>
    <?php 
        endforeach; 
        echo "</div>"
    ?>
    <?php endfor;?>
</form>
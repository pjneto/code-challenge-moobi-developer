<?php 
    
require_once "controllers/OrderController.php";
$ordController = new OrderController;
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
</style>

<h2>New Order</h2>
<?php 
    $n = 5;
    for ($i = 0; $i < sizeof($products); $i += $n):
        echo "<div class='products'>";
        $block = array_slice($products, $i, $n);
        foreach ($block as $product):
?>
            <div class="product-item">
                <div>
                    <h4><?= $product->name ?></h4>
                    <p style="text-align: justify"><?= $product->description ?></p>
                </div>
                <div>
                    <p><?= ValuesUtil::format_money($product->price) ?></p>
                    <button name="btn-add-cart">Add to cart</button>
                </div>
            </div>
<?php 
    endforeach; 
    echo "</div>"
?>
<?php endfor;?>

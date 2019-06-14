<?php 
require_once "controllers/ProductController.php";

$prodController = new ProductController;
$result = $prodController->post($prodController->input_name());
$id = isset($_GET['value']) ? intval($_GET['value']) : -1;
$product = $prodController->get_product($id);
?>

<title>Product Details</title>

<h2>Product Details</h2>
<?php if (is_null($product) || $product->id <= 0): ?>
    <h3>Invalid Product</h3>
<?php else: ?>
    <form method="post">
        <div class="form-field">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" placeholder="Product name" value="<?= $product->name ?>">
        </div>
        <div class="form-field">
            <label for="barcode">Barcode</label>
            <input id="barcode" name="barcode" placeholder="Barcode" value="<?= $product->barcode ?>">
        </div>
        <div class="form-field">
            <label for="price">Price</label>
            <input type="number" id="price" name="price" placeholder="Unit price" value="<?= $product->price ?>">
        </div>
        <div class="form-field">
            <label for="description">Description</label>
            <textarea name="description" id="description" cols="30" rows="4" placeholder="Some product's description"><?= $product->description?></textarea>
        </div>
        <div class="form-field">
            <label></label>
            <button type="submit" name="btn-edit" value="<?= $product->id ?>">Edit</button>
        </div>
    </form>

<?php endif; ?>
<?php
require_once 'config/config.php';
require_once 'classes/Pedidos.php';
require_once 'classes/Produto.php';

$p = new Pedido();
$c = new Produto();

$produtos_cadastrados = $c->exibirProdutos();

if(isset($_POST['nome_produto']) && isset($_POST['form_pag']) && isset($_POST['vl_produto']) && isset($_POST['qtd_produtos']) && isset($_POST['num_parcelas'])) {
    $nome_produto   = addslashes($_POST['nome_produto']);
    $form_pag           = addslashes($_POST['form_pag']);
    $vl_produto         = addslashes($_POST['vl_produto']);
    $qtd_produtos     = addslashes($_POST['qtd_produtos']);
    $num_parcelas    = addslashes($_POST['num_parcelas']);
    $p->cadastrarPedido($form_pag, $vl_produto, $qtd_produtos, $num_parcelas, $nome_produto);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste</title>
</head>
<body>
<form method="POST" name="cadastrar-pedido.php">

<select name="nome_produto">
                    <option>Selecione um produto</option>
                    <?php foreach ($produtos_cadastrados as $produtos): ?>
                        <option value="<?php echo $produtos['nome']?>">
                            <?php echo $produtos['nome'] ?>
                        </option>
                    <?php endforeach ?>
                </select>

                <input type="text" name="form_pag" placeholder="forma do pagamento">

                <input type="text" name="vl_produto" placeholder="valor do produto">

                <input type="text" name="qtd_produtos" placeholder="quantidade produtos">

                <input type="text" name="num_parcelas" placeholder="numero de parcelas">

                <button type="submit" name="cadastrar">cadastar</button>
            </form>
</body>
</html>
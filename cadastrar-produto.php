<?php
require_once 'config/config.php';
require_once 'classes/Produto.php';

$p = new Produto();
$prod = null;

if(isset($_POST['nome']) && isset($_POST['qtd_produto']) && isset($_POST['ativo'])) {
    $nome                   = addslashes($_POST['nome']);
    $qtd_produto       = addslashes($_POST['qtd_produto']);
    $ativo                  = addslashes($_POST['ativo']);

    $p->cadastrarProduto($nome, $qtd_produto, $ativo);
}

?>

<?php
require_once 'config/config.php';
require_once 'classes/Produto.php';

$p = new Produto();

$id = $_GET['id'];

if(isset($id)) { 
    if(isset($_POST['nome']) && isset($_POST['qtd_produto']) && isset($_POST['ativo'])) {
        $nome                   = addslashes($_POST['nome']);
        $qtd_produto       = addslashes($_POST['qtd_produto']);
        $ativo                  = addslashes($_POST['ativo']);
        $id                        = addslashes($_POST['id']);
        $p->editarProduto($nome, $qtd_produto, $ativo, $id);
    }
}

?>

<?php 
class Pedido{
    // Cadastrar pedido
    public function cadastrarPedido($form_pag, $vl_produto, $qtd_produtos, $num_parcelas, $nome_produto) 
    {
        try{
            if(!empty($form_pag) && !empty($vl_produto) && !empty($nome_produto) && !empty($qtd_produtos)){
                global $pdo;

                // $sql = $pdo->query("SELECT nome FROM produtos where  nome = $nome_produto");
                // $nm_estoque =  $sql->fetch(PDO::FETCH_ASSOC);

                if($form_pag == "boleto") {
                    $sql = $pdo->prepare("INSERT INTO pedidos(form_pag, vl_venda, qtd_produtos, nome_produto) 
                    values (:form_pag, :vl_venda, :qtd_produtos, :nome_produto)");

                    $vl_total = $qtd_produtos * $vl_produto;
                    $valor_desconto = $vl_total - ($vl_total / 100 * 5);

                    $sql->bindValue(":form_pag", $form_pag);
                    $sql->bindValue(":vl_venda", $valor_desconto);
                    $sql->bindValue("qtd_produtos", $qtd_produtos);
                    $sql->bindValue("nome_produto", $nome_produto);
                    $sql->execute();
                
                } else if($form_pag == 'credito') {
                    $sql = $pdo->prepare("INSERT INTO pedidos(form_pag, vl_venda, num_parcelas, vl_parcelas, qtd_produtos, nome_produto ) 
                    values (:form_pag, :vl_venda, :num_parcelas, :vl_parcelas, :qtd_produtos, :nome_produto)");
                    
                    $vl_total = $qtd_produtos * $vl_produto;
                    $vl_parcelas = ($vl_total/$num_parcelas);
                
                    $sql->bindValue(":form_pag", $form_pag);
                    $sql->bindValue(":vl_venda", $vl_total);
                    $sql->bindValue(":num_parcelas", $num_parcelas);
                    $sql->bindValue("vl_parcelas", $vl_parcelas);
                    $sql->bindValue("qtd_produtos", $qtd_produtos);
                    $sql->bindValue("nome_produto", $nome_produto);
                
                    $sql->execute();

                } else if($form_pag == 'debito') {
                    $sql = $pdo->prepare("INSERT INTO pedidos(form_pag, vl_venda, qtd_produtos, nome_produto ) 
                    values (:form_pag, :vl_venda, :qtd_produtos, :nome_produto)");
                
                    $vl_total = $qtd_produtos * $vl_produto;
                   $valor_desconto = $vl_total - ($vl_total / 100 * 10);
                
                    $sql->bindValue(":form_pag", $form_pag);
                    $sql->bindValue(":vl_venda", $valor_desconto);
                    $sql->bindValue("qtd_produtos", $qtd_produtos);
                    $sql->bindValue("nome_produto", $nome_produto);
                
                    $sql->execute();
                }
            }
        } catch(PDOException$e) {
            echo "Erro ao cadastrar pedido" .$e->getMessage();
        }
    }

    // Enviar email
    public function sendEmail()
    {
        $headers = "MIME-Version: 1.1\n";
        $headers .= "Content-type: text/plain; charset=UTF-8\n";
        $headers .= "From: eu@teste.com\n";
        $headers .= "Return-Path: eu@teste.com\n"; 
        $envio = mail("destinatario@gmail.com", "Assunto", "Texto", $headers);
         
        if($envio)
         echo "Mensagem enviada com sucesso";
        else
         echo "A mensagem não pode ser enviada";
    }
}
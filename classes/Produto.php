<?php 

class Produto {

        // Cadastrar produtos
        public function cadastrarProduto($nome, $qtd_produto, $ativo) 
        {
            try {
                if(!empty($nome) && !empty($qtd_produto) && !empty($ativo)){
                    global $pdo;
                    $sql = $pdo->prepare("INSERT INTO produtos(nome, qtd_produto, ativo) values (:nome, :qtd_produto, :ativo)");
            
                    $sql->bindValue(":nome", $nome);
                    $sql->bindValue(":qtd_produto", $qtd_produto);
                    $sql->bindValue(":ativo", $ativo);
                    $sql->execute();
                    return true;
                } else {
                    return false;
                }

            } catch(PDOException $e) {
                echo "Falha ao cadastrar notícia: " .$e->getMessage();
            }
        }

        // Exibir produto
        public function exibirProduto($id) 
        {
            try{
                global $pdo;
                $sql = $pdo->query("SELECT * FROM produtos where ativo = 1 and qtd_produto > 0");
                return $sql->fetchAll(PDO::FETCH_ASSOC);
            } catch(PDOException $e){
                echo "Falha ao exibir o produto: " .$e->getMessage();
            }
        }

         //Exibir todos os produtos ativos
        public function exibirProdutos() 
        {
            try{
                global $pdo;
                $sql = $pdo->query("SELECT * FROM produtos where ativo = 1 and qtd_produto > 0");
                return $sql->fetchAll(PDO::FETCH_ASSOC);
            } catch(PDOException $e){
                echo "Falha ao exibir o produto: " .$e->getMessage();
            }
        }

        //Exibir produtos inativos
        public function exibirProdutoInativos()
        {
            try{
                global $pdo;
                $sql = $pdo->prepare("SELECT * FROM produtos where ativo = 0");
                $sql->execute();
               return  $sql->fetchAll(PDO::FETCH_ASSOC);

            } catch(PDOException $e){
                echo "Falha ao exibir o produto: " .$e->getMessage();
            }
        }

        // Atualizar produtos
        public function editarProduto($id, $nome, $qtd_produto, $ativo) 
        {
            try{
                if(!empty($nome) && !empty($qtd_produto) && !empty($id)){
                global $pdo;
            
                $sql = $pdo->prepare("UPDATE produtos SET nome = :nome, qtd_produto = :qtd_produto, ativo = :ativo WHERE id = :id ");
            
                $sql->bindValue(":nome", $nome);
                $sql->bindValue(":qtd_produto", $qtd_produto);
                $sql->bindValue(":ativo", $ativo);
                $sql->bindValue(":id", $id);
                $sql->execute();
                return true;
                } else {
                    return false;
                }
            } catch (PDOException $e) {
                echo "Falha ao atualizar o produto" .$e->getMessage();
            }
        }
    }
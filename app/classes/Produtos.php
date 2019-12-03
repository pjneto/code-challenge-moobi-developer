<?php
namespace CodeChallengeMoobi;

use CodeChallengeMoobi\Conexao;

class Produtos
{
    protected $tabela;
    protected $objConexao;

    public function __construct()
    {
        $this->objConexao = new Conexao\AcessoBanco();
        $this->tabela = 'produtos';
        $this->chave = 'idProduto';
    }

    public function retornarProdutos()
    {
        return $this->objConexao->select($this->tabela);
    }

    public function consultarProduto($idProduto)
    {
        return $this->objConexao->selectById($this->tabela, $idProduto, $this->chave);
    }

    public function cadastrarProduto(array $dados)
    {
        try {
            if (empty($dados['nome'])) {
                throw new \Exception("Erro: Nome do produto não informado!");
            }

            if (empty($dados['preco'])) {
                throw new \Exception("Erro: Preço do produto não informado!");
            }

            if (empty($dados['quantidade'])) {
                throw new \Exception("Erro: Quantidade do produto em estoque não informado!");
            }

            return $this->objConexao->insert($this->tabela, $dados);
        } catch(\Exception $erro) {
            echo $erro->getMessage();
            exit;
        }
    }

    public function atualizarProduto(array $novosDados, $idProduto)
    {
        return $this->objConexao->update($this->tabela, $novosDados, $idProduto, $this->chave);
    }

    public function inativarProduto($idProduto)
    {
        $dados['ativo'] = 'N';
        return $this->objConexao->update($this->tabela, $dados, $idProduto, $this->chave);
    }

    public function apagarProduto($idProduto)
    {
    	return $this->objConexao->delete($this->tabela, $idProduto, $this->chave);
    }

    public function verificarQtdProduto(array $produtos)
    {
        $retorno = ['sucesso' => true];
        
        foreach ($produtos as $idproduto) {
            $produto = $this->consultarProduto($idproduto);

            if ($produto[0]->quantidade == 0){
                return ['sucesso' => false, 'nome' => $produto[0]->nome];
                break;
            }
        }

        return $retorno;
    }

    public function decrementarEstoqueDosProdutos(array $produtos)
    {
        foreach ($produtos as $idProduto) {
            $produto = $this->consultarProduto($idProduto);
            $this->atualizarProduto(['quantidade' => ($produto[0]->quantidade - 1)], $idProduto);
        }

        return true;
    }
}
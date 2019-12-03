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
                throw new \Exception("Nome do produto não informado!");
            }

            if (empty($dados['preco'])) {
                throw new \Exception("Preço do produto não informado!");
            }

            if (empty($dados['quantidade'])) {
                throw new \Exception("Quantidade do produto em estoque não informado!");
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
}
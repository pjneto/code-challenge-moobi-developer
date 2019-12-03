<?php
namespace CodeChallengeMoobi;

use CodeChallengeMoobi\Conexao;

class Clientes
{
    protected $tabela;
    protected $objConexao;

    public function __construct()
    {
        $this->objConexao = new Conexao\AcessoBanco();
        $this->tabela = 'clientes';
        $this->chave = 'idCliente';
    }

    public function retornarClientes()
    {
        return $this->objConexao->select($this->tabela);
    }

    public function retornarClientePorId($idCliente)
    {
        return $this->objConexao->selectById($this->tabela, $idCliente, $this->chave);
    }

    public function cadastrarCliente(array $dados)
    {
        return $this->objConexao->insert($this->tabela, $dados);
    }

    public function atualizarCliente(array $dados, $idCliente)
    {
        return $this->objConexao->update($this->tabela, $dados, $idCliente, $this->chave);
    }

    public function inativarCliente($idCliente)
    {
        $dados['ativo'] = 'N';
        return $this->objConexao->update($this->tabela, $dados, $idCliente, $this->chave);
    }

}
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

    public function consultarCliente($idCliente)
    {
        return $this->objConexao->selectById($this->tabela, $idCliente, $this->chave);
    }

    public function cadastrarCliente(array $dados)
    {
        try {
            if (empty($dados['documento'])) {
                throw new \Exception("Erro: Documento não informado!");
            }

            if (empty($dados['telefone'])) {
                throw new \Exception("Erro: Telefone não informado!");
            }

            if (empty($dados['celular'])) {
                throw new \Exception("Erro: Celular não informado!");
            }

            if (empty($dados['email'])) {
                throw new \Exception("Erro: E-mail não informado!");
            }

            return $this->objConexao->insert($this->tabela, $dados);
        } catch(\Exception $erro) {
            echo $erro->getMessage();
            exit;
        }
    }

    public function atualizarCliente(array $novosDados, $idCliente)
    {
        return $this->objConexao->update($this->tabela, $novosDados, $idCliente, $this->chave);
    }

    public function inativarCliente($idCliente)
    {
        $dados['ativo'] = 'N';
        return $this->objConexao->update($this->tabela, $dados, $idCliente, $this->chave);
    }

    public function apagarCliente($idCliente)
    {
        return $this->objConexao->delete($this->tabela, $idCliente, $this->chave);
    }

}
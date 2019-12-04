<?php
namespace CodeChallengeMoobi;

use CodeChallengeMoobi\Conexao;

class Pedidos
{
    protected $tabela;
    protected $objConexao;

    public function __construct()
    {
        $this->objConexao = new Conexao\AcessoBanco();
        $this->tabela = 'pedidos';
        $this->chave = 'idPedido';
    }

    public function retornarPedidos()
    {
        return $this->objConexao->select($this->tabela);
    }

    public function consultarPedido($idPedido)
    {
        return $this->objConexao->selectById($this->tabela, $idPedido, $this->chave);
    }

    public function cadastrarPedido(array $dados)
    {
        try {

            $objProduto = new Produtos();
            $objPedidoProduto = new PedidosProdutos();
            $produtos = $dados['produtos'];

            if ($this->verificarRegras($dados)) {

                $dadosDoPedido = $this->montarDados($dados);
                $objProduto->decrementarEstoqueDosProdutos($produtos);
                $salvarPedido = $this->objConexao->insert($this->tabela, $dadosDoPedido);
                $idPedidoSalvo = $this->objConexao->lastInsertId($this->tabela, $this->chave)[0]->idPedido;
                $salvarPedidoProduto = $objPedidoProduto->cadastrarPedidosProdutos($produtos, $idPedidoSalvo);

                if ($salvarPedidoProduto) {

                    $objCliente = new Clientes();
                    $dadosCliente = $objCliente->consultarCliente($dados['idCliente']);

                    $this->enviarEmailCliente($dadosCliente);
                    $this->enviarSmsCliente($dadosCliente);
                }
            }

            return true;

        } catch(\Exception $erro) {
            echo $erro->getMessage();
            exit;
        }
    }

    public function verificarRegras(array $dados)
    {
        if (empty($dados['dataPedido'])) {
            throw new \Exception("Erro: Data do pedido não informado!");
        }

        if (empty($dados['formaPagamento'])) {
            throw new \Exception("Erro: Forma de pagamento do pedido não informado!");
        }

        if (empty($dados['produtos'])) {
            throw new \Exception("Erro: Não é possível registrar o pedido sem informar ao menos um produto!");
        }

        if (empty($dados['idCliente'])) {
            throw new \Exception("Erro: Cliente para o pedido não informado!");
        }

        $objProduto = new Produtos();
        $existenciaEstoque = $objProduto->verificarQtdProduto($dados['produtos']);
        if (!$existenciaEstoque['sucesso']) {
            throw new \Exception(
                "Erro: Não é possível registrar o pedido, pois não possui o produto " . $existenciaEstoque['nome'] . " no estoque!"
            );
        }

        if ($dados['formaPagamento'] == 'BO' && (!empty($dados['numParcelas']) || !empty($dados['valorParcela']))) {
            throw new \Exception("Erro: Só é possível parcelar compras no cartão de crédito!");
        }

        if ($dados['formaPagamento'] == 'CD' && empty($dados['numParcelas'])) {
            throw new \Exception("Erro: Informe a quantidade de parcelas para compras no cartão de crédito!");
        }

        return true;
    }

    public function montarDados(array $dados)
    {
        $desconto = $this->retornarValorDesconto($dados['formaPagamento']);

        $dados['dataCad'] = date('Y-m-d H:i:s');
        $dados['ativo'] = 'S';
        $dados['valorTotalVenda'] = $this->calcularValorVendaPorProdutos($dados['produtos'], $desconto);
        $dados['valorParcela'] = ( $dados['formaPagamento'] == 'CD' ? $dados['valorTotalVenda'] / $dados['numParcelas'] : null ); 

        unset($dados['produtos']);

        return $dados;
    }

    public function retornarValorDesconto($formaPagamento)
    {
        switch ($formaPagamento) {
            // Boleto
            case 'BO':
                $desconto = 0.05;
                break;
            // Cartão de Crédito
            case 'CD':
                $desconto = 0;
                break;
            // Débito
            case 'DE':
                $desconto = 0.10;
                break;
        }

        return $desconto;
    }

    public function calcularValorVendaPorProdutos(array $produtos, $desconto)
    {

        $objProduto = new Produtos();
        $valorVenda = 0;

        foreach ($produtos as $idProduto) {
            $produto = $objProduto->consultarProduto($idProduto);
            $valorVenda = $valorVenda + $produto[0]->preco;
        }

        if ($desconto > 0) {
            $valorVenda = $valorVenda * (1 - $desconto);
        }

        return $valorVenda;
    }

    public function enviarEmailCliente(array $dadosCliente)
    {
        $headers = "MIME-Version: 1.1\r\n";
        $headers .= "Content-type: text/plain; charset=UTF-8\r\n";
        $headers .= "From: lojabrinquedos@moobitech.com\r\n";
        $headers .= "Return-Path: lojabrinquedos@moobitech.com\r\n"; 
        
        $destino = $dadosCliente[0]->email;
        $assunto  = "Novo Pedido";
        $corpo = "Novo pedido realizado na Loja de Brinquedos da MoobiTech!";

        $emailEnvio = $this->simulacaoEnvioEmail($destino, $assunto, $corpo, $headers);

        if ($emailEnvio) {
            return true;
        }

        return false;
    }

    public function simulacaoEnvioEmail($destino, $assunto, $corpo, $headers) 
    {
        return true;
    }

    public function enviarSmsCliente(array $dadosCliente)
    {
        $corpoSms = 'Novo pedido realizado na Loja de Brinquedos da MoobiTech!';
        $nomePara = $dadosCliente[0]->nome;

        $dadosGateway = [
            'loginApiSMS' => 'loginTeste',
            'tokenApiSMS' => 'tokenTeste',
            'celular' => $dadosCliente[0]->celular,
            'nome' => $nomePara,
            'mensagem' => $corpoSms
        ];

        $smsEnvio = $this->simulacaoEnvioSmsApi($dadosGateway);

        if ($smsEnvio) {
            return true;
        }

        return false;
    }

    
    public function simulacaoEnvioSmsApi($dadosGateway)
    {
        return true;
    }

    public function atualizarPedido(array $novosDados, $idProduto)
    {
        return $this->objConexao->update($this->tabela, $novosDados, $idProduto, $this->chave);
    }

    public function inativarPedido($idPedido)
    {
        $dados['ativo'] = 'N';
        return $this->objConexao->update($this->tabela, $dados, $idProduto, $this->chave);
    }

    public function apagarPedido($idPedido)
    {
    	return $this->objConexao->delete($this->tabela, $idProduto, $this->chave);
    }
}
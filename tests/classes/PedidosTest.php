<?php
require 'vendor/autoload.php';

use PHPUnit\Framework\TestCase;

class PedidosTest extends TestCase
{

	public function getObjetoPedido()
	{
		return new CodeChallengeMoobi\Pedidos();
	}

	public function testeRetornarPedidos() 
	{
		$objPedido = $this->getObjetoPedido();
		$qtdePedidos = count($objPedido->retornarPedidos());
		$this->assertEquals(2, $qtdePedidos);
	}

	public function testeConsultarProduto()
	{
		$objPedido = $this->getObjetoPedido();
		$pedido = $objPedido->consultarPedido(1000);
		$this->assertEquals(DEBITO, $pedido[0]->formaPagamento);

		$objPedido = $this->getObjetoPedido();
		$pedido = $objPedido->consultarPedido(1001);
		$this->assertEquals(CARTAO_CREDITO, $pedido[0]->formaPagamento);
	}

	public function testeCadastrarPedidoSucesso()
	{
		$novoPedido = [
			'dataCad' 			=> date('Y-m-d H:i:s'),
			'ativo' 			=> 'S',
			'dataPedido' 		=> date('Y-m-d H:i:s'),
			'formaPagamento' 	=> CARTAO_CREDITO,
			'numParcelas' 		=> '5',
			'idCliente' 		=> '100',
			'produtos'			=> [60, 61, 62]
		];

		$objPedido = $this->getObjetoPedido();
		$resultadoCadastro = $objPedido->cadastrarPedido($novoPedido);
		$this->assertEquals(true, $resultadoCadastro);

		$qtdePedidos = count($objPedido->retornarPedidos());
		$this->assertEquals(3, $qtdePedidos);
	}

	public function testeCadastrarPedidoErro()
	{

		$novoPedido1 = [
			'dataCad' 			=> date('Y-m-d H:i:s'),
			'ativo' 			=> 'S',
			'dataPedido' 		=> date('Y-m-d H:i:s'),
			'formaPagamento' 	=> DEBITO,
			'numParcelas' 		=> '5',
			'idCliente' 		=> '100',
			'produtos'			=> [60, 61]
		];

		$objPedido = $this->getObjetoPedido();
		$resultado1 = $objPedido->cadastrarPedido($novoPedido1);
		$this->assertEquals(PARCELAS_SOMENTE_CARTAO_CREDITO, $resultado1);


		$novoPedido2 = [
			'dataCad' 			=> date('Y-m-d H:i:s'),
			'ativo' 			=> 'S',
			'dataPedido' 		=> date('Y-m-d H:i:s'),
			'formaPagamento' 	=> CARTAO_CREDITO,
			'numParcelas' 		=> '10',
			'idCliente' 		=> '101',
			'produtos'			=> [64]
		];

		$objPedido = $this->getObjetoPedido();
		$resultado2 = $objPedido->cadastrarPedido($novoPedido2);
		$this->assertEquals(SEM_PEDIDO_ESTOQUE, $resultado2);

	}
}
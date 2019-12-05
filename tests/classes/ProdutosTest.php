<?php
require 'vendor/autoload.php';

use PHPUnit\Framework\TestCase;

class ProdutosTest extends TestCase
{

	public function getObjetoProduto()
	{
		return new CodeChallengeMoobi\Produtos();
	}

	public function testeRetornarProdutos() 
	{
		$objProduto = $this->getObjetoProduto();
		$qtdeProdutos = count($objProduto->retornarProdutos());
		$this->assertEquals(4, $qtdeProdutos);
	}

	public function testeConsultarProduto()
	{
		$objProduto = $this->getObjetoProduto();
		$produto = $objProduto->consultarProduto(63);
		$this->assertEquals('Robô', $produto[0]->nome);
	}

	public function testeCadastrarProdutoSucesso()
	{
		$novoProduto = [
			'dataCad' 		=> date('Y-m-d H:i:s'),
			'ativo' 		=> 'S',
			'nome' 			=> 'Banco Imobiliário',
			'modelo' 		=> 'Tradicional',
			'marca' 		=> 'Estrela',
			'preco' 		=> '500.99',
			'quantidade' 	=> '50'
		];

		$objProduto = $this->getObjetoProduto();
		$resultadoCadastro = $objProduto->cadastrarProduto($novoProduto);
		$this->assertEquals(true, $resultadoCadastro);

		$produtos = $objProduto->retornarProdutos();
		$resultado = $produtos[count($produtos) - 1]->nome;
		$this->assertEquals('Banco Imobiliário', $resultado);

	}

	// public function testeCadastrarProdutoErro()
	// {
	// 	// $dadosCliente = [
	// 	// 	'dataCad' 	=> date('Y-m-d H:i:s'),
	// 	// 	'ativo' 	=> 'S',
	// 	// 	'nome' 		=> 'João',
	// 	// 	'tipoDoc' 	=> 'CPF',
	// 	// 	'documento' => '01234567890',
	// 	// 	'telefone' 	=> '79999874563',
	// 	// 	'email'		=> 'email@email.com'
	// 	// ];

	// 	// $objCliente = $this->getObjetoCliente();
	// 	// $resultadoCadastro = $objCliente->cadastrarCliente($dadosCliente);
	// 	// $this->assertEquals(CELULAR_NAO_INFORMADO, $resultadoCadastro);
	// }

	// public function testeAtualizarCliente()
	// {
	// 	// $objCliente = $this->getObjetoCliente();
	// 	// $novosDados = ['email' => 'lojaonline@loja.com'];
	// 	// $resultUpdate = $objCliente->atualizarCliente($novosDados, 102);
	// 	// $this->assertEquals(true, $resultUpdate);
	// }

	// public function testeInativarCliente()
	// {
	// 	// $objCliente = $this->getObjetoCliente();
	// 	// $resultUpdate = $objCliente->inativarCliente(102);
	// 	// $this->assertEquals(true, $resultUpdate);
	// }

	// public function testeVerificarQtdProdutoCompra()
	// {

	// }

	// public function testeDecrementarEstoqueDosProdutos()
	// {

	// }


}
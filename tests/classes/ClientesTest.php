<?php
require 'vendor/autoload.php';
require 'config/config.php';

use PHPUnit\Framework\TestCase;

class ClientesTest extends TestCase
{

	public function getObjetoCliente()
	{
		return new CodeChallengeMoobi\Clientes();
	}

	public function testeRetornarClientes() 
	{
		$objCliente = $this->getObjetoCliente();
		$qtdeClientes = count($objCliente->retornarClientes());
		$this->assertEquals(3, $qtdeClientes);
	}

	public function testeConsultarCliente()
	{
		$objCliente = $this->getObjetoCliente();
		$cliente = $objCliente->consultarCliente(100);
		$this->assertEquals('Antonio', $cliente[0]->nome);
	}

	public function testeCadastrarClienteSucesso()
	{
		$dadosCliente = [
			'dataCad' 	=> date('Y-m-d H:i:s'),
			'ativo' 	=> 'S',
			'nome' 		=> 'Maria',
			'sexo' 		=> 'F',
			'dataNasc' 	=> '1995-10-15',
			'tipoDoc' 	=> 'CPF',
			'documento' => '01234567899',
			'telefone' 	=> '79999874563',
			'celular' 	=> '79454547785',
			'email' 	=> 'maria@email.com'
		];

		$objCliente = $this->getObjetoCliente();
		$resultadoCadastro = $objCliente->cadastrarCliente($dadosCliente);
		$this->assertEquals(true, $resultadoCadastro);
	}

	public function testeCadastrarClienteErro()
	{
		$dadosCliente = [
			'dataCad' 	=> date('Y-m-d H:i:s'),
			'ativo' 	=> 'S',
			'nome' 		=> 'João',
			'tipoDoc' 	=> 'CPF',
			'documento' => '01234567890',
			'telefone' 	=> '79999874563',
			'email'		=> 'email@email.com'
		];

		$objCliente = $this->getObjetoCliente();
		$resultadoCadastro = $objCliente->cadastrarCliente($dadosCliente);
		$this->assertEquals(CELULAR_NAO_INFORMADO, $resultadoCadastro);
	}

	public function testeAtualizarCliente()
	{
		$objCliente = $this->getObjetoCliente();
		$novosDados = ['email' => 'lojaonline@loja.com'];
		$resultUpdate = $objCliente->atualizarCliente($novosDados, 102);
		$this->assertEquals(true, $resultUpdate);
	}

	public function testeInativarCliente()
	{
		$objCliente = $this->getObjetoCliente();
		$resultUpdate = $objCliente->inativarCliente(102);
		$this->assertEquals(true, $resultUpdate);
	}

}
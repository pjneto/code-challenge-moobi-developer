<?php
require 'vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;

class ConexaoTest extends TestCase
{
	use TestCaseTrait;

	private $conn = null;

	public function getConnection()
    {
    	if (!$this->conn) {
            $db = new \PDO("mysql:host=".HOST.";dbname=" . DATABASE, USUARIO, SENHA);
            $this->conn = $this->createDefaultDBConnection($db, DATABASE);
        }

        return $this->conn;

	}

    public function getDataSet()
	{
        return $this->createXMLDataSet("./tests/classes/DadosTest/loja_brinquedos_db.xml");
	}

	public function testLendo()
    { 
        $conn = $this->getConnection()->getConnection();
   
        $query = $conn->query('SELECT * FROM clientes');
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
         
        $this->assertCount(2, $results);
        $this->assertEquals('Antonio', $results[0]['nome']);
        $this->assertEquals('Ana', $results[0]['nome']);
 
        // // lendo tracks
        // $query = $conn->query('SELECT count(*) as total_tracks FROM tracks');
        // $results = $query->fetchAll(PDO::FETCH_ASSOC);
        // $this->assertEquals(3, $results[0]['total_tracks']);
    }
}

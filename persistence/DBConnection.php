<?php 
require_once "configs.php";

class DBConnection {

    private $host, $dbName, $username, $password;

    function __construct() {
        $this->host = DB_HOST;
        $this->dbName = DB_NAME;
        $this->username = DB_USER;
        $this->password = DB_PASSWORD;
    }

    public function insert(string $query, array $params): int {
        $connection = $this->open_connection();
        try {
            $stmt = $connection->prepare($query);
            if ($stmt) {

                $connection->beginTransaction();
                $stmt->execute($params);
                $id = $connection->lastInsertId();
                $connection->commit();
                return $id;
            }

        } catch (PDOException $e) {
            echo "<br><br><b>PDOException: " . $e->getMessage() . "<br><br></b>";
        }
        return -1;
    }

    private function open_connection(): PDO {
        try {
            $options = array(
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8'
            );
            $strConnection = "mysql:host=$this->host;dbname=$this->dbName";

            $connection = new PDO($strConnection, $this->username, $this->password, $options);
            $connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $connection;

        } catch (PDOException $e) {
            echo "Falha: " . $e->getMessage() . "\n";
        }
        return null;
    }
}
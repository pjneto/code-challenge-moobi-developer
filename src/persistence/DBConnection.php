<?php 
require_once "configs.php";

class DBConnection {

    private $host, $dbName, $username, $password, $port;

    function __construct() {
        $this->dbName = self::get_db_name();
        $this->host = DB_HOST;
        $this->username = DB_USER;
        $this->password = DB_PASSWORD;
        $this->port = DB_PORT;
    }

    public static function get_db_name(): ?string {
        $dbNames = [
            "DEV" => DEV_DB_NAME,
            "HOMOL" => HOMOL_DB_NAME,
            "PROD" => PROD_DB_NAME,
        ];
        return $dbNames[ENVIRONMENT] ?? null;
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

    public function update(string $query, array $params): int {
        $connection = $this->open_connection();
        $prepare = $connection->prepare($query);
        if ($prepare) {
            $prepare->execute($params);
            $rows = $prepare->rowCount();
            return $rows;
        }
        return -1;
    }

    public function delete(string $query, array $params): int {
        $connection = $this->open_connection();
        $prepare = $connection->prepare($query);
        
        if ($prepare) {
            $prepare->execute($params);
            $rows = $prepare->rowCount();
            return $rows;
        }
        return -1;
    }

    public function select(string $query, array $params = null): ?array{
        $connection = $this->open_connection();

        $prepare = $connection->prepare($query);
        if ($prepare) {
            is_null($params) ? $prepare->execute() : $prepare->execute($params);

            $rows = $prepare->fetchAll();
            return $rows;
        }
        return null;
    }

    private function open_connection(): PDO {
        try {
            $options = array(
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8'
            );
            $strConnection = "mysql:host=$this->host;port=$this->port;dbname=$this->dbName";

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
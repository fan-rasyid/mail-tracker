<?php
class Database
{
    private $host = DB_HOST,
        $db_name = DB_NAME,
        $uname = DB_USER,
        $pass = DB_PASS;

    protected $dbh;

    // di luar
    public function connect()
    {
        $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name;

        $option = [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ];
        try {
            // return
            return new \PDO($dsn, $this->uname, $this->pass, $option);
        } catch (\PDOException $e) {
            echo "Connection Failed" . $e->getMessage();
            exit; // Exit the script since we can't continue without database connection
        }
    }
}

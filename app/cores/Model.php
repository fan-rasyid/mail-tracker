<?php
class Model extends Database
{
    protected $stmt,
        $dbh;

    protected $table_name,
        $primary_key,
        $field_name = [];

    public function __construct()
    {
        $this->dbh = $this->connect();
    }

    public function setTableName($table_name)
    {
        $this->table_name = $table_name;
    }

    public function setFieldName($field_name)
    {
        $this->field_name = $field_name;
    }

    public function setPrimaryKey($primary_key)
    {
        $this->primary_key = $primary_key;
    }

    public function getStringQuery()
    {
        return "SELECT * FROM $this->table_name";
    }

    public function getAllData()
    {
        $sql = 'SELECT * FROM ' . $this->table_name;
        $st = $this->dbh->query($sql);
        if ($st->rowCount() > 0) {
            $data = $st->fetchAll(PDO::FETCH_ASSOC);
            return $data;
        } else return 0;
    }

    public function getDataById($id)
    {
        if (is_array($id) && isset($id[0])) {
            // membuat variabel id agar tidak menjadi array
            $id = $id[0];
        }

        $sql = "SELECT * FROM {$this->table_name} WHERE {$this->primary_key} = $id";
        $st = $this->dbh->query($sql);
        if ($st->rowCount() > 0) {
            $data = $st->fetch(PDO::FETCH_ASSOC);
            return $data;
        } else return 0;
    }

    public function create($data)
    {

        // memastikan data sudah dideklarasikan
        if (empty($this->table_name) || empty($this->field_name)) {
            throw new Exception('Table name or field names are not set.');
        }

        // Menyiapkan query SQL
        $fields = implode(', ', $this->field_name);
        $placeholders = implode(', ', array_fill(0, count($this->field_name), '?'));

        $sql = "INSERT INTO {$this->table_name} ($fields) VALUES ($placeholders)";

        $stmt = $this->dbh->prepare($sql);

        // mengambil value secara dinamis
        $values = [];
        foreach ($this->field_name as $field) {
            $values[] = $data[$field] ?? null;
        }

        $stmt->execute($values);

        return $stmt->rowCount();
    }

    public function update($data)
    {
        // memastikan data sudah dideklarasikan
        if (empty($this->table_name) || empty($this->field_name)) {
            throw new Exception('Table name or field names are not set.');
        }

        // Menyiapkan query SQL
        $setClause = implode(', ', array_map(fn($field) => "$field = ?", array_keys($data)));
        $sql = "UPDATE {$this->table_name} SET $setClause WHERE {$this->primary_key} = ?";
        $stmt = $this->dbh->prepare($sql);

        
        $values = array_values($data);
        $values[] = $data[$this->primary_key] ?? null;

        $stmt->execute($values);

        return $stmt->rowCount(); // Return the number of affected rows
    }

    public function deleteData($id)
    {
        if (is_array($id) && isset($id[0])) {
            // membuat variabel id agar tidak menjadi array
            $id = $id[0];
        }

        $sql = "DELETE FROM {$this->table_name}  WHERE {$this->primary_key} = $id";
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();

        return $stmt->rowCount();
    }

    public function query($query)
    {
        $this->stmt = $this->dbh->prepare($query);
    }

    public function bind($param, $value, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }


    public function execute()
    {
        $this->stmt->execute();
    }

    public function resultset()
    {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function single()
    {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function rowCount()
    {
        return $this->stmt->rowCount();
    }

    public function __destruct()
    {
        $this->stmt = null;
    }
}

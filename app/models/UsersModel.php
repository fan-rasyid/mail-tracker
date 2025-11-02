<?php
require_once __DIR__ . "/../cores/Model.php";

class UsersModel extends Model
{
    protected $table_name = "users";
    protected $primary_key = "id_user";
    protected $field_name = [
        "username",
        "password",
        "name",
        "role"
    ];

    public function __construct()
    {
        parent::__construct(); // Call parent constructor to establish DB connection
        $this->setTableName($this->table_name);
        $this->setPrimaryKey($this->primary_key);
        $this->setFieldName($this->field_name);
    }

    public function getUser($username, $password)
    {
        try {
            $sql = "SELECT * FROM {$this->table_name} WHERE username = :username LIMIT 1";
            $this->query($sql);
            $this->bind(':username', $username);
            $this->execute();

            if ($this->rowCount() > 0) {
                $user = $this->single();

                //password hash/md5
                if (md5($password) == $user['password']) {
                    unset($user['password']);
                    return ['success' => true, 'user' => $user];
                } else {
                    return ['success' => false, 'message' => 'Invalid Password'];
                }
            } else {
                return ['success' => false, 'message' => 'User not found'];
            }
        } catch (Exception $e) {
            error_log('Login Error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Database error occurred.'];
        }
    }

    public function createUser($data)
    {
        try {
            $checksql = "SELECT * FROM {$this->table_name} WHERE username = :username";
            $this->query($checksql);
            $this->bind(':username', $data['username']);
            $user = $this->single($checksql);

            if ($user) {
                return ['success' => false, 'message' => 'Username already exists.'];
            }

            $sql = "INSERT INTO {$this->table_name} (username, password, name, role) 
                    VALUES (:username, :password, :name, :role)";
            $this->query($sql);
            $this->bind(':username', $data['username']);
            $this->bind(':password', md5($data['password'])); // Hash the password
            $this->bind(':name', $data['name']);
            $this->bind(':role', 'user');
            $this->execute();
            // var_dump($data);die;

            return ['success' => true, 'message' => 'User created successfully.', 'user' => $data];
            // return $this
        } catch (Exception $e) {
            error_log('Create User Error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Database error occurred while creating user.'];
        }

    }

    public function getDataById($id)
    {
        return parent::getDataById($id);
    }

    public function updateUser($data)
    {
        return $this->update($data);
    }

    public function deleteUser($id)
    {
        return $this->deleteData($id);
    }

    public function getAllUsers()
    {
        return $this->getAllData();
    }

}

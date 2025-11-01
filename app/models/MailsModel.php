<?php

require_once __DIR__ . "/../cores/Model.php";

class MailsModel extends Model
{

    protected $table_name = "mails";
    protected $primary_key = "id_mail";
    protected $field_name = [
        "id_user",
        "date",
        "sender",
        "subject",
        "recepient",
        "type",
        "type_mail",
        "file"
    ];

    public function __construct()
    {
        parent::__construct(); // Call parent constructor to establish DB connection
        $this->setTableName($this->table_name);
        $this->setPrimaryKey($this->primary_key);
        $this->setFieldName($this->field_name);
    }

    public function getAllMails()
    {
        return $this->getAllData();
    }

    public function getDetail($id)
    {
        return $this->getDataById($id);
    }

    public function createNewMail($data)
    {
        return $this->create($data);
    }

    public function updateMail($data)
    {
        return $this->update($data);
    }

    public function deleteMail($id)
    {
        return $this->deleteData($id);
    }

    public function getIncomingMails()
    {
        $sql = "SELECT * FROM {$this->table_name} WHERE type = 'in'";

        $this->query($sql);

        return $this->resultset();
    }

    public function getOutgoingMails()
    {
        $sql = "SELECT * FROM {$this->table_name} WHERE type = 'out'";

        $this->query($sql);

        return $this->resultset();
    }

}
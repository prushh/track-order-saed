<?php
class Status
{
    private $conn;
    private $table_name = "status";
    // table's fields
    public $id;
    public $title;
    public $description;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    /* GET all status */
    public function get_all()
    {
        $query = "SELECT *
                  FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    /* GET status by id */
    public function get_by_id($id)
    {
        $query = "SELECT *
                  FROM " . $this->table_name . "
                  WHERE id = " . $id;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}

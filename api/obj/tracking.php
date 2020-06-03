<?php
class Tracking
{
    private $conn;
    private $table_name = "trackings";
    // table's fields
    public $id;
    public $courier;
    public $status_id;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    /* GET all trackings */
    public function get_all()
    {
        $query = "SELECT *
                  FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}

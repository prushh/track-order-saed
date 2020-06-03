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

    /* ADD one tracking */
    public function add()
    {
        $query = "INSERT INTO " . $this->table_name . " VALUES (:id, :courier, :status_id);";
        $stmt = $this->conn->prepare($query);
        // sanitize
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->courier = htmlspecialchars(strip_tags($this->courier));
        $this->status_id = htmlspecialchars(strip_tags($this->status_id));
        // binding parametri
        $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":courier", $this->courier, PDO::PARAM_STR, 3);
        $stmt->bindParam(":status_id", $this->status_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /* DELETE one tracking */
    public function delete()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        // sanitize
        $this->id = htmlspecialchars(strip_tags($this->id));
        // binding parametri
        $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                return true;
            }
        }
        return false;
    }
}

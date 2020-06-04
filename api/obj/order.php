<?php
class Order
{
    private $conn;
    private $table_name = "orders";
    // table's fields
    public $id;
    public $n_items;
    public $total_cost;
    public $order_date;
    public $ship_date;
    public $delivery_date;
    public $user_id;
    public $tracking_id;
    // user's fields
    public $name;
    public $surname;
    public $email;
    public $address;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    /* GET all orders */
    public function get_all()
    {
        $query = "SELECT *
                  FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    /* GET all orders by user_id */
    public function get_all_by_user_id($user_id)
    {
        $query = "SELECT *
                  FROM " . $this->table_name . "
                  WHERE orders.user_id = " . $user_id . ";";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    /* GET all orders with tracking_id by user_id */
    public function get_all_with_track_by_user_id($user_id)
    {
        $query = "SELECT *
                  FROM " . $this->table_name . "
                  WHERE orders.user_id = " . $user_id . " AND
                        orders.tracking_id IS NOT NULL;";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    /* GET all orders with user information */
    public function get_all_with_user_info()
    {
        $query = "SELECT *
                  FROM " . $this->table_name . " INNER JOIN users ON user_id = users.id;";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}

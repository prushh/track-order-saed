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

    /* GET all orders with user information */
    public function get_all_with_user_info()
    {
        $query = "SELECT orders.id, n_items, total_cost, order_date, ship_date, delivery_date,
                         user_id, tracking_id, name, surname, email, address
                  FROM " . $this->table_name . " INNER JOIN users ON user_id = users.id;";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    /* GET order details by user_id */
    public function get_by_user_id($order_id)
    {
        $query = "SELECT *
                      FROM " . $this->table_name . "
                      WHERE id = " . $order_id . ";";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    /* UPDATE tracking_id */
    public function update_tracking()
    {
        $query = "UPDATE " . $this->table_name . " SET tracking_id = :tracking_id WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        // sanitize
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->tracking_id = htmlspecialchars(strip_tags($this->tracking_id));
        // binding parametri
        $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":tracking_id", $this->tracking_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /* ADD one order */
    public function add()
    {
        $query = "INSERT INTO " . $this->table_name . " (id, n_items, total_cost, order_date, user_id)
                  VALUES (default, :n_items, :total_cost, :order_date, :user_id);";
        $stmt = $this->conn->prepare($query);
        // sanitize
        $this->n_items = htmlspecialchars(strip_tags($this->n_items));
        $this->total_cost = htmlspecialchars(strip_tags($this->total_cost));
        $this->order_date = htmlspecialchars(strip_tags($this->order_date));
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        // binding parametri
        $stmt->bindParam(":n_items", $this->n_items, PDO::PARAM_INT);
        $stmt->bindParam(":total_cost", $this->total_cost, PDO::PARAM_INT);
        $date = date("Y-m-d", strtotime($this->order_date));
        $stmt->bindParam(":order_date", $date, PDO::PARAM_STR);
        $stmt->bindParam(":user_id", $this->user_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /* DELETE one order */
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

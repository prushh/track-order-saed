<?php

class Shipping{

    private $conn;
    public $user_id;
    public $order_id;
    public $status_id;
    public $tracking_id;
    public $courier;

    // costruttore
    public function __construct($db)
    {
        $this->conn = $db;
    }

    /* GET all orders with tracking_id (admin side) */
    public function get_all_orders_with_tracking_admin(){
        $query = "SELECT orders.id, orders.total_cost, orders.order_date, orders.ship_date,
                  orders.delivery_date, orders.user_id, users.address, users.telephone,
                  orders.tracking_id, status.title AS status
                  FROM orders INNER JOIN users ON orders.user_id = users.id
                  INNER JOIN trackings ON orders.tracking_id = trackings.id
                  INNER JOIN status ON trackings.status_id = status.id
                  WHERE orders.tracking_id IS NOT NULL;";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    /* GET all orders without tracking_id (admin side) */
    public function get_all_orders_without_tracking_admin(){
        $query = "SELECT orders.id, orders.n_items, orders.total_cost, orders.order_date,
                         users.name, users.surname, users.email, users.address
                  FROM orders INNER JOIN users ON orders.user_id = users.id
                  WHERE orders.tracking_id IS NULL;";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    /* GET all orders without tracking_id by user_id (admin side) */
    public function get_all_orders_without_tracking_by_user_admin($user_id){
        $query = "SELECT orders.id, orders.total_cost, orders.order_date, orders.user_id,
                  users.address, users.telephone
                  FROM orders INNER JOIN users ON orders.user_id = users.id
                  WHERE orders.user_id = " . $user_id . "
                  AND orders.tracking_id IS NULL;";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    /* GET order details by order_id */
    public function get_order_details_by_order_id($order_id){
        $query = "SELECT orders.id, orders.n_items, orders.n_items, orders.order_date,
                  orders.ship_date, orders.delivery_date, orders.tracking_id,
                  status.title, status.description
                  FROM orders 
                  INNER JOIN trackings ON orders.tracking_id = trackings.id
                  INNER JOIN status ON trackings.status_id = status.id
                  WHERE orders.id = " . $order_id . ";";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    /* GET all title for status (fill combobox) */
    public function get_all_status(){
        $query = "SELECT status.id, status.title, status.description FROM status;";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    /* UPDATE status for trackings by tracking_id and status_id */
    public function update_tracking($status_id,$tracking_id){
        $query = "UPDATE trackings
                  SET status_id = " . $status_id . "
                  WHERE trackings.id = " . $tracking_id . ";";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    /* INSERT new tracking by order_id and tracking_id */
    public function insert_tracking($courier,$order_id){
        $query = "INSERT INTO trackings VALUES (default, $courier, 1);
                  UPDATE orders
                  SET orders.tracking_id = (
                    /* GET last id in trackings */
                    SELECT MAX(trackings.id)
                    FROM trackings
                  )
                  WHERE orders.id = " . $order_id . ";";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    /* DELETE tracking_id by order_id and tracking_id */
    public function delete_tracking($tracking_id,$order_id){
        $query = "UPDATE orders
                  SET orders.tracking_id = NULL
                  WHERE orders.id = " . $order_id . " AND
                  orders.tracking_id = " . $tracking_id . ";";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $query2 = "DELETE FROM trackings WHERE trackings.id = ". $tracking_id .";";
        $stmt2 = $this->conn->prepare($query2);
        $stmt2->execute();

        return [$stmt,$stmt2];
    }


}
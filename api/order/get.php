<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once "../utils.php";

if ($_SERVER['REQUEST_METHOD'] != 'GET') {
    bad_request();
}

require_once "../../database/connection.php";
require_once '../obj/order.php';

$db = new Database();
$conn = $db->openConnection();

$order = new Order($conn);

$stmt = NULL;

// Pensare come gestire le query con più tabelle coinvolte
if (isset($_GET['user_id']) && !isset($_GET['token'])) {
    if (!empty($_GET['user_id'])) {
        $stmt = $order->get_all_by_user_id($_GET['user_id']);
    } else {
        bad_request();
    }
} else if (isset($_GET['token'])) {
    if (!empty($_GET['token'])) {
        // Simulate token
        $stmt = $order->get_all_with_user_info();
    } else {
        bad_request();
    }
} else {
    $stmt = $order->get_all($conn);
}

if ($stmt->rowCount() > 0) {

    $order_arr = array();
    $order_arr["results"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $order_item = array(
            "id" => $id,
            "n_items" => $n_items,
            "total_cost" => $total_cost,
            "order_date" => $order_date,
            "ship_date" => $ship_date,
            "delivery_date" => $delivery_date,
            "user_id" => $user_id,
            "tracking_id" => $tracking_id
        );

        if (isset($_GET['token'])) {
            $user_item = array(
                "name" => $name,
                "surname" => $surname,
                "email" => $email,
                "address" => $address
            );

            $order_item = array_merge($order_item, $user_item);
        }

        array_push($order_arr["results"], $order_item);
    }

    http_response_code(200);

    print json_encode($order_arr);
} else {
    http_response_code(404);
    print json_encode(array("message" => "Nessun ordine trovato"));
}

<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once "../utils.php";

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    bad_request();
}

require_once "../database/connection.php";
require_once '../obj/order.php';

$db = new Database();
$conn = $db->openConnection();

$order = new Order($conn);

$data = json_decode(file_get_contents("php://input"));

if (
    !empty($data->n_items) &&
    !empty($data->total_cost) &&
    !empty($data->order_date) &&
    !empty($data->user_id)
) {
    $order->n_items = $data->n_items;
    $order->total_cost = $data->total_cost;
    $order->order_date = $data->order_date;
    $order->user_id = $data->user_id;


    if ($order->add()) {
        http_response_code(201);
        echo json_encode(array("message" => "Ordine aggiunto."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Errore di connessione."));
    }
} else {
    bad_request();
}

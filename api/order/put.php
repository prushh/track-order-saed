<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once "../utils.php";

if ($_SERVER['REQUEST_METHOD'] != 'PUT') {
    bad_request();
}

require_once "../../database/connection.php";
require_once '../obj/order.php';

$db = new Database();
$conn = $db->openConnection();

$order = new Order($conn);

$data = json_decode(file_get_contents("php://input"));

if (
    !empty($data->id) &&
    !empty($data->n_items) &&
    !empty($data->total_cost) &&
    !empty($data->order_date) &&
    !empty($data->user_id)
) {
    $order->id = $data->id;
    $order->n_items = $data->n_items;
    $order->total_cost = $data->total_cost;
    $order->order_date = $data->order_date;
    $order->user_id = $data->user_id;

    if ($order->update()) {
        http_response_code(200);
        echo json_encode(array("message" => "Ordine aggiornato con successo."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Ordine inesistente o protetto."));
    }
} else if (!empty($data->id) && !empty($data->tracking_id)) {
    $order->id = $data->id;
    $order->tracking_id = $data->tracking_id;

    if ($order->update_tracking()) {
        http_response_code(200);
        echo json_encode(array("message" => "Tracking aggiornato con successo."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Tracking inesistente o protetto."));
    }
} else {
    bad_request();
}

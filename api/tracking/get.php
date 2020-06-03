<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once "../utils.php";

if ($_SERVER['REQUEST_METHOD'] != 'GET') {
    bad_request();
}

require_once "../../database/connection.php";
require_once '../obj/tracking.php';

$db = new Database();
$conn = $db->openConnection();

$tracking = new Tracking($conn);

$stmt = $tracking->get_all();

if ($stmt->rowCount() > 0) {

    $tracking_arr = array();
    $tracking_arr["results"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $tracking_item = array(
            "id" => $id,
            "courier" => $courier,
            "status_id" => $status_id
        );

        array_push($tracking_arr["results"], $tracking_item);
    }

    http_response_code(200);

    print json_encode($tracking_arr);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "Nessun tracking trovato"));
}

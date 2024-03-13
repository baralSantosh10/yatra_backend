<?php
session_start();
include "../database/db.php";
include "../database/helper.php";

// Check if the token is sent in the request body
if (!isset($_POST['token'])) {
    echo json_encode(
        array(
            "success" => false,
            "message" => "Token is required"
        )
    );
    die();
}


$token = $_POST['token'];


$userId = getUserId($token);
if ($userId === null) {
    error_log("No user found with the provided token: " . $token);
    return null;
}

if (!$userId) {
    echo json_encode(
        array(
            "success" => false,
            "message" => "Invalid token"
        )
    );
    die();
}

// Check if all required fields for event creation are present
$requiredFields = ['event_title', 'description', 'date', 'time', 'vip_seat_capacity', 'regular_seat_capacity', 'price_per_vip', 'price_per_regular', 'organizer_id', 'venue_id', 'category_id'];

foreach ($requiredFields as $field) {
    if (!isset($_POST[$field]) || empty($_POST[$field])) {
        echo json_encode(["success" => false, "message" => "Required field '$field' is missing"]);
        exit;
    }
}


// Process the event creation
// Sanitize and escape input data to prevent SQL injection
$event_title = mysqli_real_escape_string($conn, $_POST['event_title']);
$description = mysqli_real_escape_string($conn, $_POST['description']);
$date = mysqli_real_escape_string($conn, $_POST['date']);
$time = mysqli_real_escape_string($conn, $_POST['time']);
$vip_seat_capacity = intval($_POST['vip_seat_capacity']);
$regular_seat_capacity = intval($_POST['regular_seat_capacity']);
$price_per_vip = floatval($_POST['price_per_vip']);
$price_per_regular = floatval($_POST['price_per_regular']);
$organizer_id = intval($_POST['organizer_id']);
$venue_id = intval($_POST['venue_id']);
$category_id = intval($_POST['category_id']);

// Insert the event into the database
$sql = "INSERT INTO Events (title, description, date, time, vip_seat_capacity, regular_seat_capacity, pricePerVIP, pricePerRegular, organizerId, venueId, catId)
        VALUES ('$event_title', '$description', '$date', '$time', $vip_seat_capacity, $regular_seat_capacity, $price_per_vip, $price_per_regular, $organizer_id, $venue_id, $category_id)";

$result = mysqli_query($conn, $sql);

if ($result) {
    echo json_encode(
        array(
            "success" => true,
            "message" => "Event added successfully"
        )
    );
} else {
    echo json_encode(
        array(
            "success" => false,
            "message" => "Failed to add event"
        )
    );
}
?>
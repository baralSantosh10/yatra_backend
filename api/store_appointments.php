<?php
// Assuming you have already established a database connection

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the JSON content sent in the request body
    $json_data = file_get_contents("php://input");

    // Decode the JSON data into an associative array
    $data = json_decode($json_data, true);

    // Extract event data from the JSON
    $title = $data['title'];
    $description = $data['description'];
    $event_image = $data['event_image'];
    $date = $data['date'];
    $time = $data['time'];
    $vip_seat_capacity = $data['vip_seat_capacity'];
    $regular_seat_capacity = $data['regular_seat_capacity'];
    $price_per_vip = $data['price_per_vip'];
    $price_per_regular = $data['price_per_regular'];
    $venue_photo = $data['venue_photo'];
    $location = $data['location'];
    $category_id = $data['category_id']; // Assuming you receive category ID

    // Prepare and execute the SQL query to insert the event into the database
    $sql = "INSERT INTO Events (title, description, image, date, time, vipSeatCapacity, regularSeatCapacity, 
            pricePerVIP, pricePerRegular, venuePhoto, venueId, organizerId, catId) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, (SELECT venueId FROM Venue WHERE location = ?), 
            (SELECT user_id FROM Organizers join Users WHERE user_id = ?), ?)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $title,
        $description,
        $event_image,
        $date,
        $time,
        $vip_seat_capacity,
        $regular_seat_capacity,
        $price_per_vip,
        $price_per_regular,
        $venue_photo,
        $location,
        $user_id,
        $category_id
    ]);

    // Check if the event was successfully inserted
    if ($stmt->rowCount() > 0) {
        // Event inserted successfully
        $response = array("message" => "Event added successfully");
        echo json_encode($response);
    } else {
        // Failed to insert event
        $response = array("message" => "Failed to add event");
        echo json_encode($response);
    }
} else {
    // Request method is not POST
    $response = array("message" => "Invalid request method");
    echo json_encode($response);
}
?>
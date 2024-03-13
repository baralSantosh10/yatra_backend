<?php
// Connect to the database (include the connection code from step 1)
include('../database/db.php');

// SQL query to select locations
$sql = "SELECT location FROM venue";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Array to hold locations
    $locations = array();

    // Fetch locations from the result set
    while ($row = $result->fetch_assoc()) {
        // Add location to the array
        $locations[] = array('name' => $row['location']);
    }

    // Convert the array to JSON and output the response
    header('Content-Type: application/json');
    echo json_encode($locations);
} else {
    // No locations found
    echo json_encode(array('message' => 'No locations found'));
}

// Close the database connection
$conn->close();
?>
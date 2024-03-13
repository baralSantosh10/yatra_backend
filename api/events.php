<?php
include('../database/db.php'); // Include your database connection file
include('../global.php'); // Include global variables/constants file

// Query to retrieve event data along with available tickets
$queryEvents = "SELECT DISTINCT Events.eventId, Events.title, Events.description, Events.date, Venue.location, EventCategory.categoryName, Events.image,
                (SELECT SUM(quantity) FROM EventTickets WHERE eventId = Events.eventId AND categoryId = (SELECT categoryId FROM TicketCategories WHERE categoryName = 'VIP')) AS total_vip_seats,
                (SELECT SUM(quantity) FROM EventTickets WHERE eventId = Events.eventId AND categoryId = (SELECT categoryId FROM TicketCategories WHERE categoryName = 'Regular')) AS total_regular_seats,
                (SELECT COALESCE(SUM(quantity), 0) FROM ReservedTickets WHERE eventId = Events.eventId AND categoryName = 'VIP') AS reserved_vip_seats,
                (SELECT COALESCE(SUM(quantity), 0) FROM ReservedTickets WHERE eventId = Events.eventId AND categoryName = 'Regular') AS reserved_regular_seats
                FROM Events
                JOIN Venue ON Events.venueId = Venue.venueId
                JOIN EventCategory ON Events.catId = EventCategory.catId";

$resultEvents = $conn->query($queryEvents);

if (!$resultEvents) {
    // Error handling: Display SQL error message if query fails
    echo json_encode(['status' => 'error', 'message' => $conn->error]);
} elseif ($resultEvents->num_rows > 0) {
    // Initialize an array to store events data
    $events = array();

    // Loop through each row of the result set
    while ($row = $resultEvents->fetch_assoc()) {
        // Calculate available seats
        $available_vip_seats = intval($row['total_vip_seats']) - intval($row['reserved_vip_seats']);
        $available_regular_seats = intval($row['total_regular_seats']) - intval($row['reserved_regular_seats']);

        // Initialize an array to store event details
        $event = array(
            'eventId' => $row['eventId'],
            'title' => $row['title'],
            'description' => $row['description'],
            'date' => $row['date'],
            'location' => $row['location'],
            'categoryName' => $row['categoryName'],
            'image' => $row['image'],
            'totalVipSeats' => $row['total_vip_seats'],
            'totalRegularSeats' => $row['total_regular_seats'],
            'reservedVipSeats' => $row['reserved_vip_seats'],
            'reservedRegularSeats' => $row['reserved_regular_seats'],
            'availableVipSeats' => $available_vip_seats,
            'availableRegularSeats' => $available_regular_seats
        );

        // Add event details to the events array
        $events[] = $event;
    }

    // Return events data as JSON response
    echo json_encode([
        'status' => 'success',
        'events' => $events
    ]);
} else {
    // No events found
    echo json_encode(['status' => 'error', 'message' => 'No events found.']);
}

// Close the database connection
$conn->close();
?>
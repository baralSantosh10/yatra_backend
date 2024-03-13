<?php
// Include database connection
include('../database/db.php'); // Adjust path as per your file structure

// Prepare and execute the query to retrieve categories data
$queryCategories = "SELECT catId, categoryName FROM eventcategory";
$resultCategories = $conn->query($queryCategories);

if ($resultCategories->num_rows > 0) {
    $categories = array();
    while ($row = $resultCategories->fetch_assoc()) {
        $categories[] = array(
            'catId' => $row['catId'],
            'categoryName' => $row['categoryName'],
        );
    }

    // Return categories data as JSON response
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'success',
        'categories' => $categories
    ]);
} else {
    // No categories found
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'No categories found.']);
}

// Close the database connection
$conn->close();
?>
<?php
session_start();
if (!isset($_SESSION['userId'])) {
    header("Location: ../index.php");
    exit();
}

// Include database connection
include('../database/db.php');

// Check if doctor ID is provided in the URL
if (!isset($_GET['organizerId'])) {
    header("Location: organizers.php");
    exit();
}

$organizerId = $_GET['organizerId'];

// Delete the doctor and its associated user
$deleteSql = "DELETE organizers, users FROM organizers
              INNER JOIN users ON organizers.userId = users.userId
              WHERE organizers.id = $organizerId";
$conn->query($deleteSql);

header("Location: organizers.php");
exit();
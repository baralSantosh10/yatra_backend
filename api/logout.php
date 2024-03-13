<?php
include "../database/db.php";

if (isset($_POST['token'])) {
    $token = mysqli_real_escape_string($conn, $_POST['token']);

    // Assuming you have a table or mechanism to mark tokens as invalid
    $sql = "UPDATE tokens SET valid = 0 WHERE token = ?";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $token);
        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(["success" => true, "message" => "Logged out successfully"]);
        } else {
            echo json_encode(["success" => false, "message" => "Logout failed"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Query preparation failed"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Token is required"]);
}
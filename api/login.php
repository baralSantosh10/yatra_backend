<?php
session_start();
include "../database/db.php";

if (isset($_POST['email'], $_POST['password'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password']; // Password will be hashed, no need for real escape

    $sql = "SELECT * FROM users WHERE email = ?";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            if (password_verify($password, $user['password'])) {
                // Generate token, insert adnd respond success
                $token = bin2hex(random_bytes(16));
                $insertSql = "REPLACE INTO api_tokens (user_id, token) VALUES (?, ?)";
                $insertStmt = mysqli_prepare($conn, $insertSql);
                mysqli_stmt_bind_param($insertStmt, "is", $user['id'], $token); // Assuming 'id' is the user's ID column
                mysqli_stmt_execute($insertStmt);
                // Assume successful token insertion code here
                echo json_encode(["success" => true, "message" => "Login successful", "token" => $token, "userType" => $user['userType']]);
            } else {
                echo json_encode(["success" => false, "message" => "Incorrect password"]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "User not found"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Query preparation failed"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Email and password are required"]);
}

<?php

include "db.php";


function getUserId($token)
{
    global $conn;
    $sql = "SELECT user_id FROM api_tokens WHERE token = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $token);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $user = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if ($user === null) {
        return null; // Return null early if no user found
    }

    return $user['user_id'];
}

function isAdmin($token)
{
    $userId = getUserId($token);

    if (!$userId) {
        return false;
    }
    global $conn;
    $sql = "select userType from users where userId = '$userId'";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        return false;
    }
    $user = mysqli_fetch_assoc($result);
    $userRole = $user['userType'];

    return $userRole == "admin";
}
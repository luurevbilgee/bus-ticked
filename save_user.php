<?php
session_start();
include ('db_connect.php');

extract($_POST);
$grant = "";
appendPermission($grant, $delete);
appendPermission($grant, $insert);
appendPermission($grant, $update);
appendPermission($grant, $select);
echo $grant;
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
$data = " name = '$name' ";
$data .= ", username = '$username' ";
$data .= ", password = '$hashed_password' ";

// // $grants = [$delete, $update, $select, $insert];

// foreach ($grants as $value) {
//     if ($value != '') {
//         $grant[] = $value;
//     }
// }


// $grant = implode(', ', $grantPairs);
// echo $grant;

if (empty($id)) {
    $insert = $conn->query("INSERT INTO users set " . $data);
    $user_query = "CREATE USER '$username'@'localhost' IDENTIFIED BY 
    '$hashed_password' PASSWORD EXPIRE INTERVAL 180 DAY";
    $user_result = $conn->query($user_query);
    $grant_query = "GRANT $grant ON bus_booking.* TO  '$username'@'localhost'";
    $grant_result = $conn->query($grant_query);
    if ($insert) {
        echo 1; // Successful insertion
    } else {
        echo 0; // Error
    }
} else {
    $update = $conn->query("UPDATE users set " . $data . " where id =" . $id);
    if ($update) {
        echo 1; // Successful update
    } else {
        echo 0; // Error
    }
}
function appendPermission(&$grant, $permission)
{
    if ($permission != '') {
        if ($grant != "") {
            $grant .= ", ";
        }
        $grant .= $permission;
    }
}
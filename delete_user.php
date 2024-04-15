<?php

include ('db_connect.php');
extract($_POST);
$remove = $conn->query("DELETE FROM users where users.id =" . $id);
$grant_remove = $conn->query("DELETE FROM user WHERE user.Host = 'localhost' AND `user`.`User` =" . $username);
if ($remove)
	echo 1;
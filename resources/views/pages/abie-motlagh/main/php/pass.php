<?php

session_start();
include_once "/home/hounaarc/abie-motlagh.hounaar.com/main/php/connection.php";

$id = mysqli_real_escape_string($connection,$_POST['forget_pass_id']);
$pass = mysqli_real_escape_string($connection,$_POST['forget_pass_password']);
$repeat = mysqli_real_escape_string($connection,$_POST['forget_pass_repeat']);

if($pass === $repeat){
    $query = $connection->query("SELECT * FROM users WHERE username = '{$id}'");
    if($query->num_rows>0){
    $update = $connection->query("UPDATE users SET password = '{$pass}' WHERE username = '{$id}'");
    if($update){
        echo "success";
    } else {
        echo 'Something is blocking the server';
    }
} else {
    echo 'متاسفانه نام کاربری یافت نشد';
}
} else {
    echo 'رمز عبور با تکرار آن یکسان نیست';
}




?>
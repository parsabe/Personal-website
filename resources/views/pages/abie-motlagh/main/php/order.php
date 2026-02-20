<?php

session_start();
include_once "/home/hounaarc/abie-motlagh.hounaar.com/main/php/connection.php";

    $anon_id = $_SESSION['anon_id'];
    $fname = mysqli_real_escape_string($connection,$_POST['fname']);
    $lname = mysqli_real_escape_string($connection,$_POST['lname']);
    $email = mysqli_real_escape_string($connection,$_POST['user_mail']);
    $phone = mysqli_real_escape_string($connection,$_POST['phone']);
    $address = mysqli_real_escape_string($connection,$_POST['user_add']);
    $postal_code = mysqli_real_escape_string($connection,$_POST['postal_code']);
    $order_name = mysqli_real_escape_string($connection,$_POST['order_name']);
    $author_name = mysqli_real_escape_string($connection,$_POST['author_name']);
    $date = date("Y-m-d H:i:s");

    if(filter_var($email,FILTER_VALIDATE_EMAIL)){
        $query = $connection->query("INSERT INTO orders VALUES ('{$anon_id}','{$fname}','{$lname}','{$email}','{$phone}',
        '{$address}','{$postal_code}','{$order_name}','{$author_name}', '{$date}')");
        if($query){
            echo 'success';
        } else {
            echo 'Something went wrong, try again later';
        }
    
    
    } else {
        echo 'لطفا ایمیلی معتبر وارد نمایید.';
    }





?>
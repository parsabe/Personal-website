<?php

include_once "/home/hounaarc/abie-motlagh.hounaar.com/main/php/connection.php";

if(isset($_POST['address'])){
    $add = mysqli_real_escape_string($connection,$_POST['address']);
    $date = date("Y-m-d H:i:s");
    if(filter_var($add,FILTER_VALIDATE_EMAIL)){
        $checker = $connection->query("SELECT * FROM news WHERE email = '{$add}'");
        if($checker->num_rows>0){
            echo "ایمیل شما قبلا در خبرنامه ثبت شده است.";
        } else {
            $query = $connection->query("INSERT INTO news VALUES ('{$add}')");
            if($query){
                echo "success";
            } else {
                echo "مشکلی در سرور پیش آمده است. لطفا مدتی بعد تلاش کنید.";
            }
        }
    } else {
        echo "لطفا ایمیلی معبتر را وارد نمایید";
    }
}


?>
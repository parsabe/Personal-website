<?php 


include_once "/home/hounaarc/abie-motlagh.hounaar.com/main/php/connection.php";
if(isset($_POST['name']) && isset($_POST['email']) && isset($_POST['subject']) && isset($_POST['msg'])){
    $name = mysqli_real_escape_string($connection,$_POST['name']);
    $email = mysqli_real_escape_string($connection,$_POST['email']);
    $subject = mysqli_real_escape_string($connection,$_POST['subject']);
    $message = mysqli_real_escape_string($connection,$_POST['msg']);
    $date = date("Y-m-d H:i:s");

    if(filter_var($email,FILTER_VALIDATE_EMAIL)){
        $query = $connection->query("INSERT INTO contact VALUES ('{$name}','{$email}','{$subject}','{$message}','{$date}')");
        if($query){
            echo "موفقیت";
        } else {
                echo "مشکلی در سرور به وجود آمده است. لطفا مدتی بعد تلاش کنید.";
            }
        } else {
            echo "در فرایند ثبت مشکلی وجود دارد. لطفا مدتی بعد تلاش کنید.";
        }
   
    } else {
        echo "لطفا یک ایمیل معبتر وارد نمایید";
    }





?>


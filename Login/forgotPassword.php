<?php
    session_start();
    if(isset($_REQUEST['submitBtn'])){
        unset($_REQUEST['submitBtn']);
        $validate = "";
        $email = $_REQUEST['email'];
        if($email == ""){
            $validate = "is-invalid";
            $error = "Please enter Email/Enrollment";
        }
        else{
            $conn = new mysqli("localhost", "root", "", "project");
            $result = $conn -> query("SELECT * FROM user where (enrollmentNo='$email' OR email='$email');");
            $row = $result -> fetch_assoc();
            if(!$row){
                $validate = "is-invalid";
                $error = "Email/Enrollment does not exists";
                $_REQUEST['email'] = ""; 
            }
            else{
                $_SESSION['email']=$row['email'];
                $_SESSION['myID'] = "h";
                header('Location: ../Assets/scripts/sendmail.php');
            }
            $conn -> close();
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container-fluid mx-auto border p-5 m-5" style="width:25em;">
        <form action="" method="post" novalidate>
            <div class="mb-4 text-center">
                <img src="../Assets/image/logo.jpg" class="rounded text-center" alt="logo" height="100" width="100">
            </div>
            <div class="mb-5 text-center">
                <h4>Forgot Your Password?</h4>
            </div>
            <div class="mb-3">
                <p>Enter your Enrollment or email and we will send you a otp and by verifying it you can reset your password.</p>
            </div>
            <div class="mb-4">
                <input type="text" class="form-control <?php echo $validate?>" name="email" id="email" placeholder="Email or Enrollment">
                <div class="invalid-feedback">
                    <?php echo $error;?>
                </div>
            </div>
            <div class="mb-2 text-center">
                <button type="submit" class="btn btn-primary" style="width:10em;" name="submitBtn" >Send OTP</button>
            </div>
        </form> 
    </div>
</body>
</html>
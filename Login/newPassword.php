<?php
    session_start();
    if(!isset($_SESSION['email'])){
        header('Location: forgotPassword.php');
    }
    else{
        if(isset($_REQUEST['submitBtn'])){
            unset($_REQUEST['submitBtn']);
            $otpvalidate = $passwordvalidate = $cpasswordvalidate = "";
            $otperror = $passworderror = $cpassworderror = "";

            if(!isset($_COOKIE)){
                $otpvalidate = "is-invalid";
                $otperror = "OTP expired retry again";
            }
            else{
                $saltedEncodedOtp = $_COOKIE['myID'];
                $FirstPart =  substr($saltedEncodedOtp,0,3);
                $secondPart = substr($saltedEncodedOtp,9);
                
                $DecodeOtp = $FirstPart.$secondPart;
                $otp = hex2bin($DecodeOtp);

                if($_REQUEST['otp'] != $otp){
                    $otpvalidate = "is-invalid";
                    $otperror = "OTP does not match";
                    $_REQUEST['otp'] = "";
                }

                if (!filter_var($_REQUEST['password'], FILTER_VALIDATE_REGEXP, array( "options"=> array( "regexp" => "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[a-zA-Z\d@$!%*?&]{8,20}$/"))) ){
                    $passwordvalidate = "is-invalid";
                    $passworderror = "Password must contain one Caps,one Number,One symbol and must be longer than 8 digits";
                    $_REQUEST['password']="";
                }
                else{
                    if($_REQUEST['password'] != $_REQUEST['cpassword']){
                        $cpasswordvalidate = "is-invalid";
                        $cpassworderror = "Password does not match";
                        $_REQUEST['cpassword']="";
                    }
                    else{
                        $email = $_SESSION['email'];
                        $password = $_REQUEST['password'];
                        $conn = new mysqli("localhost", "root", "","project") or die("Connection failed: " . $conn->connect_error);
                        $conn->query("UPDATE user set password = '$password' where email = '$email'");
                        $conn->close();
                        header('Location: index.php');
                    }
                }
            }
        
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
            <div class="mb-4 text-center">
                <h4>Update Password</h4>
            </div>
            <div class="mb-4">
                <input type="text" class="form-control <?php echo $otpvalidate;?>" name="otp" id="otp" value="<?php if(!empty($_REQUEST['otp'])) echo $_REQUEST['otp'];?>" placeholder="OTP">
                <div class="invalid-feedback">
                    <?php echo $otperror;?>
                </div>
            </div>
            <div class="mb-4">
                <input type="text" class="form-control <?php echo $passwordvalidate;?>" name="password" value="<?php if(!empty($_REQUEST['password'])) echo $_REQUEST['password'];?>" id="password" placeholder="Password">
                <div class="invalid-feedback">
                    <?php echo $passworderror;?>
                </div>
            </div>
            <div class="mb-4">
                <input type="text" class="form-control <?php echo $cpasswordvalidate;?>" name="cpassword" value="<?php if(!empty($_REQUEST['cpassword'])) echo $_REQUEST['cpassword'];?>" id="cpassword" placeholder="Confirm Password">
                <div class="invalid-feedback">
                    <?php echo $cpassworderror;?>
                </div>
            </div>
            <div class="mb-2 text-center">
                <button type="submit" class="btn btn-primary" style="width:10em;" name="submitBtn" >Submit</button>
            </div>
        </form> 
    </div>
</body>
</html>
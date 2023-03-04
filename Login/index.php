<?php
    include('../Assets/scripts/login.php');
    if(isset($_SESSION["email"]) && isset($_SESSION["password"]) && isset($_COOKIE["email"]) && isset($_COOKIE["password"])){
        $email = $_COOKIE["email"];
        $password = $_COOKIE["password"];
        $conn = new mysqli("localhost", "root", "", "project");
        $result = $conn -> query("SELECT * FROM user where (enrollmentNo='$email' OR email='$email') and password='$password';");
        $row = $result -> fetch_assoc();
        $conn -> close();
        header('Location: ../'.$row['role']);
    }
    else{
        if(isset($_REQUEST['submitBtn'])){
            unset($_REQUEST['submitBtn']);
            $emailValidate = "";
            $passwordValidate = "";

            $emailInvalidMssg = "";
            $passwordInvalidMssg = "";
            if($_REQUEST['email'] == ""){
                $emailValidate = "is-invalid";
                $emailInvalidMssg = "Please enter Enrollment";
            }
            elseif($_REQUEST['password'] == ""){
                $passwordValidate = "is-invalid";
                $passwordInvalidMssg = "Please enter Password";
            }
            else{
                if(login(($_REQUEST['email']),($_REQUEST['password']))){
                    $row = login(($_REQUEST['email']),($_REQUEST['password']));    
                    header('Location: ../'.$row['role']);
                }
                else{
                    $emailValidate = "is-invalid";
                    $passwordValidate = "is-invalid";
                    $passwordInvalidMssg = "Either Enrollment/Email or Password is Wrong. Recheck !!!";
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
        <form class="row g-3 needs-validation" novalidate action="" method="post">
            <div class="mb-2 text-center">
                <img src="../Assets/image/logo.jpg" class="rounded text-center" alt="logo" height="100" width="100">
            </div>
            <div class="mb-2">
                <label for="enrollment" class="form-label">Enrollment No</label>
                <input type="text" class="form-control <?php echo $emailValidate;?>" name="email" id="enrollment" value="<?php if(!empty($_REQUEST['email'])) echo $_REQUEST['email'];?>" aria-describedby="enrollmentHelp">
                <div id="enrollmentHelp" class="form-text">If you are admin or faculty enter Email ID</div>
                <div class="invalid-feedback">
                    <?php echo $emailInvalidMssg;?>
                </div>
            </div>
            <div class="mb-1">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control <?php echo $passwordValidate;?>" name="password" id="password" value="<?php if(!empty($_REQUEST['password'])) echo $_REQUEST['password'];?>">
                <input type="checkbox" class="form-check-input" onclick="PasswordVisibility()"><span style="font-size:14px;"> Show Password</span>
                <div class="invalid-feedback" id = "passwordFeedback">
                    <?php echo $passwordInvalidMssg;?>
                </div>
            </div>
            <div class="mb-1">
                <a href="forgotPassword.php" style="float:right">Forgot Password</a>
            </div>
            <button type="submit" class="btn btn-primary" name="submitBtn" >Submit</button>
            <div class="mt-3 text-center">
                Don't have an account? <a href="registration.php">sign up</a>
            </div>
        </form> 
    </div>
</body>
    <script>
        function PasswordVisibility() {
            var password = document.getElementById("password");
            if (password.type === "password") {
                password.type = "text";
            } else {
                password.type = "password";
            }
            }   
    </script>
</html>
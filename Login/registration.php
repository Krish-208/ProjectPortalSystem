<?php
   if(isset($_REQUEST['submitBtn'])){
        unset($_REQUEST['submitBtn']);

        $namevalidate = $enrollvalidate = $mobilevalidate = $departmentvalidate = $semestervalidate = $emailvalidate = $passwordvalidate = "";
        $nameerror = $enrollerror = $mobileerror = $departmenterror = $semestererror = $emailerror = $passworderror = "";
        if($_REQUEST['uname'] == ""){
            $namevalidate = "is-invalid";
            $nameerror = "Enter your Name";
            $_REQUEST['uname'] = "";
        }
        elseif($_REQUEST['myrole'] == "Student" && $_REQUEST['enroll'] == ""){
            $enrollvalidate = "is-invalid";
            $enrollerror = "Enter your Enrollment Number";
            $_REQUEST['enroll'] = "";
        }
        elseif($_REQUEST['myrole'] == "Student" && !filter_var($_REQUEST['enroll'], FILTER_VALIDATE_REGEXP, array( "options"=> array( "regexp" => "/^[0-9]{15}$/"))) ){
            $enrollvalidate = "is-invalid";
            $enrollerror = "Enter valid Enrollment Number";
            $_REQUEST['enroll'] = "";
        }
        elseif($_REQUEST['mobile'] == ""){
            $mobilevalidate = "is-invalid";
            $mobileerror = "Enter your Mobile Number";
            $_REQUEST['mobile'] = "";
        }
        elseif(!filter_var($_REQUEST['mobile'], FILTER_VALIDATE_REGEXP, array( "options"=> array( "regexp" => "/^[6789]{1}.*[0-9]{9}$/"))) ){
            $mobilevalidate = "is-invalid";
            $mobileerror = "Enter valid Mobile Number";
            $_REQUEST['mobile'] = "";
        }
        elseif($_REQUEST['Department'] == "dept"){
            $departmentvalidate = "is-invalid";
            $departmenterror = "Please select your department";
            $_REQUEST['Department'] = "dept";
        }
        elseif($_REQUEST['myrole'] == "Student" && $_REQUEST['semester'] == "NA"){
            $semestervalidate = "is-invalid";
            $semestererror = "Please select your semester";
            $_REQUEST['semester'] = "NA";
        }
        elseif(!str_contains($_REQUEST['email'], "@") || !str_contains($_REQUEST['email'], ".") || str_contains($_REQUEST['email'], " ")){
            $emailvalidate = "is-invalid";
            $emailerror = "Please enter valid email";
            $_REQUEST['email'] = "";
        }
        elseif(!filter_var($_REQUEST['password'], FILTER_VALIDATE_REGEXP, array( "options"=> array( "regexp" => "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[a-zA-Z\d@$!%*?&]{8,20}$/"))) ){
            $passwordvalidate = "is-invalid";
            $passworderror = "Password must contain one Caps,one Number,One symbol and must be longer than 8 digits";
            $_REQUEST['password']="";
        }
        else{
            $enroll = "NA";
            $sem = "NA";

            $name = $_REQUEST['uname'];
            if(isset($_REQUEST['enroll'])){
                $enroll = $_REQUEST['enroll'];
            }
            $mobile = $_REQUEST['mobile'];
            $dept = $_REQUEST['Department'];
            if(isset($_REQUEST['semester'])){
                $sem = $_REQUEST['semester'];
            }
            $email = $_REQUEST['email'];
            $password = $_REQUEST['password'];
            $role = $_REQUEST['myrole'];
            $conn = new mysqli("localhost", "root", "","project") or die("Connection failed: " . $conn->connect_error);
            $result1 = $conn->query("select * from user where email = '$email'");
            if($result1->num_rows == 0){
                $conn->query("INSERT INTO user
                    VALUES ('$name', '$email', '$password', '$enroll', '$mobile', '$dept', '$sem', '$role', 'Not Verified');");
            }
            else{
                $emailvalidate = "is-invalid";
                $emailerror = "User already Registered";
                $_REQUEST['email'] = "";
            }
            $conn->close();
            header('Location: index.php');
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
    <div class="container-fluid mx-auto border m-5 p-5" style="width:25em;">
        <form action="" method="post" novalidate>
            <div class="mb-4 text-center">
                <img src="../Assets/image/logo.jpg" class="rounded text-center" alt="logo" height="100" width="100">
            </div>
            <div class="mb-4 text-center">
                <h4>Registration</h4>
            </div>
            <div class="mb-2">
                <div class="form-check form-check-inline">
                    <input type="radio" class="form-check-input" name="myrole" id="student" onchange="roleChange(this);" checked <?php if(!empty($_REQUEST['myrole']) && $_REQUEST['myrole'] == 'Faculty') echo 'checked';?> value="Student">
                    <label for="student" class="form-check-label">Student</label>
                </div>
                <div class="form-check form-check-inline">
                    <input type="radio" class="form-check-input" name="myrole" id="faculty" onchange="roleChange(this);" <?php if(!empty($_REQUEST['myrole']) && $_REQUEST['myrole'] == 'Faculty') echo 'checked';?> value="Faculty">
                    <label for="faculty" class="form-check-label">Faculty</label>
                </div>
            </div>
            <div class="mb-2">
                <input type="text" class="form-control <?php echo $namevalidate;?>" name="uname" value="<?php if(!empty($_REQUEST['uname'])) echo $_REQUEST['uname'];?>" placeholder="Name">
                <div class="invalid-feedback">
                    <?php echo $nameerror;?>
                </div>
            </div>
            <div class="mb-2">
                <input type="text" class="form-control <?php echo $enrollvalidate;?>" name="enroll" value="<?php if(!empty($_REQUEST['enroll'])) echo $_REQUEST['enroll'];?>" <?php if(!empty($_REQUEST['myrole']) && $_REQUEST['myrole'] == "Faculty") echo "disabled";?> placeholder="Enrollment Number" id="enroll">
                <div class="invalid-feedback">
                    <?php echo $enrollerror;?>
                </div>
            </div>
            <div class="mb-2">
                <input type="text" class="form-control <?php echo $mobilevalidate;?>" name="mobile" value="<?php if(!empty($_REQUEST['mobile'])) echo $_REQUEST['mobile'];?>" placeholder="Mobile Number">
                <div class="invalid-feedback">
                    <?php echo $mobileerror;?>
                </div>
            </div>
            <div class="mb-2">
                <select class="form-select <?php echo $departmentvalidate;?>" name="Department" aria-label="Default select example">
                    <option selected value="dept">Department</option>
                    <option <?php if(isset($_REQUEST['Department']) && $_REQUEST['Department'] == 'Automobile Engineering') echo 'selected';?> value="Automobile Engineering">Automobile Engineering</option>
                    <option <?php if(isset($_REQUEST['Department']) && $_REQUEST['Department'] == 'Civil Engineering') echo 'selected';?> value="Civil Engineering">Civil Engineering</option>
                    <option <?php if(isset($_REQUEST['Department']) && $_REQUEST['Department'] == 'Chemicals Engineering') echo 'selected';?> value="Chemicals Engineering">Chemicals Engineering</option>
                    <option <?php if(isset($_REQUEST['Department']) && $_REQUEST['Department'] == 'Electrical Engineering') echo 'selected';?> value="Electrical Engineering">Electrical Engineering</option>
                    <option <?php if(isset($_REQUEST['Department']) && $_REQUEST['Department'] == 'Computer Engineering') echo 'selected';?> value="Computer Engineering">Computer Engineering</option>
                    <option <?php if(isset($_REQUEST['Department']) && $_REQUEST['Department'] == 'Environmental Engineering') echo 'selected';?> value="Environmental Engineering">Environmental Engineering</option>
                    <option <?php if(isset($_REQUEST['Department']) && $_REQUEST['Department'] == 'Information Technology') echo 'selected';?> value="Information Technology">Information Technology</option>
                </select>
                <div class="invalid-feedback">
                    <?php echo $departmenterror;?>
                </div>
            </div>
            <div class="mb-2">
                <select class="form-select <?php echo $semestervalidate;?>" name="semester" <?php if(!empty($_REQUEST['myrole']) && $_REQUEST['myrole'] == "Faculty") echo "disabled";?> id="sem" aria-label="Default select example">
                    <option selected value="NA">Semester</option>
                    <option <?php if(isset($_REQUEST['semester']) && $_REQUEST['semester'] == '1') echo 'selected';?> value="1">1</option>
                    <option <?php if(isset($_REQUEST['semester']) && $_REQUEST['semester'] == '2') echo 'selected';?> value="2">2</option>
                    <option <?php if(isset($_REQUEST['semester']) && $_REQUEST['semester'] == '3') echo 'selected';?> value="3">3</option>
                    <option <?php if(isset($_REQUEST['semester']) && $_REQUEST['semester'] == '4') echo 'selected';?> value="4">4</option>
                    <option <?php if(isset($_REQUEST['semester']) && $_REQUEST['semester'] == '5') echo 'selected';?> value="5">5</option>
                    <option <?php if(isset($_REQUEST['semester']) && $_REQUEST['semester'] == '6') echo 'selected';?> value="6">6</option>
                    <option <?php if(isset($_REQUEST['semester']) && $_REQUEST['semester'] == '7') echo 'selected';?> value="7">7</option>
                    <option <?php if(isset($_REQUEST['semester']) && $_REQUEST['semester'] == '8') echo 'selected';?> value="8">8</option>
                </select>
                <div class="invalid-feedback">
                    <?php echo $semestererror;?>
                </div>
            </div>
            <div class="mb-2">
                <input type="text" class="form-control <?php echo $emailvalidate;?>" name="email" value="<?php if(!empty($_REQUEST['email'])) echo $_REQUEST['email'];?>" placeholder="Email Address">
                <div class="invalid-feedback">
                    <?php echo $emailerror;?>
                </div>
            </div>
            <div class="mb-5">
                <input type="password" class="form-control <?php echo $passwordvalidate;?>" name="password" value="<?php if(!empty($_REQUEST['password'])) echo $_REQUEST['password'];?>" id="password" placeholder="Password">
                <input type="checkbox" class="form-check-input" onclick="PasswordVisibility()"><span style="font-size:14px;"> Show Password</span>
                <div class="invalid-feedback">
                    <?php echo $passworderror;?>
                </div>
            </div>
            <div class="mb-2 text-center">
                <button type="submit" class="btn btn-primary" style="width:10em;" name="submitBtn" >Submit</button>
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
        function roleChange(role){
            var enrollment = document.getElementById('enroll')
            var sem = document.getElementById('sem')    
            if(role.value === "Faculty"){
                enroll.value = "NA"
                sem.value = "NA"
                enroll.setAttribute('disabled', '')
                sem.setAttribute('disabled', '')
                enroll.classList.remove("is-invalid")
                sem.classList.remove("is-invalid")
            }
            if(role.value === "Student"){
                enroll.value = ""
                sem.value = "NA"
                enroll.removeAttribute("disabled")
                sem.removeAttribute("disabled")
            }
        }
    </script>
</html>
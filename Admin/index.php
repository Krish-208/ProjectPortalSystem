<?php
    include('../Assets/scripts/login.php');

    $facultyData = array();
    $studentData = array();
    $conn = new mysqli("localhost", "root", "","project") or die("Connection failed: " . $conn->connect_error);
    $adminData = (isset($_COOKIE['email']))? login($_COOKIE['email'],$_COOKIE['password']) : login($_SESSION['email'],$_SESSION['password']);
    if(!$adminData){
        header('Location: ../Login');
    }
    if(isset($adminData) && $adminData['role'] != 'Admin'){
        header('Location: ../'.$adminData['role']);
    }
    
    $result1 = $conn->query("SELECT * FROM user where role !='Student' and role !='Admin' ORDER BY status DESC;");
    if($result1->num_rows > 0){
        while($row1 = $result1->fetch_assoc()){
            $facultyData[] = $row1;
        }
    }
    $result2 = $conn->query("SELECT * FROM user where role ='Student' and department = 'Automobile Engineering' and semester = '1' ORDER BY status DESC;");
    if($result1->num_rows > 0){
        while($row2 = $result2->fetch_assoc()) {
            $studentData[] = $row2;
        }
    }

    $departmentData = $conn->query("SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'user' AND COLUMN_NAME = 'department';")->fetch_assoc();
    $departmentDataStr = str_replace("enum(","",$departmentData['COLUMN_TYPE']);
    $departmentDataStr = str_replace("'","",$departmentDataStr);
    $departmentDataStr = str_replace(")","",$departmentDataStr);
    $departmentArray = explode(",",$departmentDataStr);
    $departmentArray = array_splice($departmentArray, 1);

    $rolevalidate = "";
    if(isset($_REQUEST['FsubmitBtn'])){
        unset($_REQUEST['FsubmitBtn']);
        if($_REQUEST['frole'] == "role"){
            $rolevalidate = "is-invalid";
        }
        else{
            $femail = $_REQUEST['facultyEmail'];
            $frole = $_REQUEST['frole'];
            $conn->query("UPDATE user SET status='Verified',role='$frole' where email='$femail';");
            header('Location: index.php');
        }
    }

    if(isset($_REQUEST['SsubmitBtn'])){
        $Semail = $_REQUEST['studentEmail'];
        $conn->query("UPDATE user SET status='Verified' where email='$Semail';");
        header('Location: index.php');
    }

    if(isset($_REQUEST['RemoveBtn'])){
        if(isset($_REQUEST['studentEmail'])){
            $email = $_REQUEST['studentEmail'];
        }
        if(isset($_REQUEST['facultyEmail'])){
            $email = $_REQUEST['facultyEmail'];
        }
        $conn->query("DELETE FROM user WHERE email='$email';");
        header('Location: index.php');
    }
    $conn->close();
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

    <style>
        .borderless-table td, .borderless-table th{
            border: none;
        }
        .hide {
            display: none;
            padding: 1em;
            background-color: #FAF8F1;
            color: black;
            border: 2px solid #EEEEEE;
            z-index: 1;
            border-radius: 5px;
            position: absolute;
            box-shadow: 0 0 5px #000000;
            border-radius:0.3em;
            margin-left: -1em;
            margin-top: -1em;
        }
        .hide:before {
            content: "";
            display: block;
            border-top: 14px solid transparent;
            border-bottom: 14px solid transparent;
            border-right: 14px solid #FFC3A1;
            position: absolute;
            left: -14px;
        }
    </style>
</head>
<body>
    <header class='container py-5'>
        <div class='container-fluid d-flex justify-content-around align-items-center'>
            <img src='../Assets/image/logo.jpg'>
            <p class="h1">Project Portal System</p>
            <div class="text-center">
                <a href="../Assets/scripts/logout.php">Logout</a><br>
                <p><?php echo $adminData['name']?></p>
            </div>
        </div>
    </header>
    <hr>
    <main class="container">
        <ul class="nav nav-tabs justify-content-center" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button"
                    role="tab" aria-controls="home" aria-selected="true">Home</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="student-tab" data-bs-toggle="tab" data-bs-target="#student" type="button"
                    role="tab" aria-controls="student" aria-selected="false">Student</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="faculty-tab" data-bs-toggle="tab" data-bs-target="#faculty" type="button"
                    role="tab" aria-controls="faculty" aria-selected="false">Faculty</button>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <div class="w-100 d-flex justify-content-center p-5">
                    <table class="table w-50">
                        <tr>
                            <th>Name</th>
                            <td><?php echo $adminData['name']?></td>
                        </tr>
                        <tr>
                            <th>Department</th>
                            <td><?php echo $adminData['department']?></td>
                        </tr>
                        <tr>
                            <th>Email Id</th>
                            <td><?php echo $adminData['email']?></td>
                        </tr>
                        <tr>
                            <th>Mobile Number</th>
                            <td><?php echo $adminData['mobileNo']?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>


        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade" id="student" role="tabpanel" aria-labelledby="student-tab">
                <div class="w-100 d-flex justify-content-center p-5">
                    <table class="table w-50 borderless-table" id = "tableBody">
                    <form action="" method="post">
                        <tr>
                            <th><label for="department" class="form-label">Department Name</label></th>
                            <td colspan="3">
                                <select class="form-select" name="department" id="department" onchange="semOrDepartmentChange()">
                                    <?php
                                        for ($i=0;$i<count($departmentArray);$i++){
                                            $value = $departmentArray[$i];
                                            echo "<option value='$value'>$value</option>";
                                        }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="No_Students" class="form-label">Total Students</label></th>
                            <td><input class="form-control" type="text" id="No_Students" disabled size="20" value="<?php echo count($studentData);?>"></td>
                            <th><label for="semester" class="form-label">Semester</label></th>
                            <td>
                                <select name="semester" class="form-select" id="semester" onchange="semOrDepartmentChange()">
                                    <?php
                                        for ($i=1;$i<=8;$i++){
                                            echo "<option value='$i'>$i</option>";
                                        }
                                    ?>
                                </select>    
                            </td>
                        </tr>


                        <tr class="text-center">
                            <th colspan="1">Student List</th>
                            <th colspan="2">Enrollment no</th>
                            <th colspan="1">Status</th>
                        </tr>
                        <?php
                            for($i=0;$i<count($studentData);$i++){
                                $name = $studentData[$i]['name'];
                                $enrollno = $studentData[$i]['enrollmentNo'];
                                $department = $studentData[$i]['department'];
                                $status = $studentData[$i]['status'];
                                $email = $studentData[$i]['email'];
                                $mobileNo = $studentData[$i]['mobileNo'];
                                $semester = $studentData[$i]['semester'];
                                $role = $studentData[$i]['role'];
                                $mystyle = "";
                                if($status == 'Not Verified'){
                                    $mystyle = "style='color:blue;'";
                                }
                                echo 
                                    "<tr class='text-center added-row'>
                                        <td colspan='1'>$name</td>
                                        <td colspan='2'>$enrollno</td>
                                        <td colspan='1' id = 'studentStatusdiv$i' onmouseover='studentShowDiv(this)' onmouseout='studentHideDiv(this)' $mystyle>$status</td>";
                                        
                                if($status == "Not Verified"){
                                    echo "<td><div class='hide text-start' id = 'studentDetails$i' onmouseout='hidemyDiv1(this)'>
                                            Name : $name<hr>Email: $email<hr>Mobile No: $mobileNo<hr>Department: $department<hr>Semester: $semester<hr>
                                            <input type='hidden' name='studentEmail' value='$email'>
                                            <button type='submit' class='btn btn-primary' id='SsubmitBtn' name='SsubmitBtn' >Verify</button>
                                            <button type='submit' class='btn btn-danger' id='SRemoveBtn' name='RemoveBtn'>Remove</button>
                                        </div></td>";
                                }
                                

                                echo "</tr>";
                            }
                        ?>
                    </form>
                    </table>
                </div>
            </div>
        </div>


        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade" id="faculty" role="tabpanel" aria-labelledby="faculty-tab">
                <div class="w-100 d-flex justify-content-center p-5">
                    <table class="table w-50">
                        <form action="" method="post">
                        <tr class="text-center">
                            <th colspan="2">Faculty Name</th>
                            <th colspan="2">Department</th>
                            <th colspan="1">Status</th>
                        </tr>
                        <?php
                            for($i=0;$i<count($facultyData);$i++){
                                $name = $facultyData[$i]['name'];
                                $department = $facultyData[$i]['department'];
                                $status = $facultyData[$i]['status'];
                                $email = $facultyData[$i]['email'];
                                $mobileNo = $facultyData[$i]['mobileNo'];
                                $semester = $facultyData[$i]['semester'];
                                $role = $facultyData[$i]['role'];
                                $mystyle = "";
                                if($status == 'Not Verified'){
                                    $mystyle = "style='color:blue;'";
                                }
                                echo 
                                " <tr class='text-center'>
                                    <td colspan='2'>$name</td>
                                    <td colspan='2'>$department</td>
                                    <td colspan='1' id = 'facultyStatusdiv$i' onmouseover='facultyShowDiv(this)' onmouseout='facultyHideDiv(this)' $mystyle>$status</td>";
                                    
                                if($status == "Not Verified"){
                                    echo "<td><div class='hide text-start' id = 'facultyDetails$i' onmouseout='hidemyDiv2(this)'>
                                            Faculty Name : $name<hr>Email: $email<hr>Mobile No: $mobileNo<hr>Department: $department<hr>Semester: $semester<hr>
                                            <select name='frole' class='form-select $rolevalidate'>
                                                <option selected value='role'>Role</option>
                                                <option value='Admin'>Admin</option>
                                                <option value='Coordinator'>Coordinator</option>
                                                <option value='Faculty'>Faculty</option>
                                                <option value='Guide'>Guide</option>
                                            </select>
                                            <div class='invalid-feedback'>
                                                Pleasr Select Faculty Role
                                            </div><hr>
                                            <input type='hidden' name='facultyEmail' value='$email'>
                                            <button type='submit' class='btn btn-primary' id='FsubmitBtn' name='FsubmitBtn' >Verify</button>
                                            <button type='submit' class='btn btn-danger' id='FRemoveBtn' name='RemoveBtn'>Remove</button>
                                        </div></td>";
                                }
                                
                                echo "</tr>";
                            }
                        ?>
                    </form>
                    </table>
                </div>
            </div>
        </div>

    </main>   

</body>
<script>

    function semOrDepartmentChange() {
       

        const addedRows = document.querySelectorAll('.added-row')
        addedRows.forEach(row => {
            row.remove()
        })

        var sem = document.querySelector('#semester').value
        var department = document.getElementById("department").value
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var studentData = this.responseText;
                const studentDataobj = JSON.parse(studentData);
                document.getElementById('No_Students').value =  Object.keys(studentDataobj).length
                Object.keys(studentDataobj).forEach(key => {
                    var name = studentDataobj[key]['name']
                    var enrollno = studentDataobj[key]['enrollmentNo']
                    var status = studentDataobj[key]['status']

                    var email = studentDataobj[key]['email']
                    var mobileNo = studentDataobj[key]['mobileNo']
                    var semester = studentDataobj[key]['semester']
                    var department = studentDataobj[key]['department']
                    
                    //let table = document.getElementById("tableBody")
                    let table = document.getElementsByTagName('tbody')[1]
                    
                    // Create row element
                    let row = document.createElement("tr")
                    row.classList.add("text-center")
                    row.classList.add("added-row")
                    
                    // Create cells
                    let c1 = document.createElement("td")
                    let c2 = document.createElement("td")
                    let c3 = document.createElement("td")
                    let c4 = document.createElement("td")
                    
                    // Insert data to cells
                    c1.innerText = name
                    c2.innerText = enrollno
                    c3.innerText = status
                    c1.colSpan = "1"
                    c2.colSpan = "2"
                    c3.colSpan = "1"

                    c3.setAttribute("id","studentStatusdiv"+key)
                    c3.setAttribute("onmouseover","studentShowDiv(this)")
                    c3.setAttribute("onmouseout","studentHideDiv(this)")
                    if(status == "Not Verified"){
                        c3.setAttribute("style","color:blue;")
                        c4.innerHTML = "<form action ='' method='post'><div class='hide text-start' id = 'studentDetails" + key + "' onmouseout='hidemyDiv1(this)'>Name : "+ name +"<hr>Email: "+ email +"<hr>Mobile No: "+ mobileNo +"<hr>Department: "+department+"<hr>Semester: "+semester+"<hr><input type='hidden' name='studentEmail' value="+email+"><button type='submit' class='btn btn-primary' id='SsubmitBtn' name='SsubmitBtn' >Verify</button>  <button type='submit' class='btn btn-danger' id='SRemoveBtn' name='RemoveBtn'>Remove</button></div></form>"
                    }
                    
                    // Append cells to row
                    row.appendChild(c1)
                    row.appendChild(c2)
                    row.appendChild(c3)
                    row.appendChild(c4)
                    
                    // Append row to table body
                    table.appendChild(row)
                });
            }
        };
        xhttp.open("GET", "studentData.php?sem="+sem+"&department="+department, true)
        xhttp.send();
    }

    function studentShowDiv(divInstance){
        let substring = "studentStatusdiv"
        index = divInstance.id.slice(substring.length)
        document.getElementById("studentDetails"+index).setAttribute("style","display:block")
    }
    function studentHideDiv(divInstance){
        let substring = "studentStatusdiv"
        index = divInstance.id.slice(substring.length)
        var element1 = document.getElementById("studentDetails"+index)
        if(!element1.matches(':hover')){
            element1.setAttribute("style","display:none")
        }        
    }
    function facultyShowDiv(divInstance){
        let substring = "facultyStatusdiv"
        index = divInstance.id.slice(substring.length)
        document.getElementById("facultyDetails"+index).setAttribute("style","display:block")
    }
    function facultyHideDiv(divInstance){
        let substring = "facultyStatusdiv"
        index = divInstance.id.slice(substring.length)
        var element1 = document.getElementById("facultyDetails"+index)
        if(!element1.matches(':hover')){
            element1.setAttribute("style","display:none")
        }        
    }
    function hidemyDiv1(divInstance){
        var element2 = document.getElementById('SsubmitBtn')
        var element3 = document.getElementById('SRemoveBtn')
        // console.log(element2.matches(':hover'))
        if(element2.matches(':hover') && element3.matches(':hover')){
            divInstance.setAttribute("style","display:block")
        }
        else{
            if(!divInstance.matches(':hover')){
                divInstance.setAttribute("style","display:none")
            }
        }
    }

    function hidemyDiv2(divInstance){
        var element2 = document.getElementById('FsubmitBtn')
        var element3 = document.getElementById('FRemoveBtn')
        // console.log(element2.matches(':hover'))
        if(element2.matches(':hover') && element3.matches(':hover')){
            divInstance.setAttribute("style","display:block")
        }
        else{
            if(!divInstance.matches(':hover')){
                divInstance.setAttribute("style","display:none")
            }
        }
    }
</script>
</html>
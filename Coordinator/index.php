<?php
    include("../Assets/scripts/login.php");

    $row = (isset($_COOKIE['email']))? login($_COOKIE['email'],$_COOKIE['password']) : login($_SESSION['email'],$_SESSION['password']);
    if(!$row){
        header('Location: ../Login');
    }
    if(isset($row) && $row['role'] != 'Coordinator'){
        header('Location: ../'.$row1['role']);
    }

    $department = $row["department"];
    $conn = new mysqli("localhost", "root", "","project") or die("Connection failed: " . $conn->connect_error);
    $result1 = $conn -> query("SELECT * FROM user where department='$department' AND role = 'Student' AND status='Verified'");
    if($result1->num_rows > 0){
        while($query = $result1->fetch_assoc()){
            $students[] = $query;
        }
    }
    $result2 = $conn -> query("SELECT * FROM user where department='$department' AND role = 'Guide' AND status='Verified'");
    if($result2->num_rows > 0){
        while($query = $result2->fetch_assoc()){
            $guides_email[] = $query;
        }
    }
    $conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
    crossorigin="anonymous"></script>
    <link href="../Assets/stylesheet/stylesheet.css" rel="stylesheet">
    <title></title>
</head>

<body>
    <header class='container py-5'>
        <div class='container-fluid d-flex justify-content-around align-items-center'>
            <img src='../Assets/image/logo.jpg'>
            <p class="h1">Project Portal System</p>
            <div class="text-center">
                <a href="../Assets/scripts/logout.php">Logout</a><br>
                <p><?php echo $row["name"] ?></p>
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
                <button class="nav-link" id="schedule-tab" data-bs-toggle="tab" data-bs-target="#groups" type="button"
                    role="tab" aria-controls="schedule" aria-selected="false">Manage Group</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#schedule" type="button"
                    role="tab" aria-controls="contact" aria-selected="false">Presentation Schedule</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#marks" type="button"
                    role="tab" aria-controls="contact" aria-selected="false">Marks Distribution</button>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <div class="w-100 d-flex justify-content-center p-5">
                    <table class="table w-50">
                        <tr>
                            <th class='text-center p-3' colspan="2">Guide Information</th>
                        </tr>
                        <tr>
                            <th>Name</th>
                            <td><?php echo $row["name"] ?></td>
                        </tr>
                        <tr>
                            <th>Department</th>
                            <td><?php echo $row["department"] ?></td>
                        </tr>
                        <tr>
                            <th>Email Id</th>
                            <td><?php echo $row["email"] ?></td>
                        </tr>
                        <tr>
                            <th>Phone Number</th>
                            <td><?php echo $row["mobileNo"] ?></td>
                        </tr>
                    </table>
                </div>
            </div>


            <div class="tab-pane fade" id="groups" role="tabpanel" aria-labelledby="contact-tab">
                <div class="w-100 d-flex justify-content-center p-5">
                    <?php
                     if(isset($_REQUEST['createGroup'])) {
                        $group = strtolower(str_replace(" ", "", $_REQUEST['groupID']));
                        $student = $_REQUEST['student'];
                        $conn = new mysqli('localhost', 'root', '', 'project');
                        $conn -> query('INSERT INTO groups values("'.$group.'", "'.$_REQUEST['guide'][0].'", "'.$_REQUEST['guide'][1].'", "", "")');
                        $conn -> query('CREATE TABLE IF NOT EXISTS '.$group.'(enroll varchar(100) PRIMARY KEY PRIMARY KEY, name varchar(100), marks int)');
                        for($i = 0;$i < 5; $i++) {
                            $conn -> query('INSERT INTO '.$group.' values("'.$student[$i]["enroll"].'", "'.$student[$i]["name"].'", -1)');
                        }
                    }
                    ?>
                    <form action="" method="POST">
                    <table class="table table-bordered w-100">
                        <tr>
                            <th class='text-center p-3' colspan="2">Student Groups and Guide Allocation</th>
                        </tr>
                        <tr>
                            <th class='rowspan text-center'><input type='text' class='form-control' name='groupID' placeholder='Group ID' required></th>
                            <td>
                                
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Student 1</th>
                                        <td><input type='text' class='form-control' name="student[0][name]" id="student[0][name]"  placeholder='Student Name' readonly></td>
                                        <td>
                                            <select class="form-select" name="student[0][enroll]" onchange="changeName(this)">
                                                <option value=''>Student Enrollment Number</option>
                                                <?php
                                                    for ($i=0;$i<count($students);$i++){
                                                        $value = $students[$i]['enrollmentNo'];
                                                        echo "<option value='$value'>$value</option>";
                                                    }
                                                ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Student 2</th>
                                        <td><input type='text' class='form-control' name="student[1][name]" id="student[1][name]" placeholder='Student Name' readonly></td>
                                        <td>
                                            <select class="form-select" name="student[1][enroll]" onchange="changeName(this)">
                                                <option value=''>Student Enrollment Number</option>
                                                <?php
                                                    for ($i=0;$i<count($students);$i++){
                                                        $value = $students[$i]['enrollmentNo'];
                                                        echo "<option value='$value'>$value</option>";
                                                    }
                                                ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Student 3</th>
                                        <td><input type='text' class='form-control' name="student[2][name]" id="student[2][name]" placeholder='Student Name' readonly></td>
                                        <td>
                                            <select class="form-select" name="student[2][enroll]" onchange="changeName(this)">
                                                <option value=''>Student Enrollment Number</option>
                                                <?php
                                                    for ($i=0;$i<count($students);$i++){
                                                        $value = $students[$i]['enrollmentNo'];
                                                        echo "<option value='$value'>$value</option>";
                                                    }
                                                ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Student 4</th>
                                        <td><input type='text' class='form-control' name="student[3][name]" id="student[3][name]" placeholder='Student Name' readonly></td>
                                        <td>
                                            <select class="form-select" name="student[3][enroll]" onchange="changeName(this)">
                                                <option value=''>Student Enrollment Number</option>
                                                <?php
                                                    for ($i=0;$i<count($students);$i++){
                                                        $value = $students[$i]['enrollmentNo'];
                                                        echo "<option value='$value'>$value</option>";
                                                    }
                                                ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Student 5</th>
                                        <td><input type='text' class='form-control' name="student[4][name]" id="student[4][name]" placeholder='Student Name' readonly></td>
                                        <td>
                                            <select class="form-select" name="student[4][enroll]" onchange="changeName(this)">
                                                <option value=''>Student Enrollment Number</option>
                                                <?php
                                                    for ($i=0;$i<count($students);$i++){
                                                        $value = $students[$i]['enrollmentNo'];
                                                        echo "<option value='$value'>$value</option>";
                                                    }
                                                ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Guide Name</th>
                                        <td><input type='text' class='form-control' name="guide[]" id="guideName" placeholder='Guide Name' readonly></td>
                                        <td>
                                            <select class="form-select" name="guide[]" onchange="changeGuideName(this)">
                                                <option value=''>Guide Email</option>
                                                <?php
                                                    for ($i=0;$i<count($guides_email);$i++){
                                                        $value = $guides_email[$i]['email'];
                                                        echo "<option value='$value'>$value</option>";
                                                    }
                                                ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan=3><input type='submit' name="createGroup" class='form-control btn btn-primary' value='Create Group'></td>
                                    </tr>
                                </table>
                            
                            </td>
                        </tr>

                        <?php
                            $conn = new mysqli('localhost', 'root', '', 'project');
                            $result = $conn -> query('SELECT * FROM groups');
                            while($row = $result -> fetch_assoc()) {
                                $result2 = $conn -> query('SELECT * FROM '.$row['groupid']);
                                echo "<tr><th class='rowspan text-center'>".$row['groupid']."</th><td><table class='table table-bordered'>";
                                for($i = 0; $i < 5; $i++) {
                                    $row2 = $result2 -> fetch_assoc();
                                    echo " <tr>
                                    <th>Student ".($i+1)."</th>
                                    <td>".$row2['enroll']."</td>
                                    <td>".$row2['name']."</td>
                                </tr>";
                                }

                                echo "<tr><th>Guide Name</th><td>".$row['guidename']."</td><td>".$row['guideemail']."</td></tr><tr>
                                <td colspan=3><input type='button' id='".$row['groupid']."' onclick='deleteGroup(this.id)' class='form-control btn btn-primary' value='Delete Group'></td>
                                </tr></table></td></tr>";
                            }
                        ?>
                    </table>
                    </form>
                </div>
        </div>

            <div class="tab-pane fade justify-content-center w-100" id="schedule" role="tabpanel"
                aria-labelledby="schedule-tab">
                <div class="p-5">
                    <table class="table table-bordered">
                        <tr>
                            <th class='text-center p-3' colspan='5'>Presentation Schedule</th>
                        </tr>
                        <tr class="text-center">
                            <th>Group ID</th>
                            <th>Phase 1 schedule</th>
                            <th>Phase 2 schedule</th>
                            <th>Final Presentation</th>
                            <td></td>
                        </tr>

                        <?php

                            if(isset($_REQUEST['sch'])) {
                                $conn -> query('INSERT INTO schedule values("'.$_REQUEST['grp'].'", "'.$_REQUEST['phase1'].'", "'.$_REQUEST['phase2'].'", "'.$_REQUEST['final'].'")');
                            }
                            $result = $conn -> query('SELECT * FROM groups');
                            while($row = $result -> fetch_assoc()) {
                                $result2 = $conn -> query('SELECT * FROM schedule where groupid="'.$row['groupid'].'"');
                                $row2 = $result2 -> fetch_assoc();
                                if(!$row2) {
                                    echo "<form action='' method='POST'>
                                    <tr>
                                    <th><input type='text' name='grp' value=".$row['groupid']." readonly></th>
                                        <td><input name='phase1' class='form-control' type='datetime-local' required></td>
                                        <td><input name='phase2' class='form-control' type='datetime-local' required></td>
                                        <td><input name='final' class='form-control' type='datetime-local' required></td>
                                        <td><input name='sch' class='form-control btn btn-primary' type='submit' value='Schedule'></td>
                                    </tr></form>";
                                } else {
                                    
                                    echo "<tr>
                                    <th>".$row['groupid']."</th>
                                        <td>".$row2['phase1']."</td>
                                        <td>".$row2['phase2']."</td>
                                        <td>".$row2['final']."</td>
                                        <td></td>
                                    </tr></form>";
                                }
                            }
                        ?>
                    </table>
                </div>
            </div>

        <div class="tab-pane fade" id="marks" role="tabpanel" aria-labelledby="contact-tab">
            <div class="w-100 d-flex justify-content-center p-5">
                <table class="table">
                    <tr>
                        <th class='text-center p-3' colspan="7">Marks Distribution</th>
                    </tr>
                    <?php

                         if(isset($_REQUEST['marksDis'])) {
                            $conn = new mysqli('localhost', 'root', '', 'project');
                            $keys = array_keys($_REQUEST);
                            $result = $conn -> query('SELECT * FROM '.$keys[0]);
                            for($i = 0; $i < 5; $i++) {
                                $row = $result  -> fetch_assoc();
                                $conn -> query('UPDATE '.$keys[0].' SET marks='.$_REQUEST[$keys[0]][$i].' where enroll="'.$row['enroll'].'"');
                            }

                        }

                        $result = $conn -> query('SELECT * from groups');
                        while($row = $result -> fetch_assoc()) {
                            $key = 0;
                            $result2 = $conn -> query('SELECT * from '.$row['groupid']);
                            echo "<form action='' method='POST'><tr><th class='rowspan'>".$row['groupid']."</th>";
                            while($row2 = $result2 -> fetch_assoc()) {
                                if($row2['marks'] == -1) {
                                    $key = 0;
                                    echo "<td>".$row2['name']."<input type='text' name='".$row['groupid']."[]' class='form-control' placeholder='marks'></td>";
                                } else {
                                    $key = 1;
                                    echo "<td>".$row2['name'].": ".$row2['marks']."</td>";
                                }
                            }
                            echo "<td class='rowspan'>";
                            if($key == 0) {
                                echo "<input type='submit' class='form-control btn btn-primary' name='marksDis' value='Submit'>";
                            }
                            echo "</td></tr></form>";
                        }
                    ?>
                </table>
            </div>
        </div>
    </main>
    <Script src='include/script.js'></script>
    <script>
        function changeName(dropDownInstance) {
            var enroll = dropDownInstance.value
            var str = dropDownInstance.getAttribute("name")
            var index = str.match(/\]/).index
            var start = str.slice(0,index+1)
            
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById(start+'[name]').value = this.responseText
                }
            }
            xhttp.open("GET", "include/studentName.php?enroll="+enroll, true)
            xhttp.send();
        }
        function changeGuideName(dropDownInstance){
            var email = dropDownInstance.value
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById('guideName').value = this.responseText
                }
            }
            xhttp.open("GET", "include/studentName.php?enroll="+email, true)
            xhttp.send();
        }
    </script>
</body>

</html>
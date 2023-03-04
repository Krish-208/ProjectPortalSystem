<?php
    $studentData = array();
    $sem = $_REQUEST['sem'];
    $department = $_REQUEST['department'];
    $conn = new mysqli("localhost", "root", "","project") or die("Connection failed: " . $conn->connect_error);
    $result1 = $conn->query("SELECT * FROM user where role ='Student' and semester='$sem' and department='$department' ORDER BY status DESC;");
    if($result1->num_rows > 0){
        while($row1 = $result1->fetch_assoc()){
            $studentData[] = $row1;
        }
    }
    echo json_encode($studentData);
    $conn->close();
?>
<?php
    $enroll = $_REQUEST['enroll'];
    $conn = new mysqli("localhost", "root", "","project") or die("Connection failed: " . $conn->connect_error);
    $result = $conn->query("SELECT name FROM user where (enrollmentNo='$enroll' OR email='$enroll');");
    if($result->num_rows != 0){
        while($row = $result->fetch_assoc()) {
            $studentName[] = $row;
        }
    }
    if(isset($studentName[0]['name'])){
        echo $studentName[0]['name'];
    }
    else{
        echo "";
    }
    $conn->close();
?>
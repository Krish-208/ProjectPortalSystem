<?php
    include('../Assets/scripts/login.php');
    $conn = new mysqli("localhost", "root", "","project") or die("Connection failed: " . $conn->connect_error);
    $studentData = (isset($_COOKIE['email']))? login($_COOKIE['email'],$_COOKIE['password']) : login($_SESSION['email'],$_SESSION['password']);
    if(!$studentData){
        header('Location: ../Login');
    }
    if(isset($studentData) && $studentData['role'] != 'Student'){
        header('Location: ../'.$studentData['role']);
    }
    if(isset($studentData) && $studentData['role'] == 'Student' && $studentData['status'] == 'Not Verified'){
        header('Location: ../Assets/scripts/NotVerified.php');
    }

    $enroll = $studentData['enrollmentNo'];
    $result3 = $conn -> query("SELECT * FROM queries where enroll='$enroll' ORDER BY updated DESC");
    if($result3->num_rows > 0){
        while($query = $result3->fetch_assoc()){
            $queriesData[] = $query;
        }
    }
    $result1 = $conn -> query("SELECT * FROM groups");
    if($result1->num_rows > 0){
        while($groups = $result1->fetch_assoc()){
            $groupsData[] = $groups;
        }
    }
    for($i=0;$i<count($groupsData);$i++){
        $myGroupData = array();
        $groupid = $groupsData[$i]['groupid'];
        $result2 = $conn -> query("SELECT * FROM $groupid");
        while($GroupData = $result2->fetch_assoc()){
            $myGroupData[] = $GroupData;
        }
        for($j=0;$j<count($myGroupData);$j++){
            if($myGroupData[$j]['name'] == $studentData['name']){
                $index = $i;
                break 2;
            }
        }
    }
    $groupid = $groupsData[$index]['groupid'];
    $scheduleData = $conn -> query("SELECT * FROM schedule where groupid = '$groupid'")->fetch_assoc();

    if(isset($_REQUEST['submitbtn'])){
        unset($_REQUEST['submitbtn']);

        $projectName = $_REQUEST['projectName'];
        $conn->query("UPDATE groups SET projectName = '$projectName' WHERE groupid='$groupid';");

        $dir_name = "../Assets/data/".$groupid."/";
        if (!is_dir($dir_name)) {
            mkdir($dir_name);
        }

        if($_FILES['projectInformation']['error'] === 0){
            $ext = strtolower(pathinfo( $_FILES['projectInformation']['name'] , PATHINFO_EXTENSION));
            if(in_array($ext, array("pdf") )){
                $newName = "projectInformation.".$ext;
                $path = '../Assets/data/'. $groupid . '/' . $newName;
                move_uploaded_file($_FILES['projectInformation']['tmp_name'] , $path);
            }
        }
        if($_FILES['projectPPT']['error'] === 0){
            $ext = strtolower(pathinfo( $_FILES['projectPPT']['name'] , PATHINFO_EXTENSION));
            if(in_array($ext, array("pptx","ppt") )){
                $newName = "projectPPT.".$ext;
                $path = '../Assets/data/'. $groupid . '/' . $newName;
                move_uploaded_file($_FILES['projectPPT']['tmp_name'] , $path);
            }
        }
        if($_FILES['projectWireframe']['error'] === 0){
            $ext = strtolower(pathinfo( $_FILES['projectWireframe']['name'] , PATHINFO_EXTENSION));
            if(in_array($ext, array("pdf") )){
                $newName = "projectWireframe.".$ext;
                $path = '../Assets/data/'. $groupid . '/' . $newName;
                move_uploaded_file($_FILES['projectWireframe']['tmp_name'] , $path);
            }
        }
        if($_FILES['projectDiagram']['error'] === 0){
            $ext = strtolower(pathinfo( $_FILES['projectDiagram']['name'] , PATHINFO_EXTENSION));
            if(in_array($ext, array("pdf") )){
                $newName = "projectDiagram.".$ext;
                $path = '../Assets/data/'. $groupid . '/' . $newName;
                move_uploaded_file($_FILES['projectDiagram']['tmp_name'] , $path);
            }
        }
        if($_FILES['projectSRS']['error'] === 0){
            $ext = strtolower(pathinfo( $_FILES['projectSRS']['name'] , PATHINFO_EXTENSION));
            if(in_array($ext, array("pdf") )){
                $newName = "projectSRS.".$ext;
                $path = '../Assets/data/'. $groupid . '/' . $newName;
                move_uploaded_file($_FILES['projectSRS']['tmp_name'] , $path);
            }
        }
    }
    if(isset($_REQUEST['submitBtn2'])){
        unset($_REQUEST['submitBtn2']);
        $newQuery = $_REQUEST['newQuery'];
        $result3 = $conn -> query("INSERT INTO queries VALUES ('$enroll','$newQuery','',0,current_timestamp())");
    }
    $conn -> close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
</head>

<body>
    <header class='container py-5'>
        <div class='container-fluid d-flex justify-content-around align-items-center'>
            <img src='../Assets/image/logo.jpg'>
            <p class="h1">Project Portal System</p>
            <div class="text-center">
                <a href="../Assets/scripts/logout.php">Logout</a><br>
                <p><?php echo $studentData['name']?></p>
            </div>
        </div>
    </header>
    <hr>

    <main class="container">
        <ul class="nav nav-tabs justify-content-center" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Home</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="addProjectDetails-tab" data-bs-toggle="tab" data-bs-target="#addProjectDetails" type="button" role="tab" aria-controls="addProjectDetails" aria-selected="false">Add Project Detail</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="postQuery-tab" data-bs-toggle="tab" data-bs-target="#postQuery" type="button" role="tab" aria-controls="postQuery" aria-selected="false">Post Query</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="presentationScheduling-tab" data-bs-toggle="tab" data-bs-target="#presentationScheduling" type="button" role="tab" aria-controls="presentationScheduling" aria-selected="false">Presentation Scheduling</button>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <div class="w-100 d-flex justify-content-center p-5">
                    <table class="table w-50">
                        <tr>
                            <th>Branch Name</th>
                            <td><?php echo $studentData['department']?></td>
                        </tr>
                        <tr>
                            <th>Semester</th>
                            <td><?php echo $studentData['semester']?></td>
                        </tr>
                        <tr>
                            <th><label for="projectName" class="form-label">Project Name</label></th>
                            <td><?php echo ($groupsData[$index]['projectName'] != "")? $groupsData[$index]['projectName']: "Not Entered" ;?></td>
                        </tr>
                        <tr>
                            <th>Guide Name</th>
                            <td><?php echo ($groupsData[$index]['guidename'] != "")? $groupsData[$index]['guidename']: "Not Allocated" ;?></td>
                        </tr>
                        <tr>
                            <th>Group member name and enrollment number</th>
                            <td>
                                <table class="table table-bordered w-50">
                                    <?php
                                        for($i=0;$i<count($myGroupData);$i++){
                                            $enroll = $myGroupData[$i]['enroll'];
                                            $name = $myGroupData[$i]['name'];
                                            echo "<tr>
                                                <td>$name</td>
                                                <td>$enroll</td>
                                            </tr>";
                                        }
                                    ?>
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
       
            <div class="tab-pane fade" id="addProjectDetails" role="tabpanel" aria-labelledby="addProjectDetails-tab">
                <div class="w-100 d-flex justify-content-center p-5">
                    <form action="" method="post" enctype="multipart/form-data">
                    <table class="table w-50">
                        <tr>
                            <th><label for="projectName" class="form-label">Project Name</label></th>
                            <td><input class="form-control" type="text" id="projectName" name="projectName" required value="<?php echo ($groupsData[$index]['projectName'] != "")? $groupsData[$index]['projectName']: "" ;?>" <?php if($groupsData[$index]['projectName'] != ""){ echo "disabled";}?>></td>
                        </tr>
                        <tr>
                            <th>Project submission</th>
                            <td>
                                <table class="table table-bordered w-50">
                                    <tr>
                                        <td><label for="projectInformation" class="form-label">Project Information</label></td>
                                        <td>
                                        <div class="mb-3" style="width:20rem;">
                                            <input class="form-control" type="file" name = "projectInformation" id="projectInformation" required>
                                        </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label for="projectPPT" class="form-label">Project PPT</label></td>
                                        <td>
                                        <div class="mb-3" style="width:20rem;">
                                            <input class="form-control" type="file" name = "projectPPT" id="projectPPT" required>
                                        </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label for="projectWireframe" class="form-label">Project Wireframe</label></td>
                                        <td>
                                        <div class="mb-3" style="width:20rem;">
                                            <input class="form-control" type="file" name = "projectWireframe" id="projectWireframe" required>
                                        </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label for="projectDiagram" class="form-label">Project Diagram</label></td>
                                        <td>
                                        <div class="mb-3" style="width:20rem;">
                                            <input class="form-control" type="file" name = "projectDiagram" id="projectDiagram" required>
                                        </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label for="projectSRS" class="form-label">Project SRS</label></td>
                                        <td>
                                        <div class="mb-3" style="width:20rem;">
                                            <input class="form-control" type="file" name = "projectSRS" id="projectSRS" required>
                                        </div>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr class="text-center"><td colspan="2"><input class="btn btn-primary w-25" type="submit" name="submitbtn" value="Submit"></td></tr>
                    </table>
                    </form>
                </div>
            </div>
       
            <div class="tab-pane fade" id="postQuery" role="tabpanel" aria-labelledby="postQuery-tab">
                <div class="w-100 justify-content-center p-5">
                    <form action="" method="post">
                    <table class="table text-center">
                        <tr>
                            <th>Query</th>
                            <th>Guide Reply</th>
                        </tr>
                        <td>
                            <div class='input-group mb-2'>
                                <input type='text' class='form-control' placeholder='Query' name="newQuery">
                                <button class='btn btn-primary' type='submit' name="submitBtn2">Submit</button>
                             </div>
                        </td>
                        <td>
                            <input type='text' class='form-control' placeholder='Answer' disabled>
                        </td>
                        <?php
                            if(!isset($queriesData)) $queriesData = array();
                            for($i=0;$i<count($queriesData);$i++){
                                if($queriesData[$i]['reply'] == ""){
                                    $reply = "--- Awaiting Reply ---";
                                }
                                else{
                                    $reply = $queriesData[$i]['reply'];
                                }
                                echo "<tr>
                                        <td>
                                            <input type='text' class='form-control' value='" .$queriesData[$i]['query']. "'disabled>
                                        </td>
                                        <td>
                                            <input type='text' class='form-control' value='" .$reply. "'disabled>
                                        </td>
                                    </tr>";
                            }
                        ?>
                        <tr>
                    </table>
                    </form>
                </div>
            </div>
      
            <div class="tab-pane fade" id="presentationScheduling" role="tabpanel" aria-labelledby="presentationScheduling-tab">
                <div class="w-100 d-flex justify-content-center p-5">
                    <table class="table table-bordered w-50">
                        <tr class="text-center">
                            <th>Group ID</th>
                            <th>Phase 1 schedule</th>
                            <th>Phase 2 schedule</th>
                            <th>Final Presentation</th>
                        </tr>

                        <?php
                                
                            echo "<tr>
                            <th>".$scheduleData['groupid']."</th>
                                <td>".$scheduleData['phase1']."</td>
                                <td>".$scheduleData['phase2']."</td>
                                <td>".$scheduleData['final']."</td>
                            </tr></form>";
                        ?>
                                
                        </tr>
                        <!-- <tr>
                            <th></th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Comment</th>
                        <tr>
                        <tr>
                            <td>Phase 1: </td>
                            <td></td>
                            <td></td>
                            <td></td>
                        <tr>
                        <tr>
                            <td>Phase 2: </td>
                            <td></td>
                            <td></td>
                            <td></td>
                        <tr>
                        <tr>
                            <td>Final Presentation</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        <tr> -->
                    </table>
                </div>
            </div>
        </div>
    </main>
</body>

</html>
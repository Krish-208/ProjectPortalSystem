<?php
    include("../Assets/scripts/login.php");

    $row1 = (isset($_COOKIE['email']))? login($_COOKIE['email'],$_COOKIE['password']) : login($_SESSION['email'],$_SESSION['password']);
    if(!$row1){
        header('Location: ../Login');
    }
    if(isset($row1) && $row1['role'] != 'Guide'){
        header('Location: ../'.$row1['role']);
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link href="../Assets/stylesheet/stylesheet.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
        crossorigin="anonymous"></script>
    <title></title>
</head>

<body>
    <header class='container py-5'>
        <div class='container-fluid d-flex justify-content-around align-items-center'>
            <img src='../Assets/image/logo.jpg'>
            <p class="h1">Project Portal System</p>
            <div class="text-center">
                <a href="../Assets/scripts/logout.php">Logout</a><br>
                <p><?php echo $row1["name"] ?></p>
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
                <button class="nav-link" id="schedule-tab" data-bs-toggle="tab" data-bs-target="#schedule" type="button"
                    role="tab" aria-controls="schedule" aria-selected="false">Presentation Scheduling</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button"
                    role="tab" aria-controls="contact" aria-selected="false">Student Queries</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#projectDetails" type="button"
                    role="tab" aria-controls="contact" aria-selected="false">Student Project Details</button>
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
                            <td><?php echo $row1["name"] ?></td>
                        </tr>
                        <tr>
                            <th>Department</th>
                            <td><?php echo $row1["department"] ?></td>
                        </tr>
                        <tr>
                            <th>Email Id</th>
                            <td><?php echo $row1["email"] ?></td>
                        </tr>
                        <tr>
                            <th>Phone Number</th>
                            <td><?php echo $row1["mobileNo"] ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="tab-pane fade justify-content-center w-100" id="schedule" role="tabpanel"
                aria-labelledby="schedule-tab">
                <div class="w-100 d-flex justify-content-center p-5">
                    <table class="table table-bordered">
                        <tr>
                            <th class='text-center p-3' colspan='4'>Presentation Schedule</th>
                        </tr>
                        <tr class="text-center">
                            <th>Group ID</th>
                            <th>Phase 1 schedule</th>
                            <th>Phase 2 schedule</th>
                            <th>Final Presentation</th>
                        </tr>

                        <?php
                            $conn = new mysqli('localhost', 'root', '', 'project');
                            $result = $conn -> query('SELECT * FROM schedule, groups where groups.groupid = schedule.groupid and guideemail="'.$row1['email'].'"');
                            while($row = $result -> fetch_assoc()) {
                               
                                    echo "<tr>
                                    <th>".$row['groupid']."</th>
                                        <td>".$row['phase1']."</td>
                                        <td>".$row['phase2']."</td>
                                        <td>".$row['final']."</td>
                                    </tr></form>";
                            }
                        ?>
                               
                        </tr>
                    </table>
                </div>
            </div>

            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                <div class="w-100 d-flex justify-content-center p-5">
                    <table class="table text-center">
                        <tr>
                            <th class='text-center p-3' colspan="4">Student Queries</th>
                        </tr>
                        <tr>
                            <th>Student Name</th>
                            <th>Query</th>
                            <th>Answer</th>
                            <th>Query date</th>
                        </tr>
                        <?php
                            include("include/queries.php");
                            $result = fetch_queries();
                            while($row = $result -> fetch_assoc()) {
                                echo "<tr>
                                        <th>".$row['username']."</th>
                                        <td>".$row['query']."</td>";
                                if($row['replied'] == '1') {
                                    echo "<td>".$row['reply']."</td>";
                                } else {
                                    echo "<td>
                                            <div class='input-group mb-2'>
                                                <input type='text' class='form-control' placeholder='Answer' id='".$row['username'].":::".$row['updated']."edit' aria-describedby='button-addon2'>
                                                <button class='btn btn-primary' type='button' id='".$row['username'].":::".$row['updated']."' onclick='submitAnswer(this.id)'>Submit</button>
                                            </div>
                                        </td>";
                                }
                                echo   "<td class='".$row['username']."'>".$row['updated']."</td>";
                            }
                        ?>
                        <tr>
                    </table>
                </div>
            </div>
        


        <div class="tab-pane fade" id="projectDetails" role="tabpanel" aria-labelledby="contact-tab">
            <div class="w-100 d-flex justify-content-center p-5">
            <table class="table table-bordered">
                        <tr>
                            <th colspan=2 class='text-center p-3'>Project Details</th>
                        </tr>

                        <?php
                            $conn = new mysqli('localhost', 'root', '', 'project');
                            $result = $conn -> query('SELECT * FROM groups where guideemail="'.$row1['email'].'"');
                            while($row = $result -> fetch_assoc()) {
                                echo "<tr><th class='rowspan text-center'>".$row['groupid']."</th>
                                <td><table class='table table-bordered'><tr><th>Project Name</th>
                                <td>".(($row['projectName'] == "")? "Not Available" : $row['projectName'])."</td></tr>
                                <tr><th>Information</th><td><a href='../Assets/data/".$row['groupid']."/projectInformation.pdf'>Download</a></td></tr>
                                <tr><th>Diagram</th><td><a href='../Assets/data/".$row['groupid']."/projectDiagram.jpeg'>Download</a></td></tr>
                                <tr><th>SRS</th><td><a href='../Assets/data/".$row['groupid']."/projectSRS.pdf'>Download</a></td></tr>
                                <tr><th>WireFrame</th><td><a href='../Assets/data/".$row['groupid']."/projectWireframe.pdf'>Download</a></td></tr>
                                <tr><th>PPT</th><td><a href='../Assets/data/".$row['groupid']."/projectPPT.pptx'>Download</a></td></tr>";
                            }
                        ?>
                    </table>
            </div>
        </div>
    </main>
    <script src='include/script.js'></script>
    
</body>

</html>

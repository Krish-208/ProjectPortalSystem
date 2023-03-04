<?php
    include("../Assets/scripts/login.php");

    $row = (isset($_COOKIE['email']))? login($_COOKIE['email'],$_COOKIE['password']) : login($_SESSION['email'],$_SESSION['password']);
    if(!$row){
        header('Location: ../Login');
    }
    if(isset($row) && $row['role'] != 'Faculty'){
        header('Location: ../'.$row['role']);
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
<body>
    <div class="w-100 d-flex justify-content-center p-5">
        <table class="table w-50 table-bordered">
            <tr>
                <th><label for="name" class="form-label">Name</label></th>
                <td><input class="form-control" type="text" id="name" disabled value="<?php echo $row['name'] ?>"></td>
            <tr>
            <tr>
                <th><label for="email" class="form-label">Email Address</label></th>
                <td><input class="form-control" type="text" id="email" disabled value="<?php echo $row['email'] ?>"></td>
            <tr>
            <tr>
                <th><label for="mobile" class="form-label">Mobile Number</label></th>
                <td><input class="form-control" type="text" id="mobile" disabled value="<?php echo $row['mobileNo'] ?>"></td>
            <tr>
            <tr>
                <th><label for="dept" class="form-label">Department</label></th>
                <td><input class="form-control" type="text" id="dept" disabled value="<?php echo $row['department'] ?>"></td>
            <tr>
            <tr>
                <th><label for="role" class="form-label">Role</label></th>
                <td><input class="form-control" type="text" id="role" disabled value="<?php echo $row['role'] ?>"></td>
            <tr>
            <tr>
                <th><label for="status" class="form-label">Status</label></th>
                <td>
                    <input class="form-control" type="text" id="status" disabled value="<?php echo $row['status'] ?>">
                    <div id="statusHelp" class="form-text">If you are not verified contact Admin</div>
                </td>
            <tr>
            <tr>
                <th>Logout</th>
                <td><a href="../Assets/scripts/logout.php">Logout</a></td>
            <tr>
        </table>
    </div>
</body>
</html>
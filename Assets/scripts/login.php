<?php
    session_start();
    function login($email, $password) {
        $conn = new mysqli("localhost", "root", "", "project");
        $result = $conn -> query("SELECT * FROM user where (enrollmentNo='$email' OR email='$email') and password='$password';");
        $row = $result -> fetch_assoc();
        $conn -> close();
        if($row) {
            
            $_SESSION["email"] = $row["email"];
            $_SESSION["password"] = $row["password"];

            setcookie("email", $row["email"],time() + 3600*30, "/");
            setcookie("password", $row["password"],time() + 3600*30, "/");

            return $row;

        }

        return false;
    }
?>
<?php
    session_start();
    session_unset();
    session_destroy();
    setcookie("email", "", time() - 3600, "/");
    setcookie("password", "",time()-3600, "/");
    header("location: ../../Login/");
?>
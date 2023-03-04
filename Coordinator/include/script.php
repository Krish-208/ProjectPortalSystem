<?php
    ini_set("display_errors", '1');
    $conn = new mysqli('localhost', 'root', '', 'project');
    $result = $conn -> query('Drop table '.$_REQUEST['id']);
    $result2 = $conn -> query('Delete from groups where groupid="'.$_REQUEST['id'].'"');
    $result3 = $conn -> query('Delete from schedule where groupid="'.$_REQUEST['id'].'"');

    if($result && $result2 && $result3) {
        echo 1;
    }
?>

<?php
    ini_set("display_errors", '1');
    if(isset($_REQUEST['req'])) {
	    answered();
    }
    function fetch_queries() {
        $conn = new mysqli("localhost", "root", "", "project");
        $result = $conn -> query("SELECT * FROM queries order by updated desc");
        $conn -> close();
        return $result;
    }


	function answered() {
        $arr = explode(":::", $_REQUEST['id']);
        $conn = new mysqli("localhost", "root", "", "project");
        $result = $conn -> query("UPDATE queries SET  reply = '".$_REQUEST['answer']."', replied = 1 where username='".$arr[0]."' and updated='".$arr[1]."'");
        if($result) {
            echo 1;
        } else {
            echo 0;
        }
    
        $conn -> close();
	}
?>

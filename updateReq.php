<?php
	// Initialize the session
	session_start();
 
	// Check if the user is logged in, if not then redirect him to login page
	if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
	    header("location: index.php");
	    exit;
	}

		$quickChange = 0;

	if(!isset($_POST['confirm'])){

	$statusMap = array('Accepted','Godtatt','Requested','Forespurt','Done','Ferdig');
		$needle = array_search($_POST['status'], $statusMap);
		if(is_numeric($needle)){
			$_POST['status'] = $statusMap[$needle - 1];
		}
		if(!isset($_POST['status'])){
			$sql = "UPDATE requests SET name='" . $_POST['name'] . "', type='" . $_POST['type'] . "', link='" . $_POST['link'] . $_POST['status'] . "', note='" . $_POST['note'] . "' WHERE id=" . $_POST['id'];
		}else{
			$sql = "UPDATE requests SET name='" . $_POST['name'] . "', type='" . $_POST['type'] . "', link='" . $_POST['link'] . "', status='" . $_POST['status'] . "', note='" . $_POST['note'] . "' WHERE id=" . $_POST['id'];
		}
	}else{
		if($_POST['confirm'] == 'Godta'){
			$sql = "UPDATE requests SET status='Accepted' WHERE id =" . $_POST['id'];
			$quickChange = 1;
		}else if($_POST['confirm'] == 'Ferdig'){
			$sql = "UPDATE requests SET status='Done' WHERE id =". $_POST['id'];
			$quickChange = 1;
		}else if($_POST['confirm'] == 'Slett'){
			$sql = "DELETE FROM requests WHERE id =" . $_POST['id'];
			$quickChange = 1;
		}
	}
		require_once "database.php";
        if(mysqli_multi_query($link, $sql)){
        	if($quickChange == 1){
                header("location: curreq.php");
        	}else{
                header("location: req.php?id=" . $_POST['id']);
        	}
        } else{
                echo "Noe gikk galt, prøv igjen senere.";
        }
        // Close statement
        mysqli_stmt_close($stmt);
    
    // Close connection
    mysqli_close($link);
?>
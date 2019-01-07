<?php
    // Initialize the session
    session_start();
 
    // Check if the user is logged in, if not then redirect him to login page
    if((!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) || $_SESSION['type'] != 'admin'){
        header("location: index.php");
      
      }
      
		$sql = "UPDATE plexUsers SET type='" . $_POST['type'] . "' WHERE id=" . $_POST['id'];
		require_once "database.php";

        if(mysqli_multi_query($link, $sql)){
                header("location: users.php");
        } else{
                echo "Noe gikk galt, prøv igjen senere.";
        }
        // Close statement
        mysqli_stmt_close($stmt);
    
    // Close connection
    mysqli_close($link);
?>
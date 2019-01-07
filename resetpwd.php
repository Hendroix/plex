<?php
	session_start();
	if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
	    header("location: index.php");
	    exit;
	}

	require_once "database.php";

$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate new password
    if(empty(trim($_POST["new_password"]))){
        $new_password_err = "Please enter the new password.";     
    } elseif(strlen(trim($_POST["new_password"])) < 6){
        $new_password_err = "Password must have atleast 6 characters.";
    } else{
        $new_password = trim($_POST["new_password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm the password.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($new_password_err) && ($new_password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
        
    // Check input errors before updating the database
    if(empty($new_password_err) && empty($confirm_password_err)){
        // Prepare an update statement
        $sql = "UPDATE plexUsers SET password = ? WHERE id = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "si", $param_password, $param_id);
            
            // Set parameters
            $param_password = password_hash($new_password, PASSWORD_DEFAULT);
            $param_id = $_SESSION["id"];
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Password updated successfully. Destroy the session, and redirect to login page
                session_destroy();
                header("location: index.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Thotdex</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="style.css" >
    </head>
<body>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="infocard"> 
            <img onclick="location.href='dashboard.php'" class="infocardImg" src="tlogo.png">
		    <div class="wrapper" style="padding: 25px;">
		        <h2>Endring av passord</h2>
	            <div class="form-group <?php echo (!empty($new_password_err)) ? 'has-error' : ''; ?>">
	                <label>Nytt passord</label>
	                <input type="password" name="new_password" class="form-control" value="<?php echo $new_password; ?>">
	                <span class="help-block"><?php echo $new_password_err; ?></span>
	            </div>
	            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
	                <label>Bekreft passord</label>
	                <input type="password" name="confirm_password" class="form-control">
	                <span class="help-block"><?php echo $confirm_password_err; ?></span>
	            </div>
	            <div class="form-group">
                    <button type="submit" class="btn btn-primary">Endre</button>
	                <button type="button" style="float: right;"class="btn btn-info" onclick="location.href='dashboard.php'">Tilbake</button>
	            </div>
			</div> 
        </form>
            <div id="footerFill" class="footerFill">
                
            </div>
            <div class="footer">
            <div style="float: left;">
                <button type="disabled" disabled="true" class="btn btn-link">Copyright Hupe Inc. &#169;</button>
            </div>
            <div style="float: right;">
                <button type="button" class="btn btn-danger" onclick="location.href='logout.php'">Logg ut</button>
                <button type="button" class="btn btn-warning" onclick="location.href='resetpwd.php'">Endre passord</button>
            </div>
        </div>   
</body>
</html>
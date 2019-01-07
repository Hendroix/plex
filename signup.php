<?php
// Include config file
require_once "database.php";
 
// Define variables and initialize with empty values
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Vennligst fyll inn et brukernavn.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM plexUsers WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "Brukernavnet er allerede i bruk.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Her har noe gått galt, finn ut av det selv eller gi faen i det du prøver på.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Venligst fyll inn ett passord.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Passordet må innholde minst 6 tegn.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Venligst bekreft passordet.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Passordene stemmer ikke overens.";
        }
    }
    
    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO plexUsers (username, password) VALUES (?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
            
            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: login.php");
            } else{
                echo "Noe gikk galt, prøv igjen senere.";
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
        <title>PlexReq</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="style.css" >
    </head>
    <body>
        <div>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" id="myForm" validate class="infocard">
	                <img onclick="location.href='dashboard.php'" class="infocardImg" src="tlogo.png">
                	<h1 style="padding-left: 25px; margin-top: 25px;">Registrering</h1>
            <div style="padding: 25px;">
                <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : '';?>">
                    <label for="username">Brukernavn</label>
                    <span class="help-block"><?php echo $username_err; ?></span>
                    <input name="username" id="username" class="form-control" type="text" placeholder="Brukernavn" required value="<?php echo $username; ?>">
                </div>
                <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : '';?>">
                    <label for="password">Passord</label>
                    <span class="help-block"><?php echo $password_err; ?></span>
                    <input name="password" id="password" class="form-control" type="password" placeholder="Passord" value="<?php echo $password; ?>" required>
                </div>
                <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : '';?>">
                    <label for="password">Gjenta passord</label>
                    <span class="help-block"><?php echo $confirm_password_err; ?></span>
                    <input name="confirm_password" id="rPassword" class="form-control" type="password" placeholder="Passord" required value="<?php echo $confirm_password; ?>">
                </div>
                <div>
                    <button type="submit" class="btn btn-primary">Registrer</button>
                    <button type="reset" class="btn btn-info">Reset</button>
                </div>
                <div class="form-group" style="float: right;">
                	<p>Har du allerede en bruker kan du <a href="login.php">logge inn her.</a></p>
                </div>
            </div>
            </form>
        </div>
    </body>
</html>
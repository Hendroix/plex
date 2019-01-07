<?php
	// Initialize the session
	session_start();
 
	// Check if the user is logged in, if not then redirect him to login page
	if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
	    header("location: index.php");
	    exit;
	}

		$name = $type = $notes = $link = "";		
		$name = $_POST["name"];
		$type = $_POST["type"];
		$notes = $_POST["notes"];
		$linkd = $_POST["link"];
		$submittedBy = $_SESSION['username'];
		$statusR = "Requested";

		require_once "database.php";

		$sql = "INSERT INTO requests (name, type, note, link, submittedBy, status) VALUES('" . $name . "', '" . $type . "', '" . $notes . "', '" . $linkd . "', '" . $submittedBy . "', '" . $statusR . "')";


    if($name != ''){

        if(mysqli_multi_query($link, $sql)){
                header("location: dashboard.php");
        } else{
                echo "Noe gikk galt, prøv igjen senere.";
        }
        // Close statement
        mysqli_stmt_close($stmt);
    
    // Close connection
    mysqli_close($link);
}

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>PlexReq</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="style.css" >
    </head>
	<body>
        <div class="infocard">
            <img class="infocardImg" src="tlogo.png">
            <div style="padding: 25px;" onclick="location.href='dashboard.php'">
            <h1>Velkommen <?php echo $_SESSION["username"] ?>!</h1>
            </div>
        </div>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" id="myForm" class="infocard" validate>
	                <img onclick="location.href='dashboard.php'" class="infocardImg" src="tlogo.png">
                	<h1 style="padding-left: 25px; margin-top: 25px;">Ny forespørsel</h1>
            <div style="padding: 25px;">         
                <div class="form-group">
                    <label for="name">Tittel</label>
                    <span class="help-block"><?php echo $name_err; ?></span>
                    <input name="name" id="name" class="form-control" type="text" placeholder="Medie tittel" required>
                </div> 
                <div class="form-group">
                	<label for="event">Type</label>
                	<span class="help-block"><?php echo $nick_err ?></span>
                	<select name="type" id="type" class="form-control" style="margin-bottom: 10px;">
                		<option>Serie</option>
                		<option>Film</option>
                	</select>
                </div>
                <div class="form-group">
                    <label for="age">Notater</label>
                    <span class="help-block"><?php echo $age_err; ?></span>
                    <textarea name='notes' class="form-control" placeholder="Diverse info rundt hva som skal lastes ned."></textarea>
                </div>
                <div class="form-group">
                	<label for="event">Link</label>
                	<span class="help-block"><?php echo $event_err ?></span>
                	<input name="link" id="event" class="form-control" type="text" placeholder="imdb link til relatert innhold.">
                </div>   
                <div>
                    <button type="submit" class="btn btn-primary">Registrer</button>
                    <button style="float: right;" type="reset" class="btn btn-info">Tilbakestill</button>
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
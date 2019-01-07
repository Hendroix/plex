<?php
	// Initialize the session
	session_start();
 
	// Check if the user is logged in, if not then redirect him to login page
	if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
	    header("location: index.php");
	    exit;
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
            <div onclick="location.href='dashboard.php'" style="padding: 25px;">
			<h1>Velkommen <?php echo $_SESSION["username"] ?>!</h1>
			<h4>Bruk menyen under for å bevege deg rundt. Trykk å headeren for å returnere hit.</h4>
			</div>
		</div>
		<div class="infocard">
            <div style="padding: 25px;" onclick="location.href='newreq.php'">
				<h1>Ny forespørsel</h1>
				<h4>Trykk her å legge til en forespørsel til hvilke filmer/serier som skal lastes ned til plexen.</h4>
			</div>
		</div>
		<div class="infocard">
            <div style="padding: 25px;" onclick="location.href='curreq.php'">
			<h1>Forespørsler</h1>
			<h4>Se hvilke forespørsler som er sendt ut.</h4>
			</div>
		</div>
		<?php
			$userType = $_SESSION['type'];
			if($userType == 'admin'){
				echo "<div class='infocard'><div style='padding: 25px;', onclick=location.href=\"users.php\"><h1>Administrer brukere</h1><h4>Trykk her for å administrere brukere.</h4></div></div>";
			}
		?>
		<div class="infocard">
            <div style="padding: 25px;" onclick="location.href='https://plex.ohgod.no:32400/web/index.html'">
			<h1>Se Plex</h1>
			<h4>Trykk her for å gå til Nico-Plex.</h4>
			</div>
		</div>
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
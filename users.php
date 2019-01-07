<?php
	// Initialize the session
	session_start();
 
	// Check if the user is logged in, if not then redirect him to login page
	if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
		    header("location: index.php");
		    exit;
	}
		if($_SESSION['type'] != 'admin'){
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
			<h4>Her kan du administrere eksisterende- og nye brukere.</h4>
			</div>
		</div>		
		<?php
			require_once "database.php";

			$query = "SELECT id, username, type, active FROM plexUsers WHERE type != 'admin' ORDER BY active";
			if ($stmt = mysqli_prepare($link, $query)) {

			    /* execute query */
			    mysqli_stmt_execute($stmt);

			    /* store result */
			    mysqli_stmt_bind_result($stmt, $id, $username, $type, $active);
		    	echo "<div class='infocard'><div style='padding: 25px;'>";
		    	echo "<h1>Brukeradministrasjon</h1>";
				echo "<table class='table'>";
				echo "<tr><th>Id</th><th>Username</th><th>Type</th><th>Status</th><th>Aktiver</th><th>Profil</th><th>Slett</th></tr>";
			        
			    /* fetch values */
			    while (mysqli_stmt_fetch($stmt)) {
			    	if($active == 0){
			    	echo "<form id='form" . $id . "' method='POST' action='activateUser.php'>";
			    	echo "<input style='display: none;' name='id' value='" . $id . "''>";
			    	echo "<input style='display: none;' name='status' value='" . $active . "''>";
			    	echo "<tr>";
			    	echo "<td>" . $id . '</td>';
			    	echo '<td>' . $username . '</td>';
			        echo '<td>' . $type . '</td>';
			        echo '<td>Inaktiv</td>';
			        echo "<td><button type='submit' form='form" . $id ."' class='btn btn-success'>Aktiver</button></td>";
			        echo "<td><button type='button' class='btn btn-info' onclick=location.href=\"user.php?id=" . $id . "\">Profil</button></td>";
			        echo "<td><button type='button' class='btn btn-danger' onclick=deleteUser('form" . $id . "')>Slett</button></td>";
			        echo "</td>";
			    	echo "</form>";
			    	}else{
			    	echo "<form id='form" . $id . "' method='POST' action='activateUser.php'>";
			    	echo "<input style='display: none;' name='id' value='" . $id . "''>";
			    	echo "<input style='display: none;' name='status' value='" . $active . "''>";
			    	echo "<tr>";
			    	echo "<td>" . $id . "</td>";
			    	echo '<td>' . $username . '</td>';
			        echo '<td>' . $type . '</td>';
			        echo "<td name='id'>" . 'Aktiv' . '</td>';
			        echo "<td><button type='submit' form='form" . $id ."' class='btn btn-danger'>Deaktiver</button></td>";
			        echo "<td><button type='button' class='btn btn-info' onclick=location.href=\"user.php?id=" . $id . "\">Profil</button></td>";
			        echo "<td><button type='button' class='btn btn-danger' onclick=deleteUser('form" . $id . "')>Slett</button></td>";
			        echo "</td>";
			    	echo "</form>";

			    	}
			    }
			    echo "</table>";
		        echo '</div></div>';

			    /* close statement */
			    mysqli_stmt_close($stmt);
			}

			/* close connection */
			mysqli_close($link);
		?>
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
		<script>
			function deleteUser(s){
  				var response = window.confirm("Er du sikker på at du vil slette denne brukeren?");
				if(response == true){
					var form = document.getElementById(s);
					var confirm = document.createElement("INPUT");
					confirm.setAttribute("type", "text");
					confirm.setAttribute("name", "confirm");
					confirm.setAttribute("value", "del");
					confirm.setAttribute("style", "display: none;");
					form.appendChild(confirm);
					form.submit();
				}
			}
		</script>
	</body>
</html>
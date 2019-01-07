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
			<h4>Her er oversikten over inkommende, nye og utførte forespørsler.</h4>
			</div>
		</div>
		<?php

			require_once "database.php";

			$query = "SELECT id, name, type, note, link, submittedBy, createdAt, status FROM requests ORDER BY status desc, createdAt DESC;";

			$results = mysqli_query($link, $query);
				$requested = array();
				$accepted = array();
				$other = array();
				foreach($results as $row){
					if($row['status'] == 'Requested'){
						$row['status'] = 'Forespurt';
						array_push($requested, $row);
					}else if($row['status'] == 'Accepted'){
						$row['status'] = 'Godtatt';
						array_push($accepted, $row);
					}else{
						$row['status'] = 'Ferdig';
						array_push($other, $row);
					}
				}
			//ADMIN VIEW
			if($_SESSION['type'] == 'admin'){
				if(count($requested) > 0){

		    	echo "<div class='infocard'><div style='padding: 25px;'>";
		    	echo "<h1>Forespørsler</h1>";
				echo "<table class='table'>";
				echo "<tr><th>Se</th><th>Tittel</th><th>Type</th><th>Registrerer</th><th>Dato</th><th>Status</th><th>Oppdater</th><th>Slett</th></tr>";

				foreach ($requested as $row)
				{
			    	echo "<form id='form" . $row['id'] . "' method='POST' action='updateReq.php'>";
			    	echo "<input style='display: none;' name='id' value='" . $row['id'] . "''>";
			    	echo "<input style='display: none;' name='status' value='" . $status . "''>";
					echo "<tr>";
					echo "<td><button type='button' class='btn btn-info' onclick=location.href='req.php?id=" . $row['id'] . "'>Se</button></td>";
					echo '<td>' . $row['name'] . '</td>';
					echo '<td>' . $row['type'] . '</td>';
					echo '<td>' . $row['submittedBy'] . '</td>';
					$date = explode(' ',$row['createdAt'])[0];
					$shortDate = explode('-', $date);
					$shortendDate = $shortDate[2] . '.' . $shortDate[1] . '.' . substr($shortDate[0], 2, 2);
					echo '<td>' . $shortendDate . '</td>';
					echo '<td>' . $row['status'] . '</td>';
					echo '<td>' . "<button type='button' onclick=acceptReq('form" . $row['id'] . "') class='btn btn-success'>Godta</button>" . '</td>';
					echo '<td>' . "<button type='button' onclick=declineReq('form" . $row['id'] . "') class='btn btn-danger'>Slett</button>" . '</td>';
					echo "</tr>";
			    	echo "</form>";
				}
					echo "</table></div></div>";
				}

				if(count($accepted) > 0){

		    	echo "<div class='infocard'><div style='padding: 25px;'>";
		    	echo "<h1>Godtatt</h1>";
				echo "<table class='table'>";
				echo "<tr><th>Se</th><th>Tittel</th><th>Type</th><th>Registrerer</th><th>Dato</th><th>Status</th><th>Oppdater</th><th>Slett</th></tr>";

				foreach ($accepted as $row)
				{
			    	echo "<form id='form" . $row['id'] . "' method='POST' action='updateReq.php'>";
			    	echo "<input style='display: none;' name='id' value='" . $row['id'] . "''>";
			    	echo "<input style='display: none;' name='status' value='" . $status . "''>";
					echo "<tr>";
					echo "<td><button type='button' class='btn btn-info' onclick=location.href='req.php?id=" . $row['id'] . "'>Se</button></td>";
					echo '<td>' . $row['name'] . '</td>';
					echo '<td>' . $row['type'] . '</td>';
					echo '<td>' . $row['submittedBy'] . '</td>';
					$date = explode(' ',$row['createdAt'])[0];
					$shortDate = explode('-', $date);
					$shortendDate = $shortDate[2] . '.' . $shortDate[1] . '.' . substr($shortDate[0], 2, 2);
					echo '<td>' . $shortendDate . '</td>';
					echo '<td>' . $row['status'] . '</td>';
					echo '<td>' . "<button type='button' class='btn btn-success' onclick=finishReq('form" . $row['id'] . "') class='btn btn-success'>Ferdig</button>" . '</td>';
					echo '<td>' . "<button type='button' class='btn btn-danger' onclick=declineReq('form" . $row['id'] . "') class='btn btn-danger'>Slett</button>" . '</td>';
					echo "</tr>";
			    	echo "</form>";
				}
					echo "</table></div></div>";
				}

				if(count($other) > 0){

		    	echo "<div class='infocard'><div style='padding: 25px;'>";
		    	echo "<h1>Ferdig</h1>";
				echo "<table class='table'>";
				echo "<tr><th>Se</th><th>Tittel</th><th>Type</th><th>Registrerer</th><th>Dato</th><th>Status</th><th>Oppdater</th><th>Slett</th></tr>";

				foreach ($other as $row)
				{
			    	echo "<form id='form" . $row['id'] . "' method='POST' action='updateReq.php'>";
			    	echo "<input style='display: none;' name='id' value='" . $row['id'] . "''>";
			    	echo "<input style='display: none;' name='status' value='" . $status . "''>";
					echo "<tr>";
					echo "<td><button type='button' class='btn btn-info' onclick=location.href='req.php?id=" . $row['id'] . "'>Se</button></td>";
					echo '<td>' . $row['name'] . '</td>';
					echo '<td>' . $row['type'] . '</td>';
					echo '<td>' . $row['submittedBy'] . '</td>';
					$date = explode(' ',$row['createdAt'])[0];
					$shortDate = explode('-', $date);
					$shortendDate = $shortDate[2] . '.' . $shortDate[1] . '.' . substr($shortDate[0], 2, 2);
					echo '<td>' . $shortendDate . '</td>';
					echo '<td>' . $row['status'] . '</td>';
					echo '<td>' . "<button type='button' class='btn btn-warning' onclick=location.href='req.php?id=" . $row['id'] . "'>Endre</button>" . '</td>';
					echo '<td>' . "<button type='button' class='btn btn-danger' onclick=declineReq('form" . $row['id'] . "') class='btn btn-danger'>Slett</button>" . '</td>';
					echo "</tr>";
			    	echo "</form>";
				}
					echo "</table></div></div>";
				}
			}
			//BRUKERENS VIEW
			else{
				if(count($requested) > 0){

		    	echo "<div class='infocard'><div style='padding: 25px;'>";
		    	echo "<h1>Forespurt</h1>";
				echo "<table class='table'>";
				echo "<tr><th>Se</th><th>Tittel</th><th>Type</th><th>Registrerer</th><th>Dato</th><th>Status</th></tr>";

				foreach ($requested as $row)
				{
					echo "<tr>";
					echo "<td><button type='button' class='btn btn-info' onclick=location.href='req.php?id=" . $row['id'] . "'>Se</button></td>";
					echo '<td>' . $row['name'] . '</td>';
					echo '<td>' . $row['type'] . '</td>';
					echo '<td>' . $row['submittedBy'] . '</td>';
					$date = explode(' ',$row['createdAt'])[0];
					$shortDate = explode('-', $date);
					$shortendDate = $shortDate[2] . '.' . $shortDate[1] . '.' . substr($shortDate[0], 2, 2);
					echo '<td>' . $shortendDate . '</td>';
					echo '<td>' . $row['status'] . '</td>';
					echo "</tr>";
				}
					echo "</table></div></div>";
				}

				if(count($accepted) > 0){

		    	echo "<div class='infocard'><div style='padding: 25px;'>";
		    	echo "<h1>Godtatt</h1>";
				echo "<table class='table'>";
				echo "<tr><th>Se</th><th>Tittel</th><th>Type</th><th>Registrerer</th><th>Dato</th><th>Status</th></tr>";

				foreach ($accepted as $row)
				{
					echo "<tr>";
					echo "<td><button type='button' class='btn btn-info' onclick=location.href='req.php?id=" . $row['id'] . "'>Se</button></td>";
					echo '<td>' . $row['name'] . '</td>';
					echo '<td>' . $row['type'] . '</td>';
					echo '<td>' . $row['submittedBy'] . '</td>';
					$date = explode(' ',$row['createdAt'])[0];
					$shortDate = explode('-', $date);
					$shortendDate = $shortDate[2] . '.' . $shortDate[1] . '.' . substr($shortDate[0], 2, 2);
					echo '<td>' . $shortendDate . '</td>';
					echo '<td>' . $row['status'] . '</td>';
					echo "</tr>";
				}
					echo "</table></div></div>";
				}

				if(count($other) > 0){

		    	echo "<div class='infocard'><div style='padding: 25px;'>";
		    	echo "<h1>Ferdig</h1>";
				echo "<table class='table'>";
				echo "<tr><th>Se</th><th>Tittel</th><th>Type</th><th>Registrerer</th><th>Dato</th><th>Status</th></tr>";

				foreach ($other as $row)
				{
					echo "<tr>";
					echo "<td><button type='button' class='btn btn-info' onclick=location.href='req.php?id=" . $row['id'] . "'>Se</button></td>";
					echo '<td>' . $row['name'] . '</td>';
					echo '<td>' . $row['type'] . '</td>';
					echo '<td>' . $row['submittedBy'] . '</td>';
					$date = explode(' ',$row['createdAt'])[0];
					$shortDate = explode('-', $date);
					$shortendDate = $shortDate[2] . '.' . $shortDate[1] . '.' . substr($shortDate[0], 2, 2);
					echo '<td>' . $shortendDate . '</td>';
					echo '<td>' . $row['status'] . '</td>';
					echo "</tr>";
				}
					echo "</table></div></div>";
				}

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
			function acceptReq(s){
				var form = document.getElementById(s);
				var confirm = document.createElement("INPUT");
				confirm.setAttribute("type", "text");
				confirm.setAttribute("name", "confirm");
				confirm.setAttribute("value", "Godta");
				confirm.setAttribute("style", "display: none;");
				form.appendChild(confirm);
				form.submit();
			}
			function declineReq(s){
  				var response = window.confirm("Press a button!");
				if(response == true){
					var form = document.getElementById(s);
					var confirm = document.createElement("INPUT");
					confirm.setAttribute("type", "text");
					confirm.setAttribute("name", "confirm");
					confirm.setAttribute("value", "Slett");
					confirm.setAttribute("style", "display: none;");
					form.appendChild(confirm);
					form.submit();
				}
			}
			function finishReq(s){
				var form = document.getElementById(s);
				var confirm = document.createElement("INPUT");
				confirm.setAttribute("type", "text");
				confirm.setAttribute("name", "confirm");
				confirm.setAttribute("value", "Ferdig");
				confirm.setAttribute("style", "display: none;");
				form.appendChild(confirm);
				form.submit();
			}
		</script>
	</body>
</html>
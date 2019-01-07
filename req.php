<?php
	// Initialize the session
	session_start();
 
	// Check if the user is logged in, if not then redirect him to login page
	if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
	    header("location: index.php");
	    exit;
	}
	$id = trim($_GET['id']);
	$statusMap = array('Accepted','Godtatt','Requested','Forespurt','Done','Ferdig');
	$typeMap = array('Film','Serie');

	if($_SESSION['type'] == 'admin' && isset($_GET['edit'])){
		$edit = trim($_GET['edit']);
	}

	require_once "database.php";

	$query = "SELECT id, name, type, note, link, submittedBy, createdAt, status FROM requests WHERE id =" . $id;

	$results = mysqli_query($link, $query);

	foreach($results as $row){
		if($_SESSION['username'] == $row['submittedBy']){
			if(isset($_GET['edit'])){
				$edit = trim($_GET['edit']);
			}
		}
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
			</div>
		</div>
		<div class="infocard">
            <div style="padding: 25px;">
            	<?php
            	if($edit == 1){
					foreach($results as $row){
            			echo "<form method='POST' action='updateReq.php' class='form-group'>";
            			echo "<input style='display: none;' name='id' value='" . $id . "''>";
						echo "<div class='form-group'><label>Tittel</label><input name='name' class='form-control' value='" . $row['name'] . "'></div>";
						echo "<div class='form-group'><label>Type</label><select class='form-control' name='type'>";
						for($k = 0; $k < count($typeMap); $k++){
							if($typeMap[$k] == $row['type']){
								echo "<option selected>" . $typeMap[$k] . "</selected>";
							}else{
								echo "<option>" . $typeMap[$k] . "</selected>";

							}
						}
						echo "</select></div>";
						echo "<div class='form-group'><label>Notat</label><textarea name='note' class='form-control' value='" . $row['note'] . "'>" . $row['note'] . "</textarea></div>";
						echo "<div class='form-group'><label>Link</label><input name='link' class='form-control' value='". $row['link'] . "'></div>";
						echo "<div class='form-group'><label>Status</label>";
						if($_SESSION['type'] == 'admin'){
							echo "<select class='form-control' name='status'>";
							for($i = 0; $i < count($statusMap); $i++){
								$needle = array_search($row['status'], $statusMap);
								if(is_numeric($needle) && $needle == $i){
									echo "<option selected>" . $statusMap[$i + 1] . "</option>";
								}else{
									echo "<option>" . $statusMap[$i + 1] . "</option>";
								}
								$i++;
							}
							echo "</select></div>";
						}else{		
							$needle = array_search($row['status'], $statusMap);
							if(is_numeric($needle)){
								$row['status'] = $statusMap[$needle + 1];
							}
							echo "<h4>" . $row['status'] ."</h4>";
						}
						echo "<div class='form-group'><label>Registrert av</label><h4><a href='user.php?name=" . $row['submittedBy'] ."''>" . $row['submittedBy'] . "</a></h4></div>";
						$date = explode(' ',$row['createdAt']);
						$shortDate = explode('-', $date[0]);
						$shortendDate = $shortDate[2] . '.' . $shortDate[1] . '.' . substr($shortDate[0], 2, 2) . " - " . substr($date[1],0,5);
						echo "<div class='form-group'><label>Dato</label><h4>" . $shortendDate . "</h4></div>";
						if($_SESSION['type'] == 'admin' || $_SESSION['username'] == $row['submittedBy']){
							echo "<div class='form-group'><button type='button' class='editButton btn btn-danger' style='float: right; margin-left: 15px;' onclick=declineReq('form" . $row['id'] . "') class='btn btn-danger'>Slett</button>";
							echo "<button type='submit' class='editButton btn btn-info' style='float: right;'>Bekreft</button>";
							echo "</div></div>";
						}
						echo "</form>";
					}

            	}else{
					foreach($results as $row){
						echo "<h1>" . $row['name'] . " - " . $row['type'] . "</h1>";
						echo "<label>Notat</label><h4>" . $row['note'] . "</h4>";
						echo "<label>Link</label><h4><a href='" . $row['link'] . "'>" . $row['link'] . "</a></h4>";
						echo "<div class='form-group'><label>Registrert av</label><h4><a href='user.php?name=" . $row['submittedBy'] ."''>" . $row['submittedBy'] . "</a></h4></div>";
						$date = explode(' ',$row['createdAt']);
						$shortDate = explode('-', $date[0]);
						$shortendDate = $shortDate[2] . '.' . $shortDate[1] . '.' . substr($shortDate[0], 2, 2) . " - " . substr($date[1],0,5);
						echo "<label>Dato</label><h4>" . $shortendDate . "</h4>";
						$needle = array_search($row['status'], $statusMap);
						if(is_numeric($needle)){
							$row['status'] = $statusMap[$needle + 1];
						}
						echo "<label>Status</label><h4>" . $row['status'] . "</h4>";

						if($_SESSION['type'] == 'admin' || $_SESSION['username'] == $row['submittedBy']){
							echo "<button type='button' class='editButton btn btn-info' style='float: right;' onclick=location.href='req.php?id=" . $row['id'] . "&edit=1'>Rediger</button>";
						}
					}
            	}	
            	?>
			</div>
		</div>
		<div class="infocard" >
            <div style="padding: 25px;" onclick="location.href='curreq.php'">
			<h1>Trykk her for å gå tilbake til forespørselsoversikten!</h1>
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
		<script>
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
		</script>
	</body>
</html>
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
            <div style="padding: 25px;" onclick="location.href='dashboard.php'">
			<h1>Velkommen <?php echo $_SESSION["username"] ?>!</h1>
			</div>
		</div>
		<div class="infocard">
            <div style="padding: 25px;">
            	<?php

					$userId = $_GET['id'];
					$editUser = $_GET['edit'];
					$edit = 0;
					if(is_numeric($_GET['edit'])){
						$editCheck = intval($_GET['edit']);
						if($editCheck == 1){
							$edit = 1;
						}
					}

					if($_SERVER["REQUEST_METHOD"] == "GET"){
						require_once "database.php";

						if(is_numeric($_GET['id']) && $edit == 1 && $_SESSION['type'] == 'admin'){
							$sql = 'SELECT id, username, type, active FROM plexUsers WHERE id = ' . $userId;

						if ($stmt = mysqli_prepare($link, $sql)) {

						    /* execute query */
						    mysqli_stmt_execute($stmt);

						    /* store result */
						    mysqli_stmt_bind_result($stmt, $id, $username, $type, $active);

						    while (mysqli_stmt_fetch($stmt)) {
						    	if($active == 1){
						    		$activePrint = 'checked';
						    		$activeText = 'Aktivert';
						    	}else{
						    		$activePrint = false;
						    		$activeText = 'Ikke aktivert';
						    	}
							    	echo "<form method='POST' action='updateuser.php' id='myForm' class='form-group' validate>";
							    	echo "<input name='id' style='display: none;' value='" . $id ."''>";
						    		echo "<label>Brukernavn</label><input name='username' class='form-control' value='" . $username . "'>";
						    		$userTypes = array('admin','bruker');
						    		echo "<label>Type</label><select name='type' class='form-control'>";
						    		for($i = 0; $i < count($userTypes); $i++){
						    			if($type == $userTypes[$i]){
						    				echo "<option selected>" . $userTypes[$i] . "</option>";
						    			}else{
						    				echo "<option>" . $userTypes[$i] . "</option>";
						    			}
						    		}
						    		echo "</select";
						    		echo "<div style='margin-bottom: 25px; margin-top: 15px;' class='form-check'><input name='active' type='checkbox' class='form-check-input checkbox' " . $activePrint . "><label style='margin-left: 40px; margin-top: 5px;' class='form-check-label'>Aktivert</label></div>";
							        echo "<button type='submit' style='margin-right: 70px !important;' class='newEditButton btn btn-success'>Bekreft</button>";
			        				echo "<button type='button' class='newEditButton btn btn-danger' onclick=deleteUser('myForm')>Slett</button>";
							    	echo "</form>";
							    	echo "</div>";

						    	}
						    }
						}else if(is_numeric($_GET['id'])){
							$sql = 'SELECT id, username, type, active FROM plexUsers WHERE Id = ' . $userId;
						if ($stmt = mysqli_prepare($link, $sql)) {

						    /* execute query */
						    mysqli_stmt_execute($stmt);

						    /* store result */
						    mysqli_stmt_bind_result($stmt, $id, $username, $type, $active);

						    while (mysqli_stmt_fetch($stmt)) {
						    	if($active == 1){
						    		$activePrint = 'checked';
						    		$activeText = 'Aktivert';
						    	}else{
						    		$activePrint = false;
						    		$activeText = 'Ikke aktivert';
						    	}
							    	echo "<div class='form-group'>";
						    		echo "<label>Brukernavn</label><h4>" . $username . "</h4>";
						    		echo "<label>Type</label><h4>" . $type . "</h4>";
						    		echo "<div style='margin-bottom: 25px; margin-top: 15px;' class='form-check'><input name='active' type='checkbox' class='form-check-input checkbox' " . $activePrint . "><label style='margin-left: 40px; margin-top: 5px;' class='form-check-label'>Aktivert</label></div>";
						    		if($_SESSION['type'] == 'admin'){
						        	echo "<button type='button' class='editButton btn btn-danger' onclick='location.href=\"user.php?id=" . $userId . "&edit=1\"'>Endre</button>";
						    		}
							    	echo "</div>";
						    	}
							}
						}else if(is_string($_GET['name']) && !isset($_GET['id'])){
							$username = trim($_GET['name']);
							$sql = "SELECT id, username, type, active FROM plexUsers WHERE username = '" . $username . "'";
						if ($stmt = mysqli_prepare($link, $sql)) {

						    /* execute query */
						    mysqli_stmt_execute($stmt);

						    /* store result */
						    mysqli_stmt_bind_result($stmt, $id, $username, $type, $active);

						    while (mysqli_stmt_fetch($stmt)) {
						    	if($active == 1){
						    		$activePrint = 'checked';
						    		$activeText = 'Aktivert';
						    	}else{
						    		$activePrint = false;
						    		$activeText = 'Ikke aktivert';
						    	}
							    	echo "<div class='form-group'>";
						    		echo "<label>Brukernavn</label><h4>" . $username . "</h4>";
						    		echo "<label>Type</label><h4>" . $type . "</h4>";
						    		echo "<div style='margin-bottom: 25px; margin-top: 15px;' class='form-check'><input name='active' type='checkbox' class='form-check-input checkbox' " . $activePrint . "><label style='margin-left: 40px; margin-top: 5px;' class='form-check-label'>Aktivert</label></div>";
						    		if($_SESSION['type'] == 'admin'){
						        		echo "<button type='button' class='editButton btn btn-danger' onclick='location.href=\"user.php?id=" . $id . "&edit=1\"'>Endre</button>";
						    		}
							    	echo "</div>";
						    	}
							}

						}
					}
            	?>
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
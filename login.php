<?php
	// See all errors and warnings
	
	error_reporting(E_ALL);
	ini_set('error_reporting', E_ALL);

	$server = "localhost";
	$username = "root";
	$password = "";
	$database = "dbUser";
	$mysqli = mysqli_connect($server, $username, $password, $database);

	$email = isset($_POST["loginEmail"]) ? $_POST["loginEmail"] : false;
	$pass = isset($_POST["loginPass"]) ? $_POST["loginPass"] : false;	


	// if email and/or pass POST values are set, set the variables to those values, otherwise make them false
?>

<!DOCTYPE html>
<html>
<head>
	<title>IMY 220 - Assignment 2</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="style.css" />
	<meta charset="utf-8" />
	<meta name="author" content="Werner Graaff">
	<!-- Replace Name Surname with your name and surname -->
</head>
<body>
	<div class="container">
		<?php
			if($email && $pass){
				$query = "SELECT * FROM tbusers WHERE email = '$email' AND password = '$pass'";
				$res = $mysqli->query($query);
				if($row = mysqli_fetch_array($res)){
					
					echo 	"<table class='table table-bordered mt-3'>
								<tr>
									<td>Name</td>
									<td>" . $row['name'] . "</td>
								<tr>
								<tr>
									<td>Surname</td>
									<td>" . $row['surname'] . "</td>
								<tr>
								<tr>
									<td>Email Address</td>
									<td>" . $row['email'] . "</td>
								<tr>
								<tr>
									<td>Birthday</td>
									<td>" . $row['birthday'] . "</td>
								<tr>
							</table>";
				
					echo 	"<form method='post' action='login.php' enctype='multipart/form-data'>
								<div class='form-group'>
									<input type='hidden' class='form-control' name='loginPass' value='$pass' />
									<input type='hidden' class='form-control' name='loginEmail' value='$email' />
									<input type='file' class='form-control' name='picToUpload' id='picToUpload' /><br/>
									<input type='submit' class='btn btn-standard' value='Upload Image' name='submit' />
								</div>
						  	</form>";
				}

				else{
					echo 	'<div class="alert alert-danger mt-3" role="alert">
	  							You are not registered on this site!
	  						</div>';
				}
			}
			
			else{
				echo 	'<div class="alert alert-danger mt-3" role="alert">
	  						Could not log you in
	  					</div>';
			}
		?>
		<?php 
		if(isset($_POST["submit"]))
		{
			$filename = $_FILES['picToUpload']['name'];
			$path= "gallery/" . basename ($filename);
			$format = strtolower(pathinfo($path, PATHINFO_EXTENSION));
			if(($format == "jpg" || $format == "jpeg") && $_FILES['picToUpload']['size'] < 1048576)
			{
				$query = "SELECT user_id FROM tbusers WHERE email = '$email' AND password = '$pass'";
				$user = $mysqli->query($query);
				if($row = mysqli_fetch_array($user))
				{
					$uID = $row['user_id'];
					$sql = "INSERT INTO tbgallery (user_id, filename) VALUES ('$uID', '$filename')";
					if($mysqli->query($sql) === TRUE)
					{
						move_uploaded_file($_FILES["picToUpload"]["tmp_name"], $path);//uploads file to the gallery

					}
				}
			}

		}
		?>
	<h2> Image Gallery </h2>

	<div class="row imageGallery" id="row1">
		<?php  
			$query = "SELECT user_id FROM tbusers WHERE email = '$email' AND password = '$pass'";
			$user = $mysqli->query($query);
			if($row = mysqli_fetch_array($user))
				{
					$uID = $row['user_id'];
				}
		
			$query2 = "SELECT filename FROM tbgallery WHERE user_id = '$uID'";
			$res2 = $mysqli->query($query2);
			
			if (mysqli_num_rows($res2) > 0)
			{						
				while($row = mysqli_fetch_array($res2))
				{
					echo "<div class='col-3'";
					echo "style='background-image: url(gallery/" ;
					echo  $row['filename']  ;
					echo  "'>";
					echo "</div>";
				}
			}

		?>
		
	</div>

	</div>
</body>
</html>
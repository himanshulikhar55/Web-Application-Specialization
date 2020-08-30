<?php
	session_start();
	include_once "pdo.php";
	if(!isset($_SESSION['name']))
  		die('Not logged in');
  	$fetch = $pdo->query("SELECT * FROM autos");
  	$rows = $fetch->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Himanshu Likhar</title>

	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
</head>
<body>
	<div class="container">
		<?php
			if(isset($_SESSION['name']))
				echo "<h1>Tracking Autos for " . htmlentities($_SESSION['name']) . "</h1>\n";
			if(isset($_SESSION['success'])){
				echo('<p style="color: green">' . htmlentities($_SESSION['success']) . "</p>");
					unset($_SESSION['success']);
			}
		?>
		<h1>
			Automobiles
		</h1>
		<?php
			echo "<ul>\n";
			foreach ($rows as $row){
				echo "<li>" . $row['year'] . ' ' . $row['make'] . ' / ' . $row['mileage'] . '</li>';
			}
			echo "</ul>\n";
		?>
		<p>
			<a href="add.php">Add New</a> |
			<a href="logout.php">Logout</a>
		</p>
	</div>
</body>
</html>
<?php
	include_once "pdo.php";
	ini_set('display_errors', 1);
	ini_set('display_startup', 1);
	$success = false;
	$warn = false;
	if(empty($_SERVER['REQUEST_METHOD']) == false && isset($_POST['logout']))
		header('Location: index.php');
	if(empty($_GET['name'])){
		echo 'Name parameter missing';
		return;
	}
	if($_SERVER["REQUEST_METHOD"] == "POST")
		if(is_numeric($_POST['make']) == false && is_numeric($_POST['year']) == false)
			$warn = true;
	else if(isset($_POST['add'])){
		$success = true;
		$make = htmlentities($_POST['make']);
		$year = htmlentities($_POST['year']);
		$mileage = htmlentities($_POST['mileage']);
		$sql = "INSERT INTO autos(make,year,mileage) VALUES (:make,:year,:mileage)";
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array(':make' => $make, ':year' => $year, ':mileage' => $mileage));
	}
	$stmt = $pdo->query("SELECT auto_id, make, year, mileage FROM autos");
	$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Himanshu Likhar</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
</head>
<body style='padding-left: 20px'>
	<?php
		echo("<h1>Tracking Autos for " . htmlentities($_GET['name']) . "</h1>\n");
		if($_SERVER["REQUEST_METHOD"] == "POST" && $success){
			if(empty($_POST['make']) == false)
				echo "<p><span style='color:green'>Record inserted</span></p>";
		}
	?>
	<form method="POST">
		<p>
			Make: <input type="text" name="make">
		</p>
		<?php
			if($_SERVER["REQUEST_METHOD"] == "POST")
			if(empty($_POST['make']))
				echo("<span style='color: red'>Make is required</span>\n");
		?>
		<p>
			Year: <input type="text" name="year">
		</p>
		<p>
			Mileage: <input type="text" name="mileage">
		</p>
		<?php
			if($warn)
				echo "<p style = 'color: red'><span>Mileage and year must be numeric</span></p>";
		?>
		<input type="submit" name="add" value="Add"/>
		<input type="submit" name="logout" value="Logout"/>
	</form>
	<?php
		echo("<h2>Automobiles</h2>\n");
		echo "<ul>\n";
		foreach ( $rows as $row ) {
		    echo "<li> ";
		    echo($row['year'] . ' ' . $row['make'] . ' / ' . $row['mileage']);
		}
		echo "</ul>";
	?>
</body>
</html>
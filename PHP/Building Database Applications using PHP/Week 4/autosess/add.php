<?php
	session_start();
	include_once "pdo.php";
	if(!isset($_SESSION['name']))
  		die('Not logged in');
  	if(isset($_POST['cancel'])){
  		header('Location: view.php');
  		return;
  	}
  	if(isset($_POST['make']) && isset($_POST['year']) && isset($_POST['mileage'])){
  		if($_POST['make']==null || $_POST['make'] == '')
  			$_SESSION['error'] = "Make is required";
  		else if(!is_numeric($_POST['year']) || !is_numeric($_POST['mileage']))
  			$_SESSION['error'] = "Mileage and Year must be numeric";
  		else{
  			$sql = $pdo->prepare("INSERT INTO autos(make,year,mileage) VALUES (:make, :year,:mileage)");
  			$sql->execute(array(':make' => htmlentities($_POST['make']), ':year' => htmlentities($_POST['year']), ':mileage' => htmlentities($_POST['mileage'])));
  			$_SESSION['success'] = "Record inserted";
  			header('Location: view.php');
  			return;

  		}
  		header('Location: add.php');
  		return;
  	}
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
			if(isset($_SESSION['error'])){
				echo('<p style="color: red">' . htmlentities($_SESSION['error']) . "</p>");
					unset($_SESSION['error']);
			}
		?>
		<form method="POST">
			<p>
				<label>
					Make:
				</label>
				<input type="text" name="make">
			</p>
			<p>
				<label>
					Year:
				</label>
				<input type="text" name="year">
			</p>
			<p>
				<label>
					Mileage:
				</label>
				<input type="text" name="mileage">
			</p>
			<input type="submit" name="add" value="Add">
			<input type="submit" name="cancel" value="Cancel">
		</form>
	</div>
</body>
</html>
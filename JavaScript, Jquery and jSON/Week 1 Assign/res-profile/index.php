<?php
	session_start();
	$loggedIn = false;
	if(isset($_SESSION['name']))
		$loggedIn = true;
?>
<!DOCTYPE html>
<html>
<head>
	<title>Himanshu Likhar</title>
	<!-- bootstrap.php - this is HTML -->
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
</head>
<body style="padding-left: 25px">
	<h1>
		Himanshu Likhar's Resume Registry
	</h1>
	<?php
		if(isset($_SESSION['status']))
			echo "<p style='color: green'>" . $_SESSION['status'] . "</p>";
	?>
	<p>
		<?php
			if($loggedIn == false)
				echo "<a href='login.php'>Please log in</a>";
			else
				echo "<a href='logout.php'>Logout</a>";
		?>
	</p>
	<h4>The present contents of database:</h4>
	<table border="1">
		<tr>
			<th>Name</th>
			<th>Headline</th>
			<?php
				if(isset($_SESSION['name']))
					echo "<th>Action</th>";
			?>
		</tr>
	<?php
		include_once "pdo.php";
		$sql = $pdo->query("SELECT * FROM Profile");
		$rows = $sql->fetchAll(PDO::FETCH_ASSOC);
		foreach ($rows as $row) {
			echo "<tr><td>\n";
			echo "<a href='view.php?profile_id=" . urlencode($row["profile_id"]) . "'>" . $row['first_name'] . "</a>";
			echo "</td><td>\n";
			echo $row['headline'];
			if(isset($_SESSION['name'])){
				echo "</td><td>\n";
			echo "<a href='edit.php?profile_id=" . urlencode($row["profile_id"]) . "'>Edit</a>\n";
			echo "<a href='delete.php?profile_id=" . urlencode($row["profile_id"]) . "'>Delete</a>\n";
			}
			echo "</td></tr>\n";
		}
	?>
	</table>
	<?php
		if(isset($_SESSION['name']))
			echo "<p><a href='add.php'>Add New Entry</a></p>";
	?>
</body>
</html>
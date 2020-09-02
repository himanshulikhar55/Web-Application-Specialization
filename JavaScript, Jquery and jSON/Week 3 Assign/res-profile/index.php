<?php
	session_start();
	include_once "pdo.php";
	include_once "util.php";
?>
<!DOCTYPE html>
<html>
<head>
	<title>Himanshu Likhar</title>
	<?php require_once "css.php" ?>
</head>
<body>
	<div class="container">
		<h1>
		Himanshu Likhar's Resume Registry
	</h1>
	<?php
		flashMessages();
	?>
	<p>
		<?php
			if(isset($_SESSION['name']) == false)
				echo "<a href='login.php'>Please log in</a>";
			else
				echo "<a href='logout.php'>Logout</a>";
		?>
	</p>
	<h4>The present contents of database:</h4>
	<?php
		$sql = $pdo->query("SELECT * FROM Profile");
		$rows = $sql->fetchAll(PDO::FETCH_ASSOC);
		if(!isset($_SESSION['name']))
			echo "<p>No Rows Found</p>";
		else{
			echo "<table border='1'>\n";
			echo "<th>Name</th>\n";
			echo "<th>Headline</th>\n";
			echo "<th>Action</th>\n";
			foreach ($rows as $row) {
				echo "<tr><td>\n";
				echo "<a href='view.php?profile_id=" . urlencode($row["profile_id"]) . "'>" . $row['first_name'] . " " . $row['last_name'] . "</a>";
				echo "</td><td>\n";
				echo $row['headline'];
				echo "</td><td>\n";
				echo "<a href='edit.php?profile_id=" . urlencode($row["profile_id"]) . "'>Edit</a>\n";
				echo "<a href='delete.php?profile_id=" . urlencode($row["profile_id"]) . "'>Delete</a>\n";
				echo "</td></tr>\n";
			}
			echo "</table>\n";
			echo "<p><a href='add.php'>Add New Entry</a></p>";
		}
	?>
	</div>
</body>
</html>
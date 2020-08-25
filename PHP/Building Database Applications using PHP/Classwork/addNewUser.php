<!-- DOCUMENT PART OF THE DOM -->
<?php
	ini_set('display_errors', 1);
	ini_set('display_startup', 1);
	require_once "pdo.php";
	if(isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])){
		$query = "INSERT INTO UserData (username, Email, pass) VALUES (:username, :Email, :pass)";
		$stmt = $pdo->prepare($query);
		$stmt->execute(array(':username' => $_POST['username'], ':Email'=>$_POST['email'], ':pass'=>$_POST['password']));
	}
	if(isset($_POST['delete']) && isset($_POST['user_id'])){
		$sql = "DELETE FROM UserData WHERE user_id = :zip";
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array(':zip' => $_POST['user_id']));
	}
	$stmt = $pdo->query("SELECT username, Email, pass, user_id FROM UserData");
	$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!--VIEW PART OF THE DOM-->
<html>
<head>
	<title>Simple Form</title>
</head>
<body>
	<h3>
		Add New Users in UserData Database
	</h3>
	<table border="1">
		<?php
			echo "<h5>The Current Database is as follows:</h5>";
			foreach ($rows as $row){
				echo("<tr><td>\n");
				echo($row['username']);
				echo("</td><td>\n");
				echo($row['Email']);
				echo("</td><td>\n");
				echo($row['pass']);
				echo("</td><td>\n");
				echo('<form method="post"><input type="hidden" ');
    			echo('name="user_id" value="'.$row['user_id'].'">'."\n");
				echo('<input type="submit" value = "Delete" name="delete">');
				echo("\n</form>\n");
				echo("</td></tr>\n");
			}
		?>
	</table>
	<form method="post">
		<p>
			Username: <input type="text" name="username" size="30"required="true" >
		</p>
		<p>
			Email: <input type="text" name="email" required="true">
		</p>
		<p>
			Password: <input type="password" name="password" required="true">
		</p>
		<p>
			<input type="submit" value ="Add New User"/>
		</p>
	</form>
</body>
</html>
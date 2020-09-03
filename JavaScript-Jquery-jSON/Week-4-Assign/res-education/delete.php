<?php
	session_start();
	include_once "pdo.php";
	if(!isset($_SESSION['name']))
		die("Not logged in");
	if(isset($_POST['cancel']))
		header('Location: index.php');
	if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])){
		$stmt = $pdo->prepare("DELETE FROM Profile WHERE profile_id = :profile_id");
		$stmt->execute(array(':profile_id' => $_GET['profile_id']));
		header('Location: index.php');
		return;
	}
	$stmt = $pdo->prepare("SELECT * FROM Profile WHERE profile_id = :profile_id");
	$stmt->execute(array(':profile_id' => $_GET['profile_id']));
	$row = $stmt->fetch(PDO::FETCH_OBJ);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Himanshu Likhar</title>
	<?php require_once "css.php" ?>
</head>
<body style="padding-left: 25px;">
	<h1>
		Deleting Profile
	</h1>	
	<?php
		echo "<p>First Name: " .$row->first_name . "</p>\n";
		echo "<p>Last Name: " .$row->last_name . "</p>\n";
	?>
	<form method="POST">
		<input type="hidden" name="profile_id" value="<?php echo $_GET['profile_id'] ?>">
		<input type="submit" name="delete" value="Delete">
		<input type="submit" name="cancel" value="Cancel">
	</form>
</body>
</html>
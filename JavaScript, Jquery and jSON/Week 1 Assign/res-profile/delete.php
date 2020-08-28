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
	}
	$stmt = $pdo->prepare("SELECT * FROM Profile WHERE profile_id = :profile_id");
	$stmt->execute(array(':profile_id' => $_GET['profile_id']));
	$row = $stmt->fetch(PDO::FETCH_OBJ);
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
<body style="padding-left: 25px;">
	<h1>
		Deleting the following Profile:
	</h1>	
	<?php
		echo "<p>First Name: " .$row->first_name . "</p>\n";
		echo "<p>Last Name: " .$row->last_name . "</p>\n";
		echo "<p>Email: " .$row->email . "</p>\n";
		echo "<p>Headline:<br>\n" .$row->headline . "</p>\n";
		echo "<p>Summary:<br>\n" .$row->summary . "</p>\n";
	?>
	<form method="POST">
		<input type="hidden" name="profile_id" value="<?php echo $_GET['profile_id'] ?>">
		<input type="submit" name="delete" value="Delete" onclick="return confirmDelete();">
		<input type="submit" name="cancel" value="Cancel">
	</form>
	<script type="text/javascript">
		function confirmDelete(){
			status = document.getElementById('delete');
			if(status){
				final = confirm("Do you really want to delete the profile?");
				if(final){
					return true;
				}
			}
			return false;
		}
	</script>
</body>
</html>
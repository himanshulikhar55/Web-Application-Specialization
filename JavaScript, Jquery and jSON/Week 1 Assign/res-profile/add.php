<?php
	session_start();
	include_once "pdo.php";
	if ( ! isset($_SESSION['name']) ){
		die("Not logged in");
	}

	// If the user requested logout go back to index.php
	if ( isset($_POST['cancel']) ){
	    header('Location: index.php');
	    return;
	}

	$status = false;

	if ( isset($_SESSION['status']) ){
		$status = htmlentities($_SESSION['status']);
		$status_color = htmlentities($_SESSION['color']);
		unset($_SESSION['status']);
		unset($_SESSION['color']);
	}
	$name = htmlentities($_SESSION['name']);
	$_SESSION['color'] = 'red';
	//Check to see if we have some POST data, if we do process it
	if (isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary'])){
	    if (strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 || strlen($_POST['email']) < 1 || strlen($_POST['headline']) < 1 || strlen($_POST['summary']) < 1){
	        $_SESSION['status'] = "All fields are required";
	        header("Location: add.php");
	        return;
	    }

	    if (strpos($_POST['email'], '@') === false){
	        $_SESSION['status'] = "Email address must contain @";
	        header("Location: add.php");
	        return;
	    }

	    $stmt = $pdo->prepare("INSERT INTO Profile (user_id, first_name, last_name, email, headline, summary) VALUES (:user_id, :first_name, :last_name, :email, :headline, :summary)");

	    $stmt->execute(array(':user_id' => $_SESSION['user_id'],':first_name' => $_POST['first_name'], ':last_name' => $_POST['last_name'], ':email' => $_POST['email'],':headline' => $_POST['headline'],':summary' => $_POST['summary']));

	    $_SESSION['status'] = 'Record added';
	    $_SESSION['color'] = 'green';

	    header('Location: index.php');
		return;
	}
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
	<?php
		echo "<h1>Adding Profile for " . $_SESSION['name'] . "</h1>";
        if ( $status !== false)
       		echo("<p style='color:' " . $status_color . '>' .htmlentities($status) . "</p>\n");
		if($_SERVER["REQUEST_METHOD"] == "POST"){
			echo "IM HERE";
			$_SESSION['status'] = 'Record edited';
	        $_SESSION['color'] = 'green';

	        header('Location: index.php');
	        return;
		}
	?>
	<form method="POST">
		<p>
			<label>First Name:</label>
			<input type="type" name="first_name">
		</p>
		<p>
			<label>Last Name:</label>
			<input type="text" name="last_name">
		</p>
		<p>
			<label>Email:</label>
			<input type="text" name="email">
		</p>
		<label>Headline:</label><br/>
		<input type="text" name="headline" style="width: 40%">
		<p>
			<p>
				<label>Summary:</label>
			</p>
			<textarea name="summary" rows="8" cols="80"></textarea>
		</p>
		<p>
			<input type="submit" name="submit" value="Add">
			<input type="submit" name="cancel" value="Cancel">
		</p>
	</form>
</body>
</html>
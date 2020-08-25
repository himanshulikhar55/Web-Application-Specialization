<?php
	$error = false;
	$password = "php123";
	$char = '@';
	$isPresent = false;
	$check = md5('php123XYZ');
	ini_set('display_errors', 1);
	ini_set('display_startup', 1);
	if($_SERVER["REQUEST_METHOD"]=="POST"){
		if(isset($_POST['cancel']))
			header('Location: index.php');
		if($_POST['pass']!=="php123"){
			$error = "Incorrect password";
			error_log("Login fail ".$_POST['who']."$check");
		}
		if(strpos($_POST['who'], '@') !== false){
			$isPresent = true;
			if($error == false)
				error_log("Login success ".$_POST['who']);
		}
		if($error == false && isset($_POST['who']) && $isPresent){
			header('Location: autos.php?name='.urlencode($_POST["who"]));
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Himanshu Likhar</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
</head>
<body style="padding-left: 20px">
	<h1>
		Please Log In
	</h1>
	<?php
		if($_SERVER["REQUEST_METHOD"] == "POST"){
			if(empty($_POST['pass']) || empty($_POST['who']))
				echo "<p><span style='color:red;'>User name and password are required</span></p>\n";
			else if($isPresent !== true){
				echo "<p style='color: red'>Email must have an at-sign (@)</p>\n";
			}
			else if($error !== false){
				echo "<p style='color: red'>" . $error . "</p>\n";

			}
		}
	?>
	<form method="post">
		<p>User Name: <input type="text" name="who"></p>
		<p>Password: <input type="text" name="pass"></p>
		<p>
			<input type="submit" name="submit" value="Log In">
			<input type="submit" name="cancel" value="Cancel">
		</p>
		 <!--Hint: The password is the three character name of the programming language used in this class (all lower case) followed by 123. -->
		 <p>For password hint, please check the comments in the source code</p>
	</form>
</body>
</html>
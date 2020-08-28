<?php
	session_start();
	include_once "pdo.php";
	$dontMatch = false;
	$salt = 'XyZzy12*_';
	$pwd = $salt . 'php123';
	$correctPwd = hash('md5', $pwd);
	// if(isset($_POST['pass']))
	// 	echo "LAKSKNDLKASNDLAKSDNAKLSNDLASKND\n";
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		$newpwd = htmlentities($_POST['pass']);
		$check = hash('md5', $salt.$newpwd);
		if($check == $correctPwd){
			$stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email AND password = :password");
			$stmt->execute(array(':email' => $_POST['email'], ':password' => $correctPwd));
			$row = $stmt->fetch(PDO::FETCH_OBJ);
			if ($row !== false){
		        $_SESSION['name'] = $row->name;
		        $_SESSION['user_id'] = $row->user_id;
		        header("Location: index.php");
		        return;
		    }
		    else
				$dontMatch = true;
		}
		else
			$dontMatch = true;
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
<body style="padding-left: 25px">
	<h1>
		Please Log In
	</h1>
	<?php
		if($_SERVER["REQUEST_METHOD"] == "POST"){
			if($dontMatch == true)
				echo "<span style='color: red'>Incorrect password</span>";
		}
	?>
	<form method="POST">
		<p>
		<label>Email</label>
		<input type="text" name="email" id="email">
	</p>
	<p>
		<label>Password</label>
		<input type="password" name="pass" id="id_1723">
	</p>
	<input type="submit" name="login" value="Log In" onclick="return doValidate();">
	<input type="submit" name="cancel" value="Cancel">
	<?php

	?>
	</form>
	<script type="text/javascript">
		function doValidate(){
			console.log("Validating...");
			try{
				email = document.getElementById('email').value;
				pass = document.getElementById('id_1723').value;
				if(email == null || email == "" || pass == "" || pass == null){
					alert("Both fields must be filled out");
					return false;
				}
				if(email.search('@') == -1){
					alert("Invalid email address");
					return false;
				}
				return true;
			}
			catch(e){
				return false;
			}
			return false;
		}
		</script>
	<p></p>
	<p>
		 For a password hint, view source and find an account and password hint in the HTML comments. 
	</p>
</body>
</html>
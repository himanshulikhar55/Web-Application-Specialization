<?php
	session_start();
	include_once "pdo.php";
	include_once "util.php";
	$submit = validateLogin();
	if(isset($_POST['email'])){
		$check = hash('md5', 'XyZzy12*_' . $_POST['pass']);
		$sql = $pdo->prepare('SELECT * FROM users WHERE email = :email AND password = :pass');
		try{
			$sql->execute(array(':email' => $_POST['email'], ':pass' => $check));
			$row = $sql->fetchAll(PDO::FETCH_ASSOC);
		}
		catch(PSOExecption $e){
			die($e->message);
		}
		if($submit === true){
			$_SESSION['name'] = $_POST['email'];
			$_SESSION['user_id'] = $row[0]['user_id'];
	        header("Location: index.php");
	        return;
	    }
	    else{
			$_SESSION['error'] = $submit;
			header("Location: login.php");
	        return;
	    }
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Himanshu Likhar</title>
	<?php require_once "css.php" ?>
</head>
<body style="padding-left: 25px">
	<h1>
		Please Log In
	</h1>
	<?php
		flashMessages();
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
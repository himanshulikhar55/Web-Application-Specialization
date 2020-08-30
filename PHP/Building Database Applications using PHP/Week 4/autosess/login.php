<?php
	session_start();
	if (isset($_POST['cancel'])){
	    header("Location: index.php");
	    return;
	}
	if(isset($_POST['email']) && isset($_POST['pass'])){
		$check = hash('md5', $salt . 'php123');
		$myPass = hash('md5', $salt . $_POST['pass']);
		if($_POST['email'] == null || $_POST['email'] == '' || $_POST['pass'] == null || $_POST['pass'] == '')
			$_SESSION['error'] = "Email and password are required";
		else if(strpos($_POST['email'], '@') == false){
			$_SESSION['error'] = "Email must have an at-sign (@)";
		}	
		else if($check == $myPass){
			$_SESSION['name'] = $_POST['email'];
			error_log("Incorrect password".$_POST['email']);
			header('Location: view.php');
			return;
		}
		else if($check!== $myPass)
			$_SESSION['error'] = "Incorrect password";
		header('Location: login.php');
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
			<h1>
				Please Log In
			</h1>
			<?php
				if(isset($_SESSION['error'])){
					echo("<p style='color: red;'>" . htmlentities($_SESSION['error']) . "</p>\n");
					unset($_SESSION['error']);
				}
			?>
			<form method="POST">
				<p>
					<label>
						Email:
					</label>
					<input type="text" name="email">
				</p>
				<p>
					<label>
						Password:	
					</label>
					<input type="text" name="pass">
				</p>
				<p>
					<input type="submit" name="login" value="Log In"/>
					<input type="submit" name="cancel" value="Cancel">
				</p>
			</form>
			<p>
				 For a password hint, view source and find an account and password hint in the HTML comments.
				 <!-- Hint: The account is csev@umich.edu.
				 	The password is the three character name of the programming language used in this class (all lower case)followed by 123.
				 -->
			</p>
		</div>
	</body>
</html>

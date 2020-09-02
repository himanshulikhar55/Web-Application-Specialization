<?php
	session_start();
	include_once "pdo.php";
	include_once "util.php";
	// die($_SESSION['user_id']);
	if (! isset($_SESSION['name'])){
		die("Not logged in");
	}

	// If the user requested logout go back to index.php
	if (isset($_POST['cancel'])){
	    header('Location: index.php');
	    return;
	}

	$submit = validate();
	$submitPos = validatePos();
	//Check to see if we have some POST data, if we do process it
	if ($submit === true && $submitPos === true && isset($_POST['email'])){
		try{
		    $stmt = $pdo->prepare('INSERT INTO Profile (user_id,first_name, last_name, email, headline, summary) VALUES (:user_id, :first_name, :last_name, :email, :headline,:summary)');
	        $stmt->execute(array(
	                ':user_id' => $_SESSION['user_id'],
	                ':first_name' => $_POST['first_name'],
	                ':last_name' => $_POST['last_name'],
	                ':email' => $_POST['email'],
	                ':headline' => $_POST['headline'],
	                ':summary' => $_POST['summary'])
	        );
		}
		catch(PDOException $e){
			die($e->message);
		}
		$profile_id = $pdo->lastInsertId();
		// $profile_id = 43;
		$rank = 1;
	    for($i=1; $i<=9; $i++){
		    if( ! isset($_POST['year'.$i]) || ! isset($_POST['desc'.$i]))
		    	continue;
		    else{
		    	// die("IN ELSE");
			    $year = $_POST['year'.$i];
			    $desc = $_POST['desc'.$i];
			    $stmt = $pdo->prepare('INSERT INTO `Position`(`profile_id`, `rank`, `year`, `description`) VALUES ( :pid, :rank, :year, :description)');

	            $stmt->execute(array(
	                    ':pid' => $profile_id,
	                    ':rank' => $rank,
	                    ':year' => $year,
	                    ':description' => $desc)
	            );
			}
			$rank++;
		}	    
	    $_SESSION['success'] = 'Record added';
	    // $_SESSION['success'] = $profile_id;
	    $_SESSION['color'] = 'green';
	    header('Location: index.php');
		return;
	}
	else if(isset($_POST['email'])){
		$_SESSION['error'] = ($submit !== true?$submit:$submitPos);
		$_SESSION['color'] = "red";
		header('Location: add.php');
		return;
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Himanshu Likhar</title>
	<?php require_once "css.php" ?>
</head>
<body>
	<div class="container">
		<?php
			echo "<h1>Adding Profile for " . $_SESSION['name'] . "</h1>\n";
			flashMessages();
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
				<label>Position: </label>
				<input type='button' value='+' id='addPos'>
			</p>
			<div id="position_fields"></div>
			<p>
				<input type="submit" name="submit" value="Add">
				<input type="submit" name="cancel" value="Cancel">
			</p>
		</div>
	</form>
	<script type="text/javascript">
		countPos = 0;
		$(document).ready(function(){
			window.console && console.log("Adding position " + countPos);
		    $('#addPos').click(function(event){
		    	event.preventDefault();
		    	if(countPos>=9){
		    		alert("Maximum of nine position entries exceeded");
		    		return;
		    	}
		    	countPos++;
		    	$('#position_fields').append(
		    		'<div id="position' + countPos + '"> \ <p>Year: <input type="text" name="year' + countPos + '" value=""/> \ <input type="button" value="-" \ onclick="$(\'#position' + countPos + '\').remove(); return false;"></p> \ <textarea name="desc' + countPos + '" rows="8" cols="80" spellcheck="false"></textarea></div>'
		    	);
			});
		});
</script>
</body>
</html>
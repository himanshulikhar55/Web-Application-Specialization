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
	$submitEdu = validateEdu();
	//Check to see if we have some POST data, if we do process it
	if ($submit === true && $submitPos === true && $submitEdu === true && isset($_POST['email'])){
		
		// die("HERE");
		$profSuccess = insertProfile($pdo);
		if($profSuccess !== true)
			die($profSuccess);
		$profile_id = $pdo->lastInsertId();
		// die($profile_id);

		$posSuccess = insertPosition($pdo, $profile_id);
		if($posSuccess !== true)
			die($posSuccess);

		$eduSuccess = insertEducation($pdo, $profile_id);
		if($eduSuccess !== true)
			die($eduSuccess);
		
	    $_SESSION['success'] = 'Record added';
	    $_SESSION['color'] = 'green';
	    header('Location: index.php');
		return;
	}
	else if(isset($_POST['email'])){
		// die("HERE");
		$_SESSION['error'] = ($submit !== true?$submit:$submitPos);
		if($_SESSION['error'] === true)
			$_SESSION['error'] = $submitEdu;
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
                <label>Education: </label>
                <input type='button' value='+' id='addEdu'>
            </p>
            <div id="edu_fields"></div>
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
</body>
</html>
<script type="text/javascript">
	countPos = 0;countEdu = 0;
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
	$(document).ready(function(){
		window.console && console.log("Adding education " + countEdu);
	    $('#addEdu').click(function(event){
	    	event.preventDefault();
	    	if(countEdu>=9){
	    		alert("Maximum of nine Educatoin entries exceeded");
	    		return;
	    	}
	    	countEdu++;
	    	$('#edu_fields').append(
	    		'<div id="edu' + countEdu + '"> \ <p>Year: <input type="text" name="edu_year' + countEdu + '" value=""/> \ <input type="button" value="-" \ onclick="$(\'#edu' + countEdu + '\').remove(); return false;"></p> \ <p><label>School: </label> <input class="school" type="text" size="60" name="edu_school' + countEdu + '" value=""/></p></div>'
	    	);
	    	$('.school').autocomplete({source: "school.php"});
		});
	});
</script>
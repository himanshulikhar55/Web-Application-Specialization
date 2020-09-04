<?php
function flashMessages(){
	if(isset($_SESSION['error'])){
		echo '<p style="color: red;">' . htmlentities($_SESSION['error']) . "</p>\n";
		unset($_SESSION['error']);
	}
	if(isset($_SESSION['success'])){
		echo '<p style="color: green;">' . htmlentities($_SESSION['success']) . "</p>\n";
		unset($_SESSION['success']);
	}
}
function validatePos(){
	for($i=1; $i<=9; $i++){
	    if( ! isset($_POST['year'.$i]))
	    	continue;
	    if( ! isset($_POST['desc'.$i]))
	    	continue;
	    $year = $_POST['year'.$i];
	    $desc = $_POST['desc'.$i];
	    if ( strlen($year) == 0 || strlen($desc) == 0 )
	      return "All fields are required";

	    if ( isset($_POST['year'.$i]) && !is_numeric($_POST['year'.$i]))
	      return "Position year must be numeric";
	}
	return true;
}
function validate(){
	if(isset($_POST['email'])){
		if (strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 || strlen($_POST['email']) < 1 || strlen($_POST['headline']) < 1 || strlen($_POST['summary']) < 1)
			return "All fields are required";
		else if (strpos($_POST['email'], '@') === false)
			return "Email address must contain @";
		return true;
	}
}
function validateLogin(){
	$salt = 'XyZzy12*_';
	$pwd = $salt . 'php123';
	$correctPwd = hash('md5', $pwd);
	if(isset($_POST['email']) && isset($_POST['pass'])){
		$newpwd = htmlentities($_POST['pass']);
		$check = hash('md5', $salt.$newpwd);
		if($check === $correctPwd)
			return true;
		else
			return "Incorrect password";
	}
}
function validateEdu(){
	for($i=1; $i<=9; $i++){
	    if( ! isset($_POST['edu_year'.$i]))
	    	continue;
	    if( ! isset($_POST['edu_school'.$i]))
	    	continue;
	    $eduYear = $_POST['edu_year'.$i];
	    $eduSchool = $_POST['edu_school'.$i];
	    if ( strlen($eduYear) == 0 || strlen($eduSchool) == 0 )
	      return "All fields are required";

	    if ( isset($_POST['edu_year'.$i]) && !is_numeric($_POST['edu_year'.$i]))
	      return "Education year must be numeric";
	}
	return true;
}
function loadPos($pdo, $profile_id){
	$stmt = $pdo->prepare("SELECT * FROM Position WHERE profile_id= :prof ORDER BY rank");
	$stmt->execute(array(':prof' => $profile_id));
	$positions = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $positions;
}
function loadEdu($pdo, $institution_id){
	$sql = $pdo->prepare("SELECT * FROM Institution WHERE institution_id = :id");
    $sql->execute(array(':id' => $institution_id));
    $row = $sql->fetchAll(PDO::FETCH_ASSOC);
    return $row[0]['name'];
}
function insertProfile($pdo){
	if(isset($_POST['email'])){
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
	        return true;
		}
		catch(PDOException $e){
			return $e->message;
		}
	}
}
function insertPosition($pdo, $profile_id){
	if(isset($_POST['email'])){
		try{
			$rank = 1;
		    for($i=1; $i<=9; $i++){
			    if( ! isset($_POST['year'.$i]) || ! isset($_POST['desc'.$i]))
			    	continue;
			    else{
				    $year = $_POST['year'.$i];
				    $desc = $_POST['desc'.$i];
				    $stmt = $pdo->prepare('INSERT INTO `Position`(`profile_id`, `rank`, `year`, `description`) VALUES ( :pid, :rank, :year, :description)');
				    // var_dump($profile_id);
		            $stmt->execute(array(
		                    ':pid' => $profile_id,
		                    ':rank' => $rank,
		                    ':year' => $year,
		                    ':description' => $desc)
		            );
				}
				$rank++;
			}
			return true;
		}
		catch(PDOException $e){
			return $e->message;
		}
	}
}
function insertEducation($pdo, $profile_id){
	try{	
		if(isset($_POST['email'])){
			$eduRank = 1;
		    for($i=1; $i<=9; $i++){
			    if( ! isset($_POST['edu_year'.$i]) || ! isset($_POST['edu_school'.$i]))
			    	continue;
			    else{
				    $eduYear = $_POST['edu_year'.$i];
				    $eduSchool = $_POST['edu_school'.$i];
				    for($i=1; $i<=9; $i++){
					$stmt1 = $pdo->prepare("SELECT * FROM Institution WHERE name = :name");
					$stmt1->execute(array(':name' => $eduSchool));
					$row = $stmt1->fetchAll(PDO::FETCH_ASSOC);
					if(count($row) > 0){
						$instID = $row[0]['institution_id'];
						// die($instID);
					}
					else{
						$stmt = $pdo->prepare("INSERT INTO Institution(name) VALUES(:name)");
						$stmt->execute(array(':name' => $eduSchool));
						$instID = $pdo->lastInsertId();
					}
					// die(var_dump($instID));
					$stmt2 = $pdo->prepare('INSERT INTO `Education`(`profile_id`, `institution_id`, `rank`, `year`) VALUES (:pid, :iid, :rank, :year)');
					
					$stmt2 = $stmt2->execute(array(':pid' => $profile_id, ':iid' => $instID, ':rank' => $eduRank, ':year' => $eduYear));
					}
					// die(var_dump($profile_id));
				}
				$eduRank++;
			}
			return true;
		}
	}
	catch(PDOException $e){
		return $e->message;
	}
}
function updateProfile($pdo){
	try{
		$stmt = $pdo->prepare('UPDATE Profile SET user_id = :uid, first_name = :first_name, last_name = :last_name, email = :email, headline = :headline, summary = :summary WHERE profile_id=:pid');
	    $stmt->execute(array(
	    	':uid' => $_SESSION['user_id'],
	    	'first_name' => $_POST['first_name'],
	    	'last_name' => $_POST['last_name'],
	    	':email' => $_POST['email'],
	    	':headline' => $_POST['headline'],
	    	':summary' => $_POST['summary'],
	    	':pid' => $_GET['profile_id']));
	    return true;
	}
	catch(PDOException $e){
		return $e->message;
	}
}
function deleteEdu($pdo, $profile_id){
	try{
		$stmt = $pdo->prepare('DELETE FROM Education WHERE profile_id=:pid');
	    $stmt->execute(array(':pid' => $profile_id));
	    return true;
	}
	catch(PDOException $e){
		return $e->message;
	}
}
function deletePos($pdo, $profile_id){
	try{
		$stmt = $pdo->prepare('DELETE FROM Position WHERE profile_id=:pid');
	    $stmt->execute(array(':pid' => $profile_id));
	    return true;
	}
	catch(PDOException $e){
		return $e->message;
	}
}
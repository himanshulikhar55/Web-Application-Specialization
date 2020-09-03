<?php
	session_start();
	include_once "pdo.php";
?>
<!DOCTYPE html>
<html>
<head>
	<title>Himanshu Likhar</title>
	<?php require_once "css.php" ?>
</head>
<body >
	<div class="container">
		<h1>
		Profile information
	</h1>	
		<?php
			$stmt = $pdo->prepare("SELECT * FROM Profile WHERE profile_id = :profile_id");
			$stmt->execute(array(':profile_id' => $_GET['profile_id']));
			$row = $stmt->fetch(PDO::FETCH_OBJ);
			echo "<p>First Name: " .$row->first_name . "</p>\n";
			echo "<p>Last Name: " .$row->last_name . "</p>\n";
			echo "<p>Email: " .$row->email . "</p>\n";
			echo "<p>Headline:<br>\n" .$row->headline . "</p>\n";
			echo "<p>Summary:<br>\n" .$row->summary . "</p>\n";
			$sql = $pdo->prepare("SELECT * FROM Position WHERE profile_id = :id");
		    $sql->execute(array(':id' => $_GET['profile_id']));
		    $rows2 = $sql->fetchAll(PDO::FETCH_ASSOC);
		    if(count($rows2) !== 0){
		    	echo "<p> Position";
		    	echo "<ul>\n";
		    	$countPos = 1;
		    	$i=0;
			    foreach ($rows2 as $row){
			        echo "<li>" . $rows2[$i]['year'] . ': ' . $rows2[$i]['description'] . "</li>";
			        $countPos++;
			        $i++;
			    }
			}
		    echo "</ul>";
		    echo "<a href='index.php'>Done</a>";
		?>
	</div>
</body>
</html>
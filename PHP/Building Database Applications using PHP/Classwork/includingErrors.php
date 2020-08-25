<?php
	echo "<pre>\n";
	require_once "pdo.php";
	$query = $pdo->query("SELECT * FROM User");
	while($row = $query->fetch(PDO::FETCH_ASSOC))
		print_r($row);
	echo "</pre>\n";
?>
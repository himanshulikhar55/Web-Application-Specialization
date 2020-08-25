<?php
	$pdo = new PDO('mysql:host=localhost;port=3306;dbname=Music','root','Messilikhar123$');
	$query = $pdo->query("select * from Track");
	echo '<table border="1">'."\n";
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		echo "<tr><td>";
		echo ($row['track_id']);
		echo "</td><td>";
		echo ($row['title']);
		echo "</td><td>";
		echo ($row['len']);
		echo "</td><td>";
		echo ($row['rating']);
		echo "</td></tr>";
	}
	echo "</table>\n";
?>
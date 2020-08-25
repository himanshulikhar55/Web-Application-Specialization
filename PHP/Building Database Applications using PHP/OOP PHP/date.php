<?php
	$newVar = date_default_timezone_set("Asia/Calcutta");
	$nextWeek = time() + (7*24*60*60);
	echo("The date now is: " . date('Y-m-d')."\n");
	echo("The date next week at this time will be: " . date('Y-m-d', $nextWeek). "\n");
	$now = new DateTime();
	$nextYear = new DateTime('today +1 year');
	echo('Time now: ' . $now->format('Y-m-d') . "\n");
	echo('Time next year: ' . $nextYear->format('Y-m-d') . "\n");
?>
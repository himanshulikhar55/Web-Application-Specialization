<!DOCTYPE html>
<html>
<head>
	<title>Hello jQuery!</title>
</head>
<body>
	<p>
		Let's start using jQuery
	</p>
	<script type="text/javascript" src="jquery.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			alert("Hello jQuery World!");
			window.console && console.log('Hello jQuery...');
		});
	</script>
</body>
</html>
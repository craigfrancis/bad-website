<?php

//--------------------------------------------------
// Config

	require_once('../../config.php');

//--------------------------------------------------
// Delete user cookie

	setcookie('user_id', '', strtotime('-1 hour'));

?>
<!DOCTYPE html>
<html lang="en-GB" xml:lang="en-GB" xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta charset="UTF-8" />
	<title>Bad security</title>
	<link rel="stylesheet" type="text/css" href="/a/main.css" media="all" />
</head>
<body>

	<h1>Bad security</h1>

	<p>You have been logged out.</p>
	<p><a href="./login.php">Login again</a></p>

</body>
</html>
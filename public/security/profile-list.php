<?php

//--------------------------------------------------
// Config

	require_once('../../config.php');

//--------------------------------------------------
// Logged in

	if (!isset($_COOKIE['user_id']) || !$_COOKIE['user_id']) {
		header('Location: ./login.php');
	}

	if (!isset($_COOKIE['admin']) || $_COOKIE['admin'] != 'true') {
		exit('<p>Permission denied!</p>');
	}

//--------------------------------------------------
// Get profile

	$sql = 'SELECT * FROM user ORDER BY username';

	$users = $db->fetch_all($sql);

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

	<p>The users on this website.</p>

	<div class="basic_table">
		<table>
			<thead>
				<tr>
					<th scope="col">Username</th>
					<th scope="col">Admin</th>
					<th scope="col">Picture</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($users as $user) { ?>
					<tr>
						<td><a href="./profile-edit.php?id=<?= $user['id'] ?>"><?= $user['username'] ?></a></td>
						<td><?= ($user['admin'] == 'true' ? 'Yes' : '-') ?></td>
						<td><img src="<?= $user['picture_url'] ?>" alt="Profile picture for <?= $user['username'] ?>"></td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>

	<p><a href="./logout.php">Logout</a></p>

</body>
</html>
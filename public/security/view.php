<?php

//--------------------------------------------------
// Config

	require_once('../../config.php');

	if (!isset($_REQUEST['q'])) $_REQUEST['q'] = '';
	if (!isset($_REQUEST['s'])) $_REQUEST['s'] = '';

//--------------------------------------------------
// Get profile

	$sql = 'SELECT
				username,
				picture_url
			FROM
				user
			WHERE
				admin = "false"';

	if ($_REQUEST['q'] != '') {
		$sql .= ' AND
				username LIKE "%' . $_REQUEST['q'] . '%"';
	}

	$sql .= '
			ORDER BY
				username ' . (isset($_REQUEST['s']) ? $_REQUEST['s'] : 'ASC');

	$users = $db->fetch_all($sql);

?>
<!DOCTYPE html>
<html lang="en-GB" xml:lang="en-GB" xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta charset="UTF-8" />
	<title>Bad security</title>
	<link rel="stylesheet" type="text/css" href="../a/main.css" media="all" />
</head>
<body>

	<h1>Bad security</h1>

	<form action=<?= $_SERVER['PHP_SELF'] ?> method="get" accept-charset="UTF-8">
		<p>
			<label for="fld_search">Search the users on this website:</label>
			<input name="q" id="fld_search" type="search" value='<?= $_REQUEST['q'] ?>' />
			<input type="submit" value="Search" />
		</p>
	</form>

	<hr />

	<p>Results for <strong><?= $_REQUEST['q'] ?></strong></p>

	<div class="basic_table">
		<table>
			<thead>
				<tr>
					<th scope="col">Picture</th>
					<th scope="col"><a href="./view.php?q=<?= $_REQUEST['q'] ?>&amp;s=<?= ($_REQUEST['s'] == 'ASC' ? 'DESC' : 'ASC') ?>">Username</a></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($users as $user) { ?>
					<tr>
						<td><img src="<?= $user['picture_url'] ?>" alt="Profile picture for <?= $user['username'] ?>"></td>
						<td><?= $user['username'] ?></td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>

</body>
</html>
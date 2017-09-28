<?php

//--------------------------------------------------
// Config

	require_once('../../config.php');

	$error = false;

//--------------------------------------------------
// Login check

	if ($_REQUEST['identification'] || $_REQUEST['password']) {

		$sql = 'SELECT
					id,
					password,
					admin
				FROM
					user
				WHERE
					username = "' . $_REQUEST['identification'] . '"';

		if ($row = $db->fetch_row($sql, $parameters)) {

			if ($row['password'] != $_REQUEST['password']) {

				$error = 'Incorrect password';

			} else {

				setcookie('user_id', $row['id']);
				setcookie('admin', $row['admin']);

				if ($row['admin'] == 'true') {
					header('Location: ./profile-list.php');
				} else {
					header('Location: ./profile-edit.php?id=' . $row['id']);
				}

			}

		} else {

			$error = 'Incorrect username';

		}

	}

?>
<!DOCTYPE html>
<html lang="en-GB" xml:lang="en-GB" xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta charset="UTF-8" />
	<title>Bad security</title>
	<link rel="stylesheet" type="text/css" href="../../a/main.css" media="all" />
</head>
<body>

	<h1>Bad Security</h1>

	<p>Welcome to Bad Security, you probably don't need to know the password to log in.</p>

	<form action=<?= $_SERVER['PHP_SELF'] ?> method="get" accept-charset="UTF-8" class="basic_form">
		<fieldset>

			<?php if ($error) { ?>
				<p class="error"><?= $error ?></p>
			<?php } ?>

			<div class="row identification">
				<span class="label"><label for="fld_identification">Username</label>:</span>
				<span class="input"><input name="identification" id="fld_identification" type="text" value="<?= $_REQUEST['identification'] ?>" autofocus="autofocus" /></span>
			</div>

			<div class="row password">
				<span class="label"><label for="fld_password">Password</label>:</span>
				<span class="input"><input name="password" id="fld_password" type="password" value="<?= $_REQUEST['password'] ?>" /></span>
			</div>

			<div class="row submit">
				<input type="submit" name="button" value="Log in" />
			</div>

		</fieldset>
	</form>

</body>
</html>
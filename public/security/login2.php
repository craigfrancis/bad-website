<?php

//--------------------------------------------------
// Config

	require_once('../../config.php');

	$error = false;

//--------------------------------------------------
// Login check

	$username = $_POST['username'];
	$password = $_POST['password'];

	if ($username || $password) {

		//--------------------------------------------------
		// Too many login attempts (by IP)



		//--------------------------------------------------
		// Get details

			$sql = 'SELECT
						u.id,
						u.password
					FROM
						user AS u
					WHERE
						u.username = ? AND
						u.password != ""';

			$parameters = array();
			$parameters[] = array('s', $username);

			if ($row = $db->fetch_row($sql, $parameters)) {
				$db_id = $row['id'];
				$db_pass = $row['password'];
			} else {
				$db_id = 0;
				$db_pass = '';
			}

		//--------------------------------------------------
		// Verify password

			$start = microtime(true);

			if (trim($db_pass) == '') {
				$db_pass = '$2y$10$q2lLAWSxXkP1Ra0VkJdjEes4249rdEoocdLXnnRPLJYfi2dNDLEhy'; // A valid hash to verify against.
				$db_id = 0; // But don't allow this to work.
			}

			if ((password_verify($password, $db_pass) || $password == $db_pass) && ($db_id > 0)) {

				//--------------------------------------------------
				// Rehash password

					if (password_needs_rehash($db_pass, PASSWORD_DEFAULT)) {

						$sql = 'UPDATE
									user AS u
								SET
									u.password = ?
								WHERE
									u.id = ?';

						$parameters = array();
						$parameters[] = array('s', password_hash($db_pass, PASSWORD_DEFAULT));
						$parameters[] = array('i', $db_id);

						$db->query($sql, $parameters);

					}

				//--------------------------------------------------
				// Done

					$end = round((microtime(true) - $start), 4);

					exit('Logged in (' . $end . 's)');

					// Store details in a $SESSION

			}

		//--------------------------------------------------
		// Failed

			$end = round((microtime(true) - $start), 4);

			$error = 'Invalid login details (' . $end . 's)';

	}

?>
<!DOCTYPE html>
<html lang="en-GB" xml:lang="en-GB" xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta charset="UTF-8" />
	<title>Bad security</title>
	<link rel="stylesheet" type="text/css" href="../a/main.css" media="all" />
</head>
<body>

	<h1>Bad Security</h1>

	<p>Welcome to Bad Security, you probably don't need to know the password to log in.</p>

	<form action="./login2.php" method="post" accept-charset="UTF-8" class="basic_form">
		<fieldset>

			<?php if ($error) { ?>
				<p class="error"><?= $error ?></p>
			<?php } ?>

			<div class="row username">
				<span class="label"><label for="fld_username">Username</label>:</span>
				<span class="input"><input name="username" id="fld_username" type="text" value="" autofocus="autofocus" /></span>
			</div>

			<div class="row password">
				<span class="label"><label for="fld_password">Password</label>:</span>
				<span class="input"><input name="password" id="fld_password" type="password" value="" /></span>
			</div>

			<div class="row submit">
				<input type="submit" name="button" value="Log in" />
			</div>

		</fieldset>
	</form>

</body>
</html>
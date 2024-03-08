<?php

//--------------------------------------------------
// Config

	require_once('../../config.php');

	$error = false;

	if (!isset($_REQUEST['username'])) $_REQUEST['username'] = '';

//--------------------------------------------------
// Logged in

	if (!isset($_COOKIE['user_id']) || !$_COOKIE['user_id']) {
		header('Location: ./login.php');
	}

//--------------------------------------------------
// Get profile

	$sql = 'SELECT * FROM user WHERE id = ' . $_REQUEST['id'];

	$row = $db->fetch_row($sql);

//--------------------------------------------------
// Save profile

	if ($_REQUEST['username']) {

		if (is_uploaded_file($_FILES['picture_file']['tmp_name'])) {

			$ext = substr($_FILES['picture_file']['name'], strrpos($_FILES['picture_file']['name'], '.'));

			$_REQUEST['picture_url'] = './uploads/profile-pictures/' . $_REQUEST['id'] . $ext;

			$success = move_uploaded_file($_FILES['picture_file']['tmp_name'], $config['path_public'] . '/security/' . $_REQUEST['picture_url']);
			if (!$success) {
				exit('<p>Could not upload file (check permissions)</p>');
			}

		}

		$sql = 'UPDATE
					user
				SET
					username = "' . $_REQUEST['username'] . '",
					password = "' . $_REQUEST['password'] . '",
					picture_url = "' . $_REQUEST['picture_url'] . '"
				WHERE
					id = ' . $_REQUEST['id'];

		$db->query($sql);

		header('Location: ./profile-edit-thank-you.php');

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

	<h1>Bad security</h1>

	<p>This is the profile for <strong><?= $row['username'] ?></strong>, I wonder what we can do to break this.</p>

	<form action=<?= $_SERVER['PHP_SELF'] ?> method="post" accept-charset="UTF-8" enctype="multipart/form-data" class="basic_form">
		<fieldset>

			<?php if ($error) { ?>
				<p class="error"><?= $error ?></p>
			<?php } ?>

			<div class="row username">
				<span class="label"><label for="fld_username">Username</label>:</span>
				<span class="input"><input name="username" id="fld_username" type="text" value="<?= $row['username'] ?>" /></span>
			</div>

			<div class="row current_password">
				<span class="label">Current password:</span>
				<span class="input"><?= $row['password'] ?></span>
			</div>

			<div class="row change_password">
				<span class="label"><label for="fld_password">Change password</label>:</span>
				<span class="input"><input name="password" id="fld_password" type="password" value="<?= $row['password'] ?>" /></span>
			</div>

			<div class="row current_picture">
				<span class="label">Current picture:</span>
				<span class="input"><img src="<?= $row['picture_url'] ?>" alt="Profile picture for <?= $row['username'] ?>"></span>
			</div>

			<div class="row picture_url">
				<span class="label"><label for="fld_picture_url">Picture URL</label>:</span>
				<span class="input"><input name="picture_url" id="fld_picture_url" type="text" value="<?= $row['picture_url'] ?>" /></span>
			</div>

			<div class="row picture_upload">
				<span class="label"><label for="fld_picture_file">Picture upload</label>:</span>
				<span class="input"><input name="picture_file" id="fld_picture_file" type="file" /></span>
			</div>

			<div class="row submit">
				<input type="hidden" name="id" value="<?= $_REQUEST['id'] ?>" />
				<input type="submit" name="button" value="Save" />
			</div>

		</fieldset>
	</form>

	<p><a href="./logout.php">Logout</a></p>

</body>
</html>
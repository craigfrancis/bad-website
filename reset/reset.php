<?php

//--------------------------------------------------
// Config

	require_once('../config.php');

	$allowed_databases = array(
			'information_schema',
			$config['database']['name'],
		);

	$allowed_tables = array(
			'user',
		);

//--------------------------------------------------
// Checks

	$sql = 'SHOW DATABASES';
	foreach ($db->fetch_all($sql) as $row) {
		$database = $row['Database'];
		if (!in_array($database, $allowed_databases)) {
			exit('This account has too many permissions, it can see the database "' . $database . '"' . "\n");
		}
	}

	$sql = 'SELECT DATABASE() AS "Database"';
	if ($row = $db->fetch_row($sql)) {
		if ($row['Database'] != $config['database']['name']) {
			exit('For some reason the current database is "' . $row['Database'] . '", not "' . $config['database']['name'] . '"' . "\n");
		}
	}

	$sql = 'SHOW TABLES';
	foreach ($db->fetch_all($sql) as $row) {
		$table = $row[key($row)];
		if (!in_array($table, $allowed_tables)) {
			exit('The database "' . $config['database']['name'] . '" contains the unrecognised table "' . $table . '"' . "\n");
		}
	}

//--------------------------------------------------
// Reset

	foreach (array_filter(array_map('trim', explode(';', file_get_contents('./reset.sql')))) as $sql) {
		$db->query($sql);
	}

?>
<?php

	$config = array(
		'allowed_ip' => '127.0.0.1',
		'database' => array(
				'host' => 'localhost',
				'name' => 's-craig-badwebsite',
				'user' => 's-craig-badwebsite',
				'pass' => 'fpvmZYFzRx7xyRrseVzyTftTVwGJUq',
			),
	);

//--------------------------------------------------

	define('ROOT', dirname(__FILE__));

	if (!isset($_SERVER['REQUEST_URI']) && isset($_SERVER['SCRIPT_FILENAME'])) {
		$_SERVER['REQUEST_URI'] = preg_replace('/^' . preg_quote(ROOT, '/') . '/', '', realpath($_SERVER['SCRIPT_FILENAME']));
	}

	$config['path_public'] = ROOT . '/public/';
	$config['request_ip'] = (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : NULL);
	$config['request_uri'] = (isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : NULL);

	if (php_sapi_name() == 'cli') {
		if ($config['request_uri'] !== '/reset/reset.php') {
			echo "\n";
			echo 'This website is insecure' . "\n";
			echo 'And to get the config, it must be used as part of the reset.php process.' . "\n";
			echo "\n";
			exit();
		}
	} else {
		if ($config['request_ip'] !== $config['allowed_ip'] || $config['request_ip'] === NULL) {
			echo '<p>This website is insecure</p>';
			echo '<p>To view it, please edit the "config.php" file, and set the "allowed_ip" to "<strong>' . htmlentities($config['request_ip']) . '</strong>"</p>';
			exit();
		}
	}

//--------------------------------------------------

	error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED); // Why would we want notices?

	ini_set('taint.error_level', 0);


if (isset($_GET['config']) && $_GET['config'] == 1) {
	echo '<pre>';
	var_dump(ini_get('error_level'));
	var_dump($config);
	var_dump($_SERVER);
	echo '</pre>';
	exit();
}


//--------------------------------------------------

		// Full version is at:
		// https://github.com/craigfrancis/framework/blob/master/framework/0.1/includes/04.database.php

	class db {

		private $result;
		private $statement;
		private $affected_rows;
		private $config;
		private $link;

		public function __construct($config = array()) {
			$this->config = $config;
		}

		public function escape_string($val) {
			$this->connect();
			return '"' . mysqli_real_escape_string($this->link, $val) . '"';
		}

		public function query($sql, $parameters = NULL) {

			$this->connect();

			if (function_exists('mysqli_stmt_get_result')) {

				$this->statement = mysqli_prepare($this->link, $sql);

				if ($this->statement) {

					if ($parameters) {
						$ref_values = array(implode(array_column($parameters, 0)));
						foreach ($parameters as $key => $value) {
							$ref_values[] = &$parameters[$key][1];
						}
						call_user_func_array(array($this->statement, 'bind_param'), $ref_values);
					}

					$this->result = $this->statement->execute();
					if ($this->result) {
						$this->affected_rows = $this->statement->affected_rows;
						$this->result = $this->statement->get_result();
						if ($this->result === false) {
							$this->result = true; // Didn't create any results, e.g. UPDATE, INSERT, DELETE
						}
						$this->statement->close(); // If this isn't successful, we need to get to the errno
					}

				} else {

					$this->result = false;

				}

			} else {

				if ($parameters) {
					$offset = 0;
					$k = 0;
					while (($pos = strpos($sql, '?', $offset)) !== false) {
						if (isset($parameters[$k])) {
							$sql_value = $this->escape_string($parameters[$k][1]);
							$sql = substr($sql, 0, $pos) . $sql_value . substr($sql, ($pos + 1));
							$offset = ($pos + strlen($sql_value));
							$k++;
						} else {
							exit_with_error('Missing parameter "' . $k . '" in SQL', $sql);
						}
					}
					if (isset($parameters[$k])) {
						exit_with_error('Unused parameter "' . $k . '" in SQL', $sql);
					}
				}

				$this->result = mysqli_query($this->link, $sql);

			}

			if (!$this->result) {
				$this->_error($sql);
			}

			return $this->result;

		}

		public function fetch_all($sql = NULL, $parameters = NULL) {
			if (is_string($sql)) {
				$result = $this->query($sql, $parameters);
			} else if ($sql !== NULL) {
				$result = $sql;
			} else {
				$result = $this->result;
			}
			$data = array();
			if ($result !== true) {
				while ($row = mysqli_fetch_assoc($result)) {
					$data[] = $row;
				}
			}
			return $data;
		}

		public function fetch_row($sql = NULL, $parameters = NULL) {
			if (is_string($sql)) {
				$result = $this->query($sql, $parameters);
			} else if ($sql !== NULL) {
				$result = $sql;
			} else {
				$result = $this->result;
			}
			return mysqli_fetch_assoc($result);
		}

		private function connect() {

			if ($this->link) {
				return;
			}

			if (!function_exists('mysqli_connect')) {
				$this->_error('PHP does not have MySQLi support - https://php.net/mysqli_connect', true);
			}

			$this->link = @mysqli_connect($this->config['host'], $this->config['user'], $this->config['pass'], $this->config['name']);
			if (!$this->link) {
				$this->_error('Database connection error: ' . mysqli_connect_error() . ' (' . mysqli_connect_errno() . ')', true);
			}

			$charset = 'utf8';
			if (!mysqli_set_charset($this->link, $charset)) {
				$this->_error('Database charset error, when loading ' . $charset, true);
			}

		}

		private function _error($message) {

			if ($this->link) {
				$extra = mysqli_errno($this->link) . ': ' . mysqli_error($this->link);
			} else {
				$extra = NULL;
			}

			http_response_code(500);

			exit('<p>I have a problem: <br />' . htmlentities($message) . '<br />' . htmlentities($extra) . '</p>');

		}

	}

	$db = new db($config['database']);

?>
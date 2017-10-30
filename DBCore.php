<?php
require_once __DIR__ . "/DBPassword.php";
class DBCore extends DBPassword {
	protected $DBusername;
	protected $DBpassword;
	protected $database;
	protected $DB;

	//TODO Write documentation
	public function __construct($Accountdata, $Database) {
		$a = 0;
		$Found = false;
		$this->init();
		$LoginName = unserialize($this->getLoginName ());
		$LoginAccount = unserialize($this->getLoginAccount());
		$LoginDBpassword = unserialize($this->getLoginDBpassword());
		$LoginHost = unserialize($this->getLoginHost());
		while (isset($LoginName [$a]) === true) {
			if ($LoginName [$a] === $Accountdata) {
				$this->DBusername = $LoginAccount [$a];
				$this->DBpassword = $LoginDBpassword [$a];
				$this->LoginHost = $LoginHost [$a];
				$Found = true;
			}
			$a++;
		}
		if (!$Found)
			throw new Exception('No matching credentials available. (DB)');
		$this->DB = new mysqli($this->LoginHost, $this->DBusername, $this->DBpassword, $Database);
		if ($this->DB->connect_errno) {
			echo "Error: Failed to make a MySQL connection, here is why: \n";
			echo "Errno: " . $mysqli->connect_errno . "\n";
			echo "Error: " . $mysqli->connect_error . "\n";
			die (1);
		}
	}

	public function query($sql, $sensitive = false) {
		$result = $this->DB->query($sql);
		if(!$result) {
			$err = $this->DB->error;
			if ($sensitive === false)
				die('There was an error running the query [' . $err . ']');
			else
				echo ('\nThere was an error running the query [' . $err . ']');
		} else if ($result->num_rows === 0) {
			return 0;
		} else {
			return $result;
		}
	}

	public function modify ($sql, $sensitive = false) {
		$result = $this->DB->query($sql);
		if (!$result) {
			$err = $this->DB->error;
			if ($sensitive === false)
				die('There was an error running the command [' . $err . ']');
			else
				echo ('\nThere was an error running the command [' . $err . ']');
		} else
			return $result;
	}

	public function escape($string) {
		return $this->DB->real_escape_string($string);
	}

	public function close() {
		mysqli_close($this->DB);
	}

	public function __destruct() {
		mysqli_close($this->DB);
	}
}
?>
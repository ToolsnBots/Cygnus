<?php
include "DBPassword.php";
class DBCore extends DBPassword {
	protected $DBusername;
	protected $DBDBpassword;
	protected $database;
	protected $DB;

	public function __construct($Accountdata, $Database) {
		$a=0;
		$Found = false;
		$this->init();
		$LoginName = unserialize($this->getLoginName ());
		$LoginAccount = unserialize($this->getLoginAccount());
		$LoginDBpassword = unserialize($this->getLoginDBpassword());
		while (isset ($LoginName [$a]) === true) {
			if ($LoginName [$a] === $Accountdata) {
				$this->DBusername = $LoginAccount [$a];
				$this->DBpassword = $LoginDBpassword [$a];
				$Found = true;
			}
			$a++;
		}
		if (!$Found) {
			throw new Exception('Keine passenden DB-Anmeldeinformationen vorhanden.');
			die(1); // exit with error
		}
		$this->DB = new mysqli('127.0.0.1', $this->DBusername, $this->DBpassword, $Database);
		if ($this->DB->connect_errno) {
			echo "Error: Failed to make a MySQL connection, here is why: \n";
			echo "Errno: " . $mysqli->connect_errno . "\n";
			echo "Error: " . $mysqli->connect_error . "\n";
			die (1);
		}
	}
	public function query ($sql, $sensitive = false) {
		if(!$result = $this->DB->query($sql)) {
			if ($sensitive === false)
				die('There was an error running the query [' . $this->DB->error . ']');
			else
				echo ('\nThere was an error running the query [' . $this->DB->error . ']');
		} else if ($result->num_rows === 0) {
			return 0;
		} else {
			return $result;
		}
	}
	public function modify ($sql, $sensitive = false) {
		if(!$result = $this->DB->query($sql)) {
			if ($sensitive === false)
				die('There was an error running the command [' . $this->DB->error . ']');
			else
				echo ('\nThere was an error running the command [' . $this->DB->error . ']');
		}
		else 
			return $result;
	}
	function __destruct() {
		$this->DB->close();
	}
}
?>

<?php
require_once __DIR__ . "/DBPassword.php";
/** DBCore.php
* File for Database-Management of the Cygnus-Framework
* @author Freddy2001 <freddy2001@wikipedia.de>, Luke081515 <luke081515@tools.wmflabs.org>, MGChecker <hgasuser@gmail.com>
*/
class DBCore extends DBPassword {
	protected $DBusername;
	protected $DBpassword;
	protected $database;
	protected $DB;

	/** __construct
	* Initializes the database connection
	* @author Luke081515
	* @param $Accountdata - name of the accounts in DBPassword.php
	* @param $Database - name of the database that should get used
	*/
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
	/** query
	* Runs a query against the current database
	* @author Luke081515
	* @param $sql - the query statement
	* @param $sensitive - default: false. if false, the function will throw an exception in case that there is an error
	*/
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
	/** modify
	* Executes an sql statement that modifies the current database
	* @author Luke081515
	* @param $sql - the query statement
	* @param $sensitive - default: false. if false, the function will throw an exception in case that there is an error
	*/
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
	/** escape
	* Espaces and SQL string, used to make sure that there are no injections
	* @author MGChecker
	*/
	public function escape($string) {
		return $this->DB->real_escape_string($string);
	}
	/** close
	* Closes the connection
	*/
	public function close() {
		mysqli_close($this->DB);
	}
	/** __destruct
	* Closes the connection
	*/
	public function __destruct() {
		mysqli_close($this->DB);
	}
}
?>
<?php
require_once __DIR__ . "/DBPassword.php";
/** DBCore.php
* File for Database-Management of the Cygnus-Framework
* @author Freddy2001 <freddy2001@wikipedia.de>, Luke081515 <luke081515@tools.wmflabs.org>, MGChecker <hgasuser@gmail.com>
*/
class DBCore extends DBPassword {
	protected $dbUsername;
	protected $dbPassword;
	protected $database;
	protected $db;
	protected $loginHost;
	private $dbPasswordVersion = "2.1.0"; // Should be the same as in DBPassword.php, when you are changing the file.

	/** __construct
	* Initializes the database connection
	* @author Luke081515
	* @param $accountdata - name of the accounts in dbPassword.php
	* @param $database - name of the database that should get used
	*/
	public function __construct($accountdata, $database) {
		$a = 0;
		$found = false;
		$this->init();
		if (method_exists($this, 'getDBPasswordVersion')) {
			$passwordVersion = $this->getDBPasswordVersion();
		} else {
			throw new Exception("You are using an old version of DBPassword.php. Please upgrade.");
		}
		if ($this->dbPasswordVersion !== $passwordVersion) { // Ensuring no old version is used
			throw new Exception("You are using an old version of DBPassword.php. Please upgrade.");
		}
		$loginName = unserialize($this->getLoginName ());
		$loginAccount = unserialize($this->getLoginAccount());
		$loginDbPassword = unserialize($this->getLogindbPassword());
		$loginHost = unserialize($this->getLoginHost());
		while (isset($loginName [$a]) === true) {
			if ($loginName [$a] === $accountdata) {
				$this->dbUsername = $loginAccount [$a];
				$this->dbPassword = $loginDbPassword [$a];
				$this->loginHost = $loginHost [$a];
				$found = true;
			}
			$a++;
		}
		if (!$found)
			throw new Exception('No matching credentials available. (DB)');
		$this->db = new mysqli($this->loginHost, $this->dbUsername, $this->dbPassword, $database);
		if ($this->db->connect_errno) {
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
	* @return Returns the result, or 0 if you fetched an empty set.
	*/
	public function query($sql, $sensitive = false) {
		$result = $this->db->query($sql);
		if(!$result) {
			$err = $this->db->error;
			if ($sensitive === false) {
				die("There was an error running the query [" . $err . "]");
			} else {
				echo ("\nThere was an error running the query [" . $err . "]");
			}
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
	* @return Returns the result of the exeuction statement.
	*/
	public function modify ($sql, $sensitive = false) {
		$result = $this->db->query($sql);
		if (!$result) {
			$err = $this->db->error;
			if ($sensitive === false) {
				die("There was an error running the command [" . $err . "]");
			} else {
				echo ("\nThere was an error running the command [" . $err . "]");
			}
		} else {
			return $result;
		}
	}
	/** escape
	* Espaces and SQL string, used to make sure that there are no injections
	* @author MGChecker
	*/
	public function escape($string) {
		return $this->db->real_escape_string($string);
	}
	/** close
	* Closes the connection
	*/
	public function close() {
		mysqli_close($this->db);
	}
	/** __destruct
	* Closes the connection
	*/
	public function __destruct() {
		$this->close();
	}
}
?>
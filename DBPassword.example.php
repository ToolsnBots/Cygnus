<?php
class DBpassword {
	/** This is only an example
	* You need to modify the data to make the program work!
	*/
	private $loginName;
	private $loginHost;
	private $loginAccount;
	private $loginPassword;
	private $dbPasswordVersion = "2.1.0"; // Do not change this as user. As dev, change it, when you change that file.

	public function __construct() {}

	protected function init() {
		$this->loginName = array( // Recommended: Username@wiki
			'root@localhost',
		);
		// NOTE: Accounts need to follow the same order as at loginname
		$this->loginHost = array( // Domain
			'127.0.0.1',
		);
		$this->loginAccount = array( // Name of the account
			'root',
		);
		$this->loginPassword = array( // Password of the account
			'Password',
		);
	}
	protected function getLoginName() {
		return serialize ($this->loginName);
	}
	protected function getLoginHost() {
		return serialize ($this->loginHost);
	}
	protected function getLoginAccount() {
		return serialize ($this->loginAccount);
	}
	protected function getLoginDBpassword() {
		return serialize ($this->loginPassword);
	}
	protected function getDBPasswordVersion () {
		return $this->dbPasswordVersion;
	}
}
?>
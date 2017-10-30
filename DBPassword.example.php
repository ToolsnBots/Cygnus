<?php
class DBpassword {
	/** This is only an example
	* You need to modify the data to make the program work!
	*/
	private $LoginName;
	private $LoginHost;
	private $LoginAccount;
	private $LoginPassword;

	public function __construct() {}

	protected function init() {
		$this->LoginName = array( // Recommended: Username@wiki
			'root@localhost',
		);
		// NOTE: Accounts need to follow the same order as at loginname
		$this->LoginHost = array( // Domain
			'127.0.0.1',
		);
		$this->LoginAccount = array( // Name of the account
			'root',
		);
		$this->LoginPassword = array( // Password of the account
			'Password',
		);
	}
	protected function getLoginName() {
		return serialize ($this->LoginName);
	}
	protected function getLoginHost() {
		return serialize ($this->LoginHost);
	}
	protected function getLoginAccount() {
		return serialize ($this->LoginAccount);
	}
	protected function getLoginDBpassword() {
		return serialize ($this->LoginPassword);
	}
}
?>
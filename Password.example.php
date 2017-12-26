<?php
class Password {
	/** This is just an example!
	* For running any Job properly, the data needs to be adapted!
	* Do not forget chmod 0600!
	*/
	private $loginName;
	private $loginHost;
	private $loginAccount;
	private $loginPassword;
	private $mailAddress;
	private $target;
	private $passwordVersion = "2.1.0"; // Do not change this as user. As dev, change it, when you change that file.

	public function __construct() {}

	protected function init() {
		$this->loginName = array( // Recommended: Username@wiki
			'User@dewiki',
			'Bot@dewikisource',
		);
		// Please note, that the following settings need to be made in the same order as loginName!
		$this->loginHost = array( // Domain of the Wiki
			'de.wikipedia.org',
			'de.wikisource.org',
		);
		$this->loginAccount = array( // Name of the account
			'User',
			'Bot',
		);
		$this->loginPassword = array( // Password of the account
			'Userpassword',
			'Botpassword',
		);
		$this->mailAddress = array( // The script sends triggered mails to this address
			'hello@example.org',
			'support@example.org',
		);
		$this->target = array ( // Path to api.php. This can be obtained from Special:Version
			'w/api.php',
			'w/api.php',
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
	protected function getLoginPassword() {
		return serialize ($this->loginPassword);
	}
	protected function getMail() {
		return serialize ($this->mailAddress);
	}
	protected function getApiPath() {
		if (!isset($this->target)) {
			echo ("\nWARNING: No API path specified at Password.php. Using default one (w/api.php)");
			for ($i = 0; isset($this->LoginName[$i]); $i++) {
				$target[$i] = 'w/api.php';
			}
			return serialize ($target);
		}
		return serialize ($this->target);
	}
	protected function getPasswordVersion () {
		return $this->passwordVersion;
	}
}
?>
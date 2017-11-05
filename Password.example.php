<?php
class Password {
	/** This is just an example!
	* For running any Job properly, the data needs to be adapted!
	* Do not forget chmod 0600!
	*/
	private $LoginName;
	private $LoginHost;
	private $LoginAccount;
	private $LoginPassword;
	private $MailAddress;

	public function __construct() {}

	protected function init() {
		$this->LoginName = array( // Recommended: Username@wiki
			'User@dewiki',
			'Bot@dewikisource',
		);
		// Please note, that the following settings need to be made in the same order as LoginName!
		$this->LoginHost = array( // Domain of the Wiki
			'de.wikipedia.org',
			'de.wikisource.org',
		);
		$this->LoginAccount = array( // Name of the account
			'User',
			'Bot',
		);
		$this->LoginPassword = array( // Password of the account
			'Userpassword',
			'Botpassword',
		);
		$this->MailAddress = array( // The script sends triggered mails to this address
			'hello@example.org',
			'support@example.org',
		);
		$this->Target = array ( // Path to api.php. This can be obtained from Special:Version
			'w/api.php',
			'w/api.php',
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
	protected function getLoginPassword() {
		return serialize ($this->LoginPassword);
	}
	protected function getMail() {
		return serialize ($this->MailAddress);
	}
	protected function getApiPath() {
		return serialize ($this->Target);
	}
}
?>
<?php
class Password
{
	/** Dies ist eine Example-Datei
	* Damit das Programm funktioniert, muessen die Daten angepasst werden!
	* chmod 0600 nicht vergessen!
	*/
	private $LoginName;
	private $LoginHost;
	private $LoginAccount;
	private $LoginPassword;
	private $MailAddress;
	
	public function Password() {}
	
	protected function init() {
		$this->LoginName = array( // Empfohlen: Username@wiki
			'User@dewiki',
			'Bot@dewikisource',
		);
		# Bitte beachten, Accounts müssen in der selben Reihenfolge genannt werden, wie bei LoginName! #
		$this->LoginHost = array( // Internetdomain
			'de.wikipedia.org',
			'de.wikisource.org',
		);
		$this->LoginAccount = array( // Name das Accounts
			'User',
			'Bot',
		);
		$this->LoginPassword = array( // Passwort des Accounts
			'Userpassword',
			'Botpassword',
		);
		$this->MailAddress = array( // Mailadresse an die Daten gesendet werden koennen
			'hello@example.org',
			'support@example.org',
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
}
?>
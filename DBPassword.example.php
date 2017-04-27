<?php
class DBpassword {
	/** Dies ist eine Example-Datei
	* Damit das Programm funktioniert, muessen die Daten angepasst werden!
	*/
	private $LoginName;
	private $LoginHost;
	private $LoginAccount;
	private $LoginPassword;

	protected function init() {
		$this->LoginName = array( // Empfohlen: Username@wiki
			'root@localhost',
		);
		# Bitte beachten, Accounts müssen in der selben Reihenfolge genannt werden, wie bei LoginName! #
		$this->LoginHost = array( // Internetdomain
			'127.0.0.1',
		);
		$this->LoginAccount = array( // Name das Accounts
			'root',
		);
		$this->LoginPassword = array( // Passwort des Accounts
			'Password',
		);
	}
	protected function getLoginName () {
		return serialize ($this->LoginName);
	}
	protected function getLoginHost () {
		return serialize ($this->LoginHost);
	}
	protected function getLoginAccount () {
		return serialize ($this->LoginAccount);
	}
	protected function getLoginDBpassword () {
		return serialize ($this->LoginPassword);
	}
}
?>

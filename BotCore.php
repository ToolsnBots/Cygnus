<?php
require_once  __DIR__ . '/Password.php';
/** BotCore.php
* Zentrale Datei des Cygnus-Frameworks
* Aus dieser Datei werden alle bereitgestellten Methoden des Frameworks geladen
* @author Freddy2001 <freddy2001@wikipedia.de>, Hgzh, Luke081515 <luke081515@tools.wmflabs.org>, MGChecker <hgasuser@gmail.com>
* @requires extensions: JSON
* @version V2.1 beta
* Vielen Dank an alle, die zu diesem Framework beigetragen haben
*/
class Core extends password {
	protected $username;
	protected $password;
	protected $curlHandle;
	protected $site;
	protected $protocol;
	protected $job;
	protected $assert;
	protected $mail;
	protected $mailcontent;
	private $version;
	private $ua;

	/** initcurl
	* initialisiert curl
	* Diese Methode sollte im Normallfall aufgerufen werden
	* Erstellt das Verbindungsobjekt und loggt den Bot ein
	* @author Hgzh
	* @param $account - Name des angegebenen Accounts in Password.php
	* @param $job - Name des Jobs; dient zur Internen Speicherung der Cookies
	* @param $pUseHTTPS - [Optional: true] falls auf false gesetzt, benutzt der Bot http statt https
	* @param $assert - [Optional: bot] falls auf 'user' gesetzt, kann auch ohne Flag edits gemacht werden
	*/
	public function initcurl($account, $job, $pUseHTTPS = true, $assert = 'bot') {
		$this->version = 'Cygnus-Framework V2.1 beta';
		if ($assert !== 'bot' && $assert !== 'user')
			throw new Exception('assert has to be \'bot\' or \'user\'');
		$this->assert = $assert;
		$this->start($account);
		$this->job = $job;
		if ($pUseHTTPS === true) 
			$this->protocol = 'https';
		else 
			$this->protocol = 'http';
		// init curl
		$curl = curl_init();
		if ($curl === false)
			throw new Exception('Curl initialization failed.');
		else
			$this->curlHandle = $curl;
		$this->login();
		echo '\n***** Starting up....\nVersion: ' . $this->version . '\n*****';
		$this->ua = 'User:' . $this->username . ' - ' . $this->job . ' - ' . $this->version;
	}
	/** initcurlArgs
	* Benutze diese Funktion anstatt initcurl, wenn du das Passwort des Bots via args mitgeben willst
	* Ansonsten bitte initcurl benutzen
	* Erstellt das Verbindungsobjekt und loggt den Bot ein
	* @author Luke081515
	* @param $job - Name des Jobs; dient zur Internen Speicherung der Cookies
	* @param $pUseHTTPS - [Optional: true] falls auf false gesetzt, benutzt der Bot http statt https
	* @param $assert - [Optional: bot] falls auf 'user' gesetzt, kann auch ohne Flag edits gemacht werden
	*/
	public function initcurlArgs($job, $pUseHTTPS = true, $assert = 'bot') {
		if ($assert !== 'bot' && $assert !== 'user')
			exit(1);
		$this->assert = $assert;
		$this->job = $job;
		if ($pUseHTTPS === true) 
			$this->protocol = 'https';
		else 
			$this->protocol = 'http';
		// init curl
		$curl = curl_init();
		if ($curl === false)
			throw new Exception('Curl initialization failed.');
		else
			$this->curlHandle = $curl;
		echo '\n***** Starting up....\nVersion: ' . $this->version . '\n*****';
		$this->ua = 'User:' . $this->username . ' - ' . $this->job . ' - ' . $this->version;
	}
	public function __construct($account, $job, $pUseHTTPS = true) {}
	public function __destruct() {
		curl_close($this->curlHandle);
	}
	/** httpRequest
	* führt http(s) request durch
	* Wird meistens benutzt um die API anzusteuern
	* @param $pArguments - API-Parameter die aufgerufen werden sollen (beginnt normalerweise mit action=)
	* @param $job - Jobname, wird benutzt um die richtigen Cookies etc zu finden. Hier einfach $this->job benutzen.
	* @param $pMethod - [optional: POST] Methode des Requests. Bei querys sollte stattdessen GET genommen werden
	* @param $pTarget - [optional: w/api.php] Verwende diesen Parameter, wenn die API deines Wikis einen anderen Einstiegspfad hat. (Special:Version)
	* @author Hgzh
	* @returns Antwort der API
	*/
	protected function httpRequest($arguments, $job, $method = 'POST', $target = 'w/api.php') {
		$baseURL = $this->protocol . '://' . 
				   $this->site . '/' . 
				   $target;
		$method = strtoupper($method);
		if ($arguments != '') {
			if ($method === 'POST') {
				$requestURL = $baseURL;
				$postFields = $arguments;
			} else if ($method === 'GET') {
				$requestURL = $baseURL . '?' .
							  $arguments;
			} else 
				throw new Exception('Unknown http request method.');
		}
		if (!$requestURL) 
			throw new Exception('No arguments for http request found.');
		// set curl options
		curl_setopt($this->curlHandle, CURLOPT_USERAGENT, $this->ua);
		curl_setopt($this->curlHandle, CURLOPT_URL, $requestURL);
		curl_setopt($this->curlHandle, CURLOPT_ENCODING, 'UTF-8');
		curl_setopt($this->curlHandle, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->curlHandle, CURLOPT_COOKIEFILE, realpath('Cookies' . $job . '.tmp'));
		curl_setopt($this->curlHandle, CURLOPT_COOKIEJAR, realpath('Cookies' . $job . '.tmp'));
		// if posted, add post fields
		if ($method === 'POST' && $postFields != '') {
			curl_setopt($this->curlHandle, CURLOPT_POST, 1);
			curl_setopt($this->curlHandle, CURLOPT_POSTFIELDS, $postFields);
		} else {
			curl_setopt($this->curlHandle, CURLOPT_POST, 0);
			curl_setopt($this->curlHandle, CURLOPT_POSTFIELDS, '');
		}
		// perform request
		$rqResult = curl_exec($this->curlHandle);
		if ($rqResult === false)
			throw new Exception('Curl request failed: ' . curl_error($this->curlHandle));
		return $rqResult;
	}
	/** login
	* loggt den Benutzer ein
	* Nicht! verwenden. Diese Methode wird nur von initcurl/initcurlargs genutzt.
	* @author Hgzh
	*/
	public function login() {
		// get login token
		try {
			$result = $this->httpRequest('action=query&format=json&meta=tokens&type=login', $this->job, 'GET');
		} catch (Exception $e) {
			throw $e;
		}
		$tree = json_decode($result, true);
		$lgToken = $tree['query']['tokens']['logintoken'];
		if ($lgToken === '')
			throw new Exception('Could not receive login token.');	
		// perform login
		try {
			$result = $this->httpRequest('action=login&format=json&lgname=' . urlencode($this->username) . 
				'&lgpassword=' . urlencode($this->password) . 
				'&lgtoken=' . urlencode($lgToken), $this->job);
		} catch (Exception $e) {
			throw $e;
		}
		$tree = json_decode($result, true);
		$lgResult = $tree['login']['result'];
		// manage result
		if ($lgResult == 'Success')
			return true;
		else
			throw new Exception('Login failed with message ' . $lgResult);
	}
	/** logout
	* Loggt den Benutzer aus
	*/
	public function logout() {
		try {
			$this->httpRequest('action=logout', $this->job);
		} catch (Exception $e) {
			throw $e;		
		}
	}
	/** DO NOT USE this function
	* This is for unit-tests only
	*/
	public function setSite($site) {
		$this->site = $site;
	}
	/** DO NOT USE this function
	* This is for unit-tests only
	*/
	public function setUsername($username) {
		$this->username = $username;
	}
	/** DO NOT USE this function
	* This is for unit-tests only
	*/
	public function setPassword($password) {
		$this->password = $password;
	}
	/** start
	* Sucht Logindaten aus Password.php, führt anschließend Login durch
	* Sollte im Normalfall nicht manuell angewendet werden, dies macht bereits initcurl
	* @author Luke081515
	*/
	public function start($account) {
		$a=0;
		$Found = false;
		$this->init();
		$LoginName = unserialize($this->getLoginName());
		$LoginHost = unserialize($this->getLoginHost());
		$LoginAccount = unserialize($this->getLoginAccount());
		$LoginPassword = unserialize($this->getLoginPassword());
		$Mail = unserialize($this->getMail());
		while (isset($LoginName[$a])) {
			if ($LoginName[$a] === $account) {
				$this->site = $LoginHost[$a];
				$this->username = $LoginAccount[$a];
				$this->password = $LoginPassword[$a];
				$this->mail = $Mail[$a];
				$Found = true;
			}
			$a++;
		}
		if (!$Found) {
			throw new Exception('Keine passenden Anmeldeinformationen vorhanden.');
		}
	}
	/** checkResult
	* Diese Methode ist fuer interne Verwendung bestimmt
	* Sie steht daher auf private
	* Sie wird aufgerufen, falls es einen Fehler gibt
	* Je nach Fehler werden entsprechende Aktionen eingeleitet
	* @author Luke081515
	* @param $result - Fehlercode der API
	* @returns fail - Edit fehlgeschlagen, Fehlercode zeigt, das ein erneuter versuch nicht sinnvoll ist
	* @returns retry - Ein erneuter Versuch kann das Problem beheben
	* @returns conflict - Ein Bearbeitungskonflikt ist vorhanden 
	*/
	private function checkResult($result) {
		if ($result === 'maxlag' || $result === 'readonly' || $result === 'unknownerror-nocode' || $result === 'unknownerror' || $result === 'ratelimited') {
			echo '\nEdit fehlgeschlangen. Grund: $result. Versuche es erneut';
			return 'retry';
		} else if ($result === 'blocked' || $result === 'confirmemail' || $result === 'autoblocked') {
			throw new Exception('Du kannst in der nahen Zukunft keine Aktionen ausfuehren. Grund: $result');
		} else if ($result === 'assertuserfailed' || $result === 'assertbotfailed') {
			$this->login();
			return 'retry';
		} else if ($result === 'editconflict') {
			echo '\nBearbeitungskonflikt festgestellt';
			return 'conflict';
		} else {
			echo 'Aktion fehlgeschlagen. Fehlercode: $result';
			return 'fail';
		}
	}
	/** readPageEngine
	* Interne Methode, um die Ergebnisse einer Anfrage zum Auslesen einer Seite zu bearbeiten
	* @param $request – Die API-Abfrage, die den Seiteninhalt ausgibt
	* @author Luke081515
	* @returns Text der Seite
	*/
	private function readPageEngine($request) {
		try {
			$page = json_decode($this->httpRequest($request, $this->job, 'GET'), true);
		} catch (Exception $e) {
			throw $e;
		}
		$pageID = $page['query']['pageids'][0];
		return $text['query']['pages'][$pageID]['revisions'][0]['*'];
	}
	/** readPage
	* Liest eine Seite aus
	* @param $title - Titel der auszulesenden Seite
	* @author MGChecker
	* @returns Text der Seite
	*/
	public function readPage($title) {
		$request = 'action=query&prop=revisions&format=json&rvprop=content&rvlimit=1&rvcontentformat=text%2Fx-wiki&titles=' . urlencode($title) . 
			'&rvdir=older&rawcontinue=&indexpageids=1';
		return $this->readPageEngine($request);
	}
	/** readPageId
	* Liest eine Seite aus
	* @param $pageID - ID der auszulesenden Seite
	* @author MGChecker
	* @returns Text der Seite
	*/
	public function readPageID($pageID) {
		$request = 'action=query&prop=revisions&format=json&rvprop=content&rvlimit=1&rvcontentformat=text%2Fx-wiki&pageids=' . urlencode($pageID) . 
			'&rvdir=older&rawcontinue=&indexpageids=1';
		return $this->readPageEngine($request);
    }
    /** readPageJs
	* Liest eine JavaScript-Seite aus
	* @param $title - Titel der auszulesenden Seite
	* @author MGChecker
	* @returns Text der Seite
	*/
	public function readPageJs($title) {
		$request = 'action=query&prop=revisions&format=json&rvprop=content&rvlimit=1&rvcontentformat=text%2Fjavascript&titles=' . urlencode($title) . 
			'&rvdir=older&rawcontinue=&indexpageids=1';
		return $this->readPageEngine($request);
	}
	/** readPageCss
	* Liest eine CSS-Seite aus
	* @param $title - Titel der auszulesenden Seite
	* @author MGChecker
	* @returns Text der Seite
	*/
	public function readPageCss($title) {
		$request = 'action=query&prop=revisions&format=json&rvprop=content&rvlimit=1&rvcontentformat=text%2Fcss&titles=' . urlencode($title) . 
			'&rvdir=older&rawcontinue=&indexpageids=1';
		return $this->readPageEngine($request);
	}
	/** readSection
	* Liest einen Abschnitt einer Seite aus
	* @param $title - Titel der Auszulesenden Seite
	* @param $section Nummer des Abschnitts
	* @author MGChecker
	* @returns Text des Abschnitts
	*/
	public function readSection($title, $section) {
		$request = 'action=query&prop=revisions&format=json&rvprop=content&rvlimit=1&rvcontentformat=text%2Fx-wiki&rvdir=older&indexpageids=1&rvsection=' . urlencode($section) . 
			'&titles=' . urlencode($title);
		return $this->readPageEngine($request);
	}
	/** getTableOfContents
	* Gibt das Inhaltsverzeichnis einer Seite aus
	* @param $page - Titel der Seite
	* @author Luke081515
	* @returns Zwei dimensionales Array
	* @returns Erste Dimension: Der entsprechende Abschnitt
	* @retuns Zweite Dimension: 
	* 	[0] => level;
	* 	[1] => Titel des Abschnitts;
	* 	[2] => Abschnittsnummer im Inhaltsverzeichnis (z.B. auch 7.5);
	* 	[3] => Abschnittsnummer, ohne Komma, reiner int;
	*/
	public function getTableOfContents($page) {
		try {
			$result = $this->httpRequest('action=parse&format=json&maxlag=5&page=' . urlencode($page) . '&prop=sections', $this->job, 'GET');
		} catch (Exception $e) {
			throw $e;
		}
		$Data = json_decode($result, true);
		$a=0;
		while (isset($Data['parse']['sections'][$a]['level'])) {
			$ret[$a][0] = $Data['parse']['sections'][$a]['level'];
			$ret[$a][1] = $Data['parse']['sections'][$a]['line'];
			$ret[$a][2] = $Data['parse']['sections'][$a]['number'];
			$ret[$a][3] = $Data['parse']['sections'][$a]['index'];
			$a++;
		}
		return $ret;
	}
	/** editPageEngine
	* Interne Methode, die die eigentliche Bearbeitung durchführt. Stattdessen eine der folgenden 6 Funktionen nutzen.
	* @param $title - Seitenname
	* @param $content - Neuer Seitentext
	* @param $summary - Zusammenfassung
	* @param $botflag - Falls true wird der Edit mit Botflag markiert
	* @param $minorflag - Falls true wird der Edit als Klein markiert
	* @param $noCreate - Soll die Seite ggf neu angelegt werden? (Standard => nein)
	* @param $sectionnumber - Welcher Abschnitt soll bearbeitet werden? (Standard => ganze Seite)
	* @param $overrideNobots - Soll die NoBots Vorlage ignoriert werden?
	* @author Hgzh / Luke081515 / MGChecker
	* @returns Unserialisierte Antwort der API, falls der Edit erfolgreich war
	*/
	private function editPageEngine($title, $content, $summary, $botflag, $minorflag, $noCreate = 1, $sectionnumber = -1, $overrideNobots = false) {
		retry:
		if ($overrideNobots !== true) {
			if ($this->allowBots($this->readPage($title)) === false)
				return 'nobots';
		}
		// get csrf token
		try {
			$result = $this->httpRequest('action=query&format=json&meta=tokens&type=csrf', $this->job, 'GET');
		} catch (Exception $e) {
			throw $e;
		}
		$tree  = json_decode($result, true);
		$token = $tree['query']['tokens']['csrftoken'];
		if ($token === '')
			throw new Exception('could not receive csrf token.');
		// perform edit
		$request = 'action=edit&assert=' . $this->assert . '&format=json&bot=&title=' . urlencode($title) . 
			'&text=' . urlencode($content) . 
			'&token=' . urlencode($token) . 
			'&summary=' . urlencode($summary) .
			'&bot=' . urlencode($botflag) . 
			'&minor=' . urlencode($minorflag);
		if ($noCreate === 1)
			$request .= '&minor=' . urlencode($minorflag);
		if ($sectionnumber !== -1)
			$request .= '&section=' . urlencode($sectionnumber);
		try {
			$result = $this->httpRequest($request, $this->job);
		} catch (Exception $e) {
			throw $e;
		}
		$tree = json_decode($result, true);
		$editres = $tree['edit']['result'];
		// manage result
		if ($editres == 'Success') {
			if (array_key_exists('nochange', $tree['edit'])) {
				return array('nochange');
			} else {
				return array($tree['edit']['oldrevid'], $tree['edit']['newrevid']);
			}
		}
		else {
			$Code = $this->checkResult($editres);
			if ($Code === 'fail')
				return 'fail';
			else if ($Code === 'retry')
				goto retry;
			else if ($Code === 'conflict')
				return 'conflict';
		}
	}
	/** editPage
	* Bearbeitet eine Seite
	* @param $title - Seitenname
	* @param $content - Neuer Seitentext
	* @param $summary - Zusammenfassung
	* @param $noCreate - Soll die Seite ggf neu angelegt werden? (Standard => nein)
	* @param $overrideNobots - Soll trotz Verbot des Bots per Vorlage bearbeitet werden? (Standard => nein)
	* @author Freddy2001 / Luke081515
	* @returns Unserialisierte Antwort der API, falls der Edit erfolgreich war
	*/
	public function editPage($title, $content, $summary, $noCreate = 1, $overrideNobots = false) {
		if ($this->assert == 'bot')
			$botflag = true;
		else
			$botflag = false;
		return $this->editPageEngine($title, $content, $summary, $botflag, false, $noCreate, -1, $overrideNobots);
	}
	/** editPageMinor
	* Bearbeitet eine Seite als kleine Bearbeitung
	* @param $title - Seitenname
	* @param $content - Neuer Seitentext
	* @param $summary - Zusammenfassung
	* @param $noCreate - Soll die Seite ggf neu angelegt werden? (Standard => nein)
	* @param $overrideNobots - Soll trotz Verbot des Bots per Vorlage bearbeitet werden? (Standard => nein)
	* @author Freddy2001 / Luke081515
	* @returns Unserialisierte Antwort der API, falls der Edit erfolgreich war
	*/
	public function editPageMinor($title, $content, $summary, $noCreate = 1, $overrideNobots = false) {
		if ($this->assert == 'bot')
			$botflag = true;
		else
			$botflag = false;
		return $this->editPageEngine($title, $content, $summary, $botflag, true, $noCreate, -1, $overrideNobots);
	}
	/** editPageD
	* Bearbeitet eine Seite
	* @param $title - Seitenname
	* @param $content - Neuer Seitentext
	* @param $summary - Zusammenfassung
	* @param $botflag - Falls true wird der Edit mit Botflag markiert
	* @param $minorflag - Falls true wird der Edit als Klein markiert
	* @param $noCreate - Soll die Seite ggf neu angelegt werden? (Standard => nein)
	* @param $overrideNobots - Soll trotz Verbot des Bots per Vorlage bearbeitet werden? (Standard => nein)
	* @author MGChecker / Luke081515
	* @returns Unserialisierte Antwort der API, falls der Edit erfolgreich war
	*/
	public function editPageD($title, $content, $summary, $botflag, $minorflag, $noCreate = 1, $overrideNobots = false) {
		return $this->editPageEngine($title, $content, $summary, $botflag, $minorflag, $noCreate, -1, $overrideNobots);
	}
	/** editSection
	* Bearbeitet einen Abschnitt
	* @param $title - Seitenname
	* @param $content - Neuer Seitentext
	* @param $summary - Zusammenfassung
	* @param $sectionnumber - Nummer des Abschnitts
	* @param $noCreate - Soll die Seite ggf neu angelegt werden? (Standard => nein)
	* @param $overrideNobots - Soll trotz Verbot des Bots per Vorlage bearbeitet werden? (Standard => nein)
	* @author Freddy2001 / MGChecker / Luke081515
	* @returns Unserialisierte Antwort der API, falls der Edit erfolgreich war
	*/
	public function editSection($title, $content, $summary, $sectionnumber, $noCreate = 1, $overrideNobots = false) {
		if ($this->assert == 'bot')
			$botflag = true;
		else
			$botflag = false;
		if ($sectionnumber < 0)
			throw new Exception('You selected a invalid section number. To edit a whole page, use editPage().');
		return $this->editPageEngine($title, $content, $summary, $botflag, false, $noCreate, $sectionnumber, $overrideNobots);
	}
	/** editSectionMinor
	* Bearbeitet einen Abschnitt
	* @param $title - Seitenname
	* @param $content - Neuer Seitentext
	* @param $summary - Zusammenfassung
	* @param $sectionnumber - Nummer des Abschnitts
	* @param $noCreate - Soll die Seite ggf neu angelegt werden? (Standard => nein)
	* @param $overrideNobots - Soll trotz Verbot des Bots per Vorlage bearbeitet werden? (Standard => nein)
	* @author Freddy2001 / MGChecker / Luke081515
	* @returns Unserialisierte Antwort der API, falls der Edit erfolgreich war
	*/
	public function editSectionMinor($title, $content, $summary, $sectionnumber, $noCreate = 1, $overrideNobots = false) {
		if ($this->assert == 'bot')
			$botflag = true;
		else
			$botflag = false;
		if ($sectionnumber < 0)
			throw new Exception('You selected a invalid section number. To edit a whole page, use editPageMinor().');
		return $this->editPageEngine($title, $content, $summary, $botflag, true, $noCreate, $sectionnumber, $overrideNobots);
	}
	/** editSectionD
	* Bearbeitet eine Seite (Auswahl weitere Parameter moeglich)
	* @param $title - Seitenname
	* @param $content - Neuer Seitentext
	* @param $summary - Zusammenfassung
	* @param $botflag - Falls true wird der Edit mit Botflag markiert
	* @param $minorflag - Falls true wird der Edit als Klein markiert
	* @param $sectionnumber - Nummer des Abschnitts
	* @param $noCreate - Soll die Seite ggf neu angelegt werden? (Standard => nein)
	* @param $overrideNobots - Soll trotz Verbot des Bots per Vorlage bearbeitet werden? (Standard => nein)
	* @author MGChecker / Luke081515
	* @returns Unserialisierte Antwort der API, falls der Edit erfolgreich war
	*/
	public function editSectionD($title, $content, $summary, $sectionnumber, $botflag, $minorflag, $noCreate = 1, $overrideNobots = false) {
		if ($sectionnumber < 0)
			throw new Exception('You selected a invalid section number. To edit a whole page, use editPageD().');
		return $this->editPageEngine($title, $content, $summary, $botflag,  $minorflag, $noCreate, $sectionnumber, $overrideNobots);
	}
	/** movePage
	* Verschiebt eine Seite
	* @param $startLemma - Alter Titel der Seite
	* @param $targetLemma - Neuer Titel der Seite
	* @param - $bot (default: 0) - Botflag setzen?
	* @param - $movetalk (default: 1) - Diskussionsseite mitverschieben?
	* @param - $noredirect - (default: 1) - Weiterleitung erstellen?
	* @param $reason - Grund der Verschiebung, der im Log vermerkt wird
	* @returns Serialisierte Antwort der API-Parameter
	*/
	public function movePage($startLemma, $targetLemma, $reason, $bot = 0, $movetalk = 1, $noredirect = 1) {
		$data = 'action=query&format=json&meta=tokens&type=csrf';
		try {
			$result = $this->httpRequest($data, $this->job, 'GET');
		} catch (Exception $e) {
			throw $e;
		}
		$answer = json_decode($result, true);
		$token = $answer['query']['tokens']['csrftoken'];
		$data = 'action=move&format=json&assert=' . $this->assert . 
			'&from=' . urlencode($startLemma) . 
			'&to=' . urlencode($targetLemma) . 
			'&reason=' . urlencode($reason) . 
			'&bot=' . $bot . 
			'&movetalk=' . $movetalk . 
			'&noredirect=' . $noredirect . 
			'&token=' . urlencode($token);
		try {
			$result = $this->httpRequest($data, $this->job);
		} catch (Exception $e) {
			throw $e;
		}
		return serialize(json_decode($result, true));
	}
	/** getCatMembers
	* Liest alle Seiten der Kategorie aus, auch Seiten die in Unterkategorien der angegebenen Kategorie kategorisiert sind
	* Funktioniert bis zu 5000 Unterkategorien pro Katgorie (nicht 5000 Unterkategorien insgesamt)
	* Erfordert Botflag, da Limit auf 5000 gesetzt, (geht zwar sonst auch, aber nur mit Warnung)
	* @author Luke081515
	* @param $kat - Kategorie die analyisiert werden soll.
	* @param $onlySubCats - [optional: false] Falls true, werden nur die Unterkategorien, nicht die Titel der Seiten weitergegeben
	* @param $excludeWls - [optional: false] Falls true, werden keine Kategorien mit Weiterleitungen weitergegeben
	* @returns false, falls keine Seiten vorhanden, ansonsten serialisiertes Array mit Seitentiteln
	*/
	public function getCatMembers($kat, $onlySubCats = false, $excludeWls = false) {
		$b=0;
		$subCat[0] = $kat;
		try {
			$result = $this->httpRequest('action=query&list=categorymembers&format=json&cmtitle=' . urlencode($kat) . 
				'&cmprop=title&cmtype=subcat&cmlimit=5000&cmsort=sortkey&cmdir=ascending&rawcontinue=', $this->job, 'GET');
		} catch (Exception $e) {
			throw $e;
		}
		$answer = json_decode($result, true);
		$a=0;
		if (isset($answer['query']['categorymembers'][$a]['title'])) {
			$Sub = true;
			while (isset($answer['query']['categorymembers'][$a]['title'])) {
				$subCat[$b] = $answer['query']['categorymembers'][$a]['title'];	
				$b++;
				$a++;
			}
		}
		$b=0;
		$c=0;
		if ($onlySubCats === true)
			return $subCat;
		if ($excludeWls === false) {	
			while (isset($subCat[$b]))
			{
				try {
					$result = $this->httpRequest('action=query&list=categorymembers&format=json&cmtitle=' . urlencode($subCat[$b]) . 
						'&cmprop=title&cmtype=page&cmlimit=5000&cmsort=sortkey&cmdir=ascending&rawcontinue=', $this->job, 'GET');
				} catch (Exception $e) {
					throw $e;
				}
				$answer = json_decode($result, true);
				$Cont = false;
				if (isset($answer['query-continue']['categorymembers']['cmcontinue'])) {
					$Continue = $answer['query-continue']['categorymembers']['cmcontinue'];
					$Cont = true;
				}
				while ($Cont === true) {
					$a=0;
					if (isset($answer['query']['categorymembers'][$a]['title'])) {
						while (isset($answer['query']['categorymembers'][$a]['title'])) {
							$page[$c] = $answer['query']['categorymembers'][$a]['title'];	
							$c++;
							$a++;
						}
					} else  {}
					try {
						$result = $this->httpRequest('action=query&list=categorymembers&format=json&cmcontinue=' . $Continue 
						. '&cmtitle=' . urlencode($subCat[$b]) 
						. '&cmprop=title&cmtype=page&cmlimit=5000&cmsort=sortkey&cmdir=ascending&rawcontinue=', $this->job, 'GET');
					} catch (Exception $e) {
						throw $e;
					}
					$answer = json_decode($result, true);
					if (isset($answer['query-continue']['categorymembers']['cmcontinue'])) {
						$Continue = $answer['query-continue']['categorymembers']['cmcontinue'];
						$Cont = true;
					} else
						$Cont = false;
				}
				$a=0;
				if (isset($answer['query']['categorymembers'][$a]['title']) === true) {
					while (isset($answer['query']['categorymembers'][$a]['title'])) {
						$page[$c] = $answer['query']['categorymembers'][$a]['title'];	
						$c++;
						$a++;
					}
				} else {}
				$b++;
			}
		} else {
			while (isset($subCat[$b])) {
				try {
					$result = $this->httpRequest('action=query&format=json&generator=categorymembers&gcmtitle=' . urlencode($subCat[$b]) . 
						'&prop=info&gcmlimit=5000&rawcontinue=&redirects', $this->job, 'GET');
				} catch (Exception $e) {
					throw $e;
				}
				$answer = json_decode($result, true);
				$Cont = false;
				if (isset($answer['query-continue']['categorymembers']['gcmcontinue'])) {
					$Continue = $answer['query-continue']['categorymembers']['gcmcontinue'];
					$Cont = true;
				}
				while ($Cont === true) {
					$a=0;
					if (isset($answer['query']['pages'][$a]['title'])) {
						while (isset($answer['query']['pages'][$a]['title'])) {
							$page[$c] = $answer['query']['pages'][$a]['title'];	
							$c++;
							$a++;
						}
					} else  {}
					try {
						$result = $this->httpRequest('action=query&format=json&generator=categorymembers&gcmtitle=' . urlencode($subCat[$b]) . 
							'&gmcontinue=' . $Continue . 
							'&prop=info&gcmlimit=5000&rawcontinue=&redirects', $this->job, 'GET');
					} catch (Exception $e) {
						throw $e;
					}
					$answer = json_decode($result, true);
					if (isset($answer['query-continue']['pages']['gcmcontinue'])) {
						$Continue = $answer['query-continue']['pages']['gcmcontinue'];
						$Cont = true;
					} else
						$Cont = false;
				}
				$a=0;
				if (isset($answer['query']['pages'][$a]['title'])) {
					while (isset($answer['query']['pages'][$a]['title'])) {
						$page[$c] = $answer['query']['pages'][$a]['title'];	
						$c++;
						$a++;
					}
				} else {}
				$b++;
			}
		}
		if (!isset($page[0]))
				return false;
		else
			return serialize($page);
	}
	/** getPageCats
	* Liest alle Kategorien einer Seite aus
	* Funktioniert bis zu 500 Kategorien einer Seite
	* Erfordert Botflag, da Limit auf 5000 gesetzt
	* @author Luke081515
	* @param $page - Seite die analyisiert werden soll
	* @returns Alle Kategorien als serialisiertes Array
	*/
	public function getPageCats($page) {
		try {
			$cats = $this->httpRequest('action=query&prop=categories&format=json&cllimit=5000&titles=' . urlencode($Page) . 
				'&cldir=ascending&rawcontinue=&indexpageids=1', $this->job, 'GET');
			$cats = json_decode($cats, true);
		} catch (Exception $e) {
			throw $e;
		}
		$pageID = $cats['query']['pageids'][0];
		$a=0;
		while (isset($cats['query']['pages'][$pageID]['categories'][$a])) {
			$catResults[$a] = $cats['query']['pages'][$pageID]['categories'][$a];
			$a++;
		}
		if (!isset($catResults[0]))
			return false;
		return serialize($catResults);
	}
	/** getAllEmbedings
	* Liest alle Einbindungen einer Vorlage aus
	* Erfordert Botflag, da Limit auf 5000 gesetzt
	* @author Luke081515
	* @param Vorlage, deren Einbindungen aufgelistet werden sollen
	* @returns false, falls keine Einbindungen vorhanden, ansonsten serialisiertes Array mit Seitentiteln
	*/
	public function getAllEmbedings($templ) {
		$b=0;
		$Again = true;
		while ($Again === true) {
			if (isset($Continue))
				$data = 'action=query&list=embeddedin&format=json&eititle=' . urlencode($templ) . 
					'&einamespace=0&eicontinue=' . urlencode($Continue) . 
					'&eidir=ascending&eilimit=5000&rawcontinue=';
			else
				$data = 'action=query&list=embeddedin&format=json&eititle=' . urlencode($templ) . '&einamespace=0&eidir=ascending&eilimit=5000&rawcontinue=';
			try {
				$result = $this->httpRequest($data, $this->job, 'GET');
			} catch (Exception $e) {
				throw $e;
			}
			$answer = json_decode($result, true);
			$a=0;
			if (isset($answer['query-continue']['embeddedin']['eicontinue'])) {
				$Continue = $answer['query-continue']['embeddedin']['eicontinue'];
				$Again = true;
			}
			else
				$Again = false;
			if (isset($answer['query']['embeddedin'][$a]['title'])) {
				while (isset($answer['query']['embeddedin'][$a]['title'])) {
					$page[$b] = $answer['query']['embeddedin'][$a]['title'];
					$b++;
					$a++;
				}
			} else
				return false;
		}
		return serialize($page);
	}
	/** getAllPages
	* Liest alle Seiten eines Namensraumes aus
	* Erfordert Botflag, da Limit auf 5000 gesetzt (geht zwar sonst auch, aber nur mit Warnung)
	* @author Luke081515
	* @param Nummer des Namensraumes, von dem die Seiten ausgelesen werden
	* @returns false, falls keine Seiten im Namensraum vorhanden, ansonsten serialisiertes Array mit Seitentiteln
	*/
	public function getAllPages($namespace) {
		$b=0;
		$Again = true;
		while ($Again === true) {
			if (isset($Continue))
				$data = 'action=query&list=allpages&format=json&apcontinue=' . $Continue . '&apnamespace=' . $namespace . '&aplimit=5000&apdir=ascending&rawcontinue=';
			else
				$data = 'action=query&list=allpages&format=json&apnamespace=' . $namespace . '&aplimit=5000&apdir=ascending&rawcontinue=';
			try {
				$result = $this->httpRequest($data, $this->job, 'GET');
			} catch (Exception $e) {
				throw $e;
			}
			$answer = json_decode($result, true);
			$a=0;
			if (isset($answer['query-continue']['allpages']['apcontinue'])) {
				$Continue = $answer['query-continue']['allpages']['apcontinue'];
				$Again = true;
			} else
				$Again = false;
			if (isset($answer['query']['allpages'][$a]['title'])) {
				while (isset($answer['query']['allpages'][$a]['title'])) {
					$page[$b] = $answer['query']['allpages'][$a]['title'];
					$b++;
					$a++;
				}
			} else
				return false;
		}
		return serialize($page);
	}
	/** getPageID
	* Gibt zu der angegebenen Seite die ID an
	* @author Luke081515
	* @param $page - Name der Seite
	* @returns int: PageID, bool: false falls Seite nicht vorhanden
	*/
	public function getPageID($page) {
		$data = 'action=query&format=json&maxlag=5&prop=info&indexpageids=1&titles=' . urlencode($page);
		try {
			$result = $this->httpRequest($data, $this->job, 'GET');
		} catch (Exception $e) {
			throw $e;
		}
		if (strpos ($result, 'missing') !== false)
			return false;
		$answer = json_decode($result, true);
		return $tree['query']['pageids'][0];
	}
	/** getLinks
	* Gibt aus, welche Wikilinks sich auf einer Seite befinden, maximal 5000
	* @author Luke081515
	* @param $page - Seite die analysiert wird
	* @returns Array mit Ergebnissen
	*/
	public function getLinks($page) {
		$data = 'action=query&prop=links&format=json&pllimit=5000&pldir=ascending&plnamespace=0&rawcontinue=&indexpageids=1&titles=' . urlencode($page);
		try {
			$result = json_decode($this->httpRequest($data, $this->job, 'GET'), true);
		} catch (Exception $e) {
			throw $e;
		}
		while (isset($result['query']['pages'][$pageID]['links'][0]['title'])) {
		$pageID = $result['query']['pageids'][0];
		}
		if (isset($links[0]))
			return $links;
		return false;
	}
	/** getSectionTitle
	* Gibt Titel und Ebene eines Abschnittes zurück
	* @author Freddy2001
	* @param title - Titel der Seite
	* @param section - Abschnitt der Seite
	* @return Titel und Überschriftenebene als Array
	*/
	public function getSectionTitle($title, $section) {
		$content = $this->readSection($title, $section);
		$sectionlevel = 5;
		while($sectionlevel > 1) {
			$searchnum = 1;
			$search = '=';
			while($searchnum < $sectionlevel) {
				$search = $search . '=';
				$searchnum++;
			}
			if (strpos(substr($content, strpos($content, '='), 5), $search) === false) {
				$sectionlevel--;
			} else {
				break;
			}
		}
		$content = substr($content, strpos($content, '=') + $sectionlevel);
		$content = substr($content, 0, strpos($content, '='));
		return['title' => $content, 'level' => $sectionlevel, ];
	}
	/** askOperator
	* Stellt eine Frage an den Executor des Programms, und gibt seine Reaktion wieder
	* @author Luke081515
	* @param $question - zu stellende Frage
	* @returns Antwort des Ops als String
	*/
	public function askOperator($question) {
		echo $question;
		$handle = fopen ('php://stdin','r');
		$line = fgets($handle);
		return trim($line);
	}
	/** addMail
	* Fuegt $content in eine neue Zeile der Mail ein
	* @author Luke081515
	* @param $content - Inhalt der hinzugegeben wird
	*/
	public function addMail($content) {
		$this->mailcontent = $this->mailcontent . '\n' . $content;
	}
	/** sendMail
	* Sendet die gespeicherte Mail
	* Leert hinterher den Mail-Buffer
	* @author Luke081515
	* @param $content - Inhalt der hinzugegeben wird
	*/
	public function sendMail($subject) {
		mail($this->mail, $subject, $this->mailcontent);
		$this->mailcontent = '';
	}
	/** curlRequest
	* Sendet einen Curl-Request an eine beliebige Webseite
	* @author: Freddy2001 <freddy2001@wikipedia.de>
	* @param $url - URL der Seite
	* @param $https - true:benutze https, false: benutze http
	*/
	protected function curlRequest($url, $https = true) {
		if ($https == true) {
			$protocol = 'https';
		} else {
			$protocol = 'http';
		}		
		$baseURL = $protocol . '://' . 
				   $url;
		$job = $baseURL;

		$curl = curl_init();

		if (!$baseURL) 
			throw new Exception('no arguments for http request found.');
		// set curl options
		curl_setopt($curl, CURLOPT_USERAGENT, 'Cygnus');
		curl_setopt($curl, CURLOPT_URL, $baseURL);
		curl_setopt($curl, CURLOPT_ENCODING, 'UTF-8');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_COOKIEFILE, realpath('Cookies' . $job . '.tmp'));
		curl_setopt($curl, CURLOPT_COOKIEJAR, realpath('Cookies' . $job . '.tmp'));
		curl_setopt($curl, CURLOPT_POST, 0);
		curl_setopt($curl, CURLOPT_POSTFIELDS, '');

		// perform request
		$rqResult = curl_exec($curl);
		if ($rqResult === false)
			throw new Exception('curl request failed: ' . curl_error($curl));
		return $rqResult;
	}
	/** allowBots
	* checks if bots are not allowed, via Template {{nobots}}
	* adapted from https://en.wikipedia.org/wiki/Template:Bots#PHP
	* @param $text - Content to check
	* @returns false if bot is not allowed, otherwise true
	*/
	private function allowBots ($text) {
		if (preg_match('/\{\{(nobots|bots\|allow=none|bots\|deny=all|bots\|optout=all|bots\|deny=.*?'.preg_quote($this->username,'/').'.*?)\}\}/iS',$text))
			return false;
		if (preg_match('/\{\{(bots\|allow=all|bots\|allow=.*?'.preg_quote($this->username,'/').'.*?)\}\}/iS', $text))
			return true;
		if (preg_match('/\{\{(bots\|allow=.*?)\}\}/iS', $text))
			return false;
		return true;
	}
}
?>

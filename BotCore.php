<?php
include 'Password.php';
class Core extends password {

	protected $username;
	protected $password;
	protected $curlHandle;
	protected $site;
	protected $protocol;
	protected $job;

	/** constructor
	* initialisiert curl
	* @author Hgzh
	*/
	public function initcurl ($Account, $Job, $pUseHTTPS = true) {
		$this->start($Account);
		$this->job = $Job;
		if ($pUseHTTPS === true) 
			$this->protocol = 'https'; 
		else 
			$this->protocol = 'http';
		// init curl
		$curl = curl_init();
		if ($curl === false)
			throw new Exception('curl initialization failed.');
		else
			$this->curlHandle = $curl;
		$this->login();
	}
	/** initcurlArgs
	* Benutze diese Funktion anstatt initcurl, wenn du das Passwort des Bots via args mitgeben willst
	* Ansonsten bitte initcurl benutzen
	* @Author Luke081515
	*/
	public function initcurlArgs ($Job, $pUseHTTPS = true) {
		$this->job = $Job;
		if ($pUseHTTPS === true) 
			$this->protocol = 'https'; 
		else 
			$this->protocol = 'http';
		// init curl
		$curl = curl_init();
		if ($curl === false)
			throw new Exception('curl initialization failed.');
		else
			$this->curlHandle = $curl;
	}
	public function __construct($Account, $Job, $pUseHTTPS = true) {}
	public function __destruct() {
		curl_close($this->curlHandle);
	}
	/** httpRequest
	* führt http(s) request durch
	* @author Hgzh
	*/
	protected function httpRequest($pArguments, $job, $pMethod = 'POST', $pTarget = 'w/api.php') {
		$baseURL = $this->protocol . '://' . 
				   $this->site . '/' . 
				   $pTarget;
		$pMethod = strtoupper($pMethod);
		if ($pArguments != '') {
			if ($pMethod === 'POST') {
				$requestURL = $baseURL;
				$postFields = $pArguments;
			} elseif ($pMethod === 'GET') {
				$requestURL = $baseURL . '?' .
							  $pArguments;
			} else 
				throw new Exception('unknown http request method.');
		}
		if (!$requestURL) 
			throw new Exception('no arguments for http request found.');
		// set curl options
		curl_setopt($this->curlHandle, CURLOPT_USERAGENT, 'Luke081515Bot-Beta');
		curl_setopt($this->curlHandle, CURLOPT_URL, $requestURL);
		curl_setopt($this->curlHandle, CURLOPT_ENCODING, "UTF-8");
		curl_setopt($this->curlHandle, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->curlHandle, CURLOPT_COOKIEFILE, realpath('Cookies' . $job . '.tmp'));
		curl_setopt($this->curlHandle, CURLOPT_COOKIEJAR, realpath('Cookies' . $job . '.tmp'));
		// if posted, add post fields
		if ($pMethod === 'POST' && $postFields != '') {
			curl_setopt($this->curlHandle, CURLOPT_POST, 1);
			curl_setopt($this->curlHandle, CURLOPT_POSTFIELDS, $postFields);
		} else {
			curl_setopt($this->curlHandle, CURLOPT_POST, 0);
			curl_setopt($this->curlHandle, CURLOPT_POSTFIELDS, '');
		}
		// perform request
		$rqResult = curl_exec($this->curlHandle);
		if ($rqResult === false)
			throw new Exception('curl request failed: ' . curl_error($this->curlHandle));
		return $rqResult;
	}
	/** login
	* loggt den Benutzer ein
	* @author Hgzh
	*/
	public function login() {
		// get login token
		try {
			$result = $this->httpRequest('action=query&format=php&meta=tokens&type=login', $this->job, 'GET');
		} catch (Exception $e) {
			throw $e;
		}
		$tree = unserialize($result);
		$lgToken = $tree['query']['tokens']['logintoken'];
		if ($lgToken === '')
			throw new Exception('could not receive login token.');	
		// perform login
		try {
			$result = $this->httpRequest('action=login&format=php&lgname=' . urlencode($this->username) . '&lgpassword=' . urlencode($this->password) . '&lgtoken=' . urlencode($lgToken), $this->job);
		} catch (Exception $e) {
			throw $e;
		}
		$tree = unserialize($result);
		$lgResult = $tree['login']['result'];
		// manage result
		if ($lgResult == 'Success')
			return true;
		else
			throw new Exception('login failed with message ' . $lgResult);
	}
	public function logout() 
	{
		try {
			$this->httpRequest('action=logout', $this->job);
		} catch (Exception $e) {
			throw $e;		
		}
	}
	/** DO NOT USE this function
	* This is for unit-tests only
	*/
	public function setSite ($site) {
		$this->site = $site;
	}
	/** DO NOT USE this function
	* This is for unit-tests only
	*/
	public function setUsername ($username) {
		$this->username = $username;
	}
	/** DO NOT USE this function
	* This is for unit-tests only
	*/
	public function setPassword ($password) {
		$this->password = $password;
	}
	/** editPage
	* Bearbeitet eine Seite
	* @param: pTitle - Seitenname
	* @param: pNewText - Neuer Seitentext
	* @param: pSummary - Zusammenfassung
	* @param: nocreate - Soll die Seite ggf neu angelegt werden? (Standart => nein)
	* @author Hgzh / Luke081515
	*/	
	public function editPage($title, $content, $summary, $nocreate = 1) {
		// get csrf token
		try {
			$result = $this->httpRequest('action=query&format=php&meta=tokens&type=csrf', $this->job, 'GET');
		}  catch (Exception $e) {
			throw $e;
		}
		$tree  = unserialize($result);
		$token = $tree['query']['tokens']['csrftoken'];
		if ($token === '')
			throw new Exception('could not receive csrf token.');
		// perform edit
		if ($nocreate === 1) {
			$request = 'action=edit&assert=bot&format=php&bot=&title=' . urlencode($title) . 
				'&nocreate='.
				'&text=' . urlencode($content) . 
				'&token=' . urlencode($token) . 
				'&summary=' . urlencode($summary);
		} else {
			$request = 'action=edit&assert=bot&format=php&bot=&title=' . urlencode($title) . 
				'&text=' . urlencode($content) . 
				'&token=' . urlencode($token) . 
				'&summary=' . urlencode($summary);
		}
		try {
			$result = $this->httpRequest($request, $this->job);
		} catch (Exception $e) {
			throw $e;
		}
		$tree = unserialize($result);
		$editres = $tree['edit']['result'];
		// manage result
		if ($editres == 'Success')
			return $result;
		else
			throw new Exception('page edit failed with message: ' . $editres);
	}
	public function editPageD($title, $content, $summary, $Botflag, $Minorflag, $nocreate = 1) {
		// get csrf token
		try {
			$result = $this->httpRequest('action=query&format=php&meta=tokens&type=csrf', $this->job, 'GET');
		} catch (Exception $e) {
			throw $e;
		}
		$tree  = unserialize($result);
		$token = $tree['query']['tokens']['csrftoken'];
		if ($token === '')
			throw new Exception('could not receive csrf token.');
		// perform edit
		if ($nocreate === 1) {
			$request = 'action=edit&assert=bot&format=php&bot=&title=' . urlencode($title) . 
				'&nocreate='.  
				'&text=' . urlencode($content) . 
				'&token=' . urlencode($token) . 
				'&summary=' . urlencode($summary) .
				'&bot=' . urlencode($Botflag) . 
				'&minor=' . urlencode($Minorflag);
		} else {
			$request = 'action=edit&assert=bot&format=php&bot=&title=' . urlencode($title) . 
				'&text=' . urlencode($content) . 
				'&token=' . urlencode($token) . 
				'&summary=' . urlencode($summary) .
				'&bot=' . urlencode($Botflag) . 
				'&minor=' . urlencode($Minorflag);
		}
		try {
			$result = $this->httpRequest($request, $this->job);
		} catch (Exception $e) {
			throw $e;
		}
		$tree = unserialize($result);
		$editres = $tree['edit']['result'];
		// manage result
		if ($editres == 'Success')
			return $result;
		else
			throw new Exception('page edit failed with message ' . $editres);
	}
	/** start
	* Sucht Logindaten aus Password.php, führt anschließend Login durch
	* @author Luke081515
	*/
	public function start ($Account) {
		$a=0;
		$Found = false;
		$this->init();
		$LoginName = unserialize($this->getLoginName ());
		$LoginHost = unserialize($this->getLoginHost());
		$LoginAccount = unserialize($this->getLoginAccount());
		$LoginPassword = unserialize($this->getLoginPassword());
		while (isset ($LoginName [$a]) === true) {
			if ($LoginName [$a] === $Account) {
				$this->site = $LoginHost [$a];
				$this->username = $LoginAccount [$a];
				$this->password = $LoginPassword [$a];
				$Found = true;
			}
			$a++;
		}
		if (!$Found) {
			throw new Exception('Keine passenden Anmeldeinformationen vorhanden.');
			die(1); // exit with error
		}
	}
	/**
	* @author: Luke081515
	*/
	public function readPage($title) {
		try {
			$result = $this->httpRequest('action=query&prop=revisions&format=php&rvprop=content&rvlimit=1&rvcontentformat=text%2Fx-wiki&rvdir=older&rawcontinue=&titles=' . urlencode($title), $this->job, 'GET');
		} catch (Exception $e) {
			throw $e;
		}
		$Answer = strstr ($result, "s:1:\"*\";");
		$Answer = substr ($Answer, 8);
		$Answer = strstr ($Answer, "\"");
		$Answer = substr ($Answer, 1);
		$Answer = strstr ($Answer, "\";}}}}}}", true);
		return  $Answer;
	}
	public function readPageID ($PageID) {
		try {
			$result = $this->httpRequest('action=query&prop=revisions&format=php&rvprop=content&rvlimit=1&rvcontentformat=text%2Fx-wiki&rvdir=older&rawcontinue=&pageids=' . urlencode($PageID), $this->job, 'GET');
		} catch (Exception $e) {
			throw $e;
		}
		$Answer = strstr ($result, "s:1:\"*\";");
		$Answer = substr ($Answer, 8);
		$Answer = strstr ($Answer, "\"");
		$Answer = substr ($Answer, 1);
		$Answer = strstr ($Answer, "\";}}}}}}", true);
		return  $Answer;
    }
    /**
	* @author: Luke081515
	*/
	public function readPageJs($title) {
		try {
			$result = $this->httpRequest('action=query&prop=revisions&format=php&rvprop=content&rvlimit=1&rvcontentformat=text%2Fjavascript&rvdir=older&rawcontinue=&titles=' . urlencode($title), $this->job, 'GET');
		} catch (Exception $e) {
			throw $e;
		}
		$Answer = strstr ($result, "s:1:\"*\";");
		$Answer = substr ($Answer, 8);
		$Answer = strstr ($Answer, "\"");
		$Answer = substr ($Answer, 1);
		$Answer = strstr ($Answer, "\";}}}}}}", true);
		return  $Answer;
	}
	/**
	* @author: Luke081515
	*/
	public function readSection($title, $section) {
		try {
			$result = $this->httpRequest('action=query&prop=revisions&format=php&rvprop=content&rvlimit=1&rvcontentformat=text%2Fx-wiki&rvdir=older&rvsection=' . urlencode($section) . '&titles=' . urlencode($title), $this->job, 'GET');
		} catch (Exception $e) {
			throw $e;
		}
		$Answer = strstr ($result, "s:1:\"*\";");
		$Answer = substr ($Answer, 8);
		$Answer = strstr ($Answer, "\"");
		$Answer = substr ($Answer, 1);
		$Answer = strstr ($Answer, "\";}}}}}}", true);
		return  $Answer;
	}
	/**
	* @author: Hgzh/Luke081515
	*/
	public function editSection($title, $content, $summary, $Sectionnumber, $nocreate = 1) {
		// get csrf token
		try {
			$result = $this->httpRequest('action=query&format=php&meta=tokens&type=csrf', $this->job, 'GET');
		} catch (Exception $e) {
			throw $e;
		}
		$tree  = unserialize($result);
		$token = $tree['query']['tokens']['csrftoken'];
		if ($token === '')
			throw new Exception('could not receive csrf token.');
		// perform edit
		if ($nocreate === 1) {
			$request = 'action=edit&assert=bot&format=php&bot=&title=' . urlencode($title) . 
				'&nocreate='.  
				'&text=' . urlencode($content) .
				'&token=' . urlencode($token) .
				'&section=' . urlencode($Sectionnumber) .
				'&summary=' . urlencode($summary);
		} else {
			$request = 'action=edit&assert=bot&format=php&bot=&title=' . urlencode($title) . 
				'&text=' . urlencode($content) .
				'&token=' . urlencode($token) .
				'&section=' . urlencode($Sectionnumber) .
				'&summary=' . urlencode($summary);
		}
		try {
			$result = $this->httpRequest($request, $this->job);
		} catch (Exception $e) {
			throw $e;
		}
		$tree = unserialize($result);
		$editres = $tree['edit']['result'];
		// manage result
		if ($editres == 'Success')
			return $result;
		else
			throw new Exception('page edit failed with message ' . $editres);
	}
	public function editSectionD($title, $content, $summary, $Sectionnumber, $Botflag, $Minorflag, $nocreate = 1) {
		// get csrf token
		try {
			$result = $this->httpRequest('action=query&format=php&meta=tokens&type=csrf', $this->job, 'GET');
		} catch (Exception $e) {
			throw $e;
		}
		$tree  = unserialize($result);
		$token = $tree['query']['tokens']['csrftoken'];
		if ($token === '')
			throw new Exception('could not receive csrf token.');
		// perform edit
		if ($nocreate === 1) {
			$request = 'action=edit&assert=bot&format=php&bot=&title=' . urlencode($title) . 
				'&nocreate='.  
				'&text=' . urlencode($content) . 
				'&token=' . urlencode($token) . 
				'&summary=' . urlencode($summary) .
				'&section=' . urlencode($Sectionnumber) .
				'&bot=' . urlencode($Botflag) . 
				'&minor=' . urlencode($Minorflag);
		} else {
			$request = 'action=edit&assert=bot&format=php&bot=&title=' . urlencode($title) . 
				'&text=' . urlencode($content) . 
				'&token=' . urlencode($token) . 
				'&summary=' . urlencode($summary) .
				'&section=' . urlencode($Sectionnumber) .
				'&bot=' . urlencode($Botflag) . 
				'&minor=' . urlencode($Minorflag);
		}
		try {
			$result = $this->httpRequest($request, $this->job);
		} catch (Exception $e) {
			throw $e;
		}
		$tree = unserialize($result);
		$editres = $tree['edit']['result'];
		// manage result
		if ($editres == 'Success')
			return $result;
		else
			throw new Exception('page edit failed with message ' . $editres);
	}
	public function MovePage ($StartLemma, $TargetLemma, $reason) {
		$data = "action=query&format=php&meta=tokens&type=csrf";
		try {
			$result = $this->httpRequest($data, $this->job, 'GET');
		} catch (Exception $e) {
			throw $e;
		}
		$answer = unserialize($result);
		$token = $answer['query']['tokens']['csrftoken'];
		$data = "action=move&format=php&assert=bot" . "&from=" . urlencode($StartLemma) . "&to=" . urlencode($TargetLemma) . "&reason=" . urlencode($reason) . "&bot=0" . "&movetalk=&noredirect=&watchlist=nochange&token=" . urlencode($token);
		try {
			$result = $this->httpRequest($data, $this->job);
		} catch (Exception $e) {
			throw $e;
		}
		return ($result);
	}

	/** getCatMembers
	* Liest alle Seiten der Kategorie aus, auch Seiten die in Unterkategorien der angegebenen Kategorie kategorisiert sind
	* Funktioniert bis zu 5000 Unterkategorien pro Katgorie (nicht 5000 Unterkategorien insgesamt)
	* Erfordert Botflag, da Limit auf 5000 gesetzt, (geht zwar sonst auch, aber nur mit Warnung)
	* @author: Luke081515
	* @param: Kategorie die analyisiert werden soll.
	* @return: false, falls keine Seiten vorhanden, ansonsten serialisiertes Array mit Seitentiteln
	* @version: V2.1
	* Tasks: T244
	*/
	protected function getCatMembers ($Kat, $onlySubCats = false)
	{
		$b=0;
		$SubCat [0] = $Kat;
		try {
			$result = $this->httpRequest('action=query&list=categorymembers&format=php&cmtitle=' . urlencode($Kat) . '&cmprop=title&cmtype=subcat&cmlimit=5000&cmsort=sortkey&cmdir=ascending&rawcontinue=', $this->job, 'GET');
		} catch (Exception $e) {
			throw $e;
		}
		$answer = unserialize($result); 
		$a=0;
		if (isset ($answer["query"]['categorymembers'][$a]['title']) === true) {
			$Sub = true;
			while (isset ($answer["query"]['categorymembers'][$a]['title']) === true) {
				$SubCat [$b] = $answer["query"]['categorymembers'][$a]['title'];	
				$b++;
				$a++;
			}
		}
		else {}
		$b=0;
		$c=0;
		if ($onlySubCats === true)
			return $SubCat;
		while (isset ($SubCat [$b]) === true)
		{
			try {
				$result = $this->httpRequest('action=query&list=categorymembers&format=php&cmtitle=' . urlencode($SubCat [$b]) . '&cmprop=title&cmtype=page&cmlimit=5000&cmsort=sortkey&cmdir=ascending&rawcontinue=', $this->job, 'GET');
			} catch (Exception $e) {
				throw $e;
			}
			$answer = unserialize($result); 
			$Cont = false;
			if (isset ($answer ["query-continue"]["categorymembers"]["cmcontinue"]) === true) {
				$Continue = $answer ["query-continue"]["categorymembers"]["cmcontinue"];
				$Cont = true;
			}
			while ($Cont === true) {
				$a=0;
				if (isset ($answer["query"]['categorymembers'][$a]['title']) === true) {
					while (isset ($answer["query"]['categorymembers'][$a]['title']) === true) {
						$Site [$c] = $answer["query"]['categorymembers'][$a]['title'];	
						$c++;
						$a++;
					}
				} else  {}
				try {
					$result = $this->httpRequest('action=query&list=categorymembers&format=php&cmcontinue=' . $Continue 
					. '&cmtitle=' . urlencode($SubCat [$b]) 
					. '&cmprop=title&cmtype=page&cmlimit=5000&cmsort=sortkey&cmdir=ascending&rawcontinue=', $this->job, 'GET');
				} catch (Exception $e) {
					throw $e;
				}
				$answer = unserialize($result); 
				if (isset ($answer ["query-continue"]["categorymembers"]["cmcontinue"]) === true) {
					$Continue = $answer ["query-continue"]["categorymembers"]["cmcontinue"];
					$Cont = true;
				} else
					$Cont = false;
			}
			$a=0;
			if (isset ($answer["query"]['categorymembers'][$a]['title']) === true) {
				while (isset ($answer["query"]['categorymembers'][$a]['title']) === true) {
					$Site [$c] = $answer["query"]['categorymembers'][$a]['title'];	
					$c++;
					$a++;
				}
			} else {}
			$b++;
		}
		if (isset ($Site [0]) === false)
			return false;
		else
			return (serialize ($Site));
	}
	/** getPageCats
	* Liest alle Kategorien einer Seite aus
	* Funktioniert bis zu 500 Kategorien einer Seite
	* Erfordert Botflag, da Limit auf 5000 gesetzt
	* @author: Luke081515
	* @param: $Site - Seite die analyisiert werden soll; [$Mode] - Modus der Rückgabe
	* @return: Alle Kategorien als Liste durch Pipes getrennt
	* @version: V1.0
	*/
	public function GetPageCats ($Site) {
		try {
			$result = $this->httpRequest('action=query&prop=categories&format=php&cllimit=5000&cldir=ascending&rawcontinue=&titles=' . urlencode($Site), $this->job, 'GET');
		} catch (Exception $e) {
			throw $e;
		}
		$Result = explode ("\"", $result);
		$a=19;
		$b=0;
		while (isset ($Result [$a]) === true) {
			$Kats [$b] = $Result [$a];
			$a = $a +  6;
			$b++;
		}
		$b=1;
		$Ret = $Kats [0];
		while (isset ($Kats [$b]) === true) {
			$Ret = $Ret . "|" . $Kats [$b];
			$b++;
		}
		return $Ret;
	}

	/** getAllEmbedings
	* Liest alle Einbindungen einer Vorlage aus
	* Erfordert Botflag, da Limit auf 5000 gesetzt
	* @author: Luke081515
	* @param: Vorlage, deren Einbindungen aufgelistet werden sollen
	* @return: false, falls keine Einbindungen vorhanden, ansonsten serialisiertes Array mit Seitentiteln
	* @version: V2.1
	* Tasks: T246
	*/
	public function getAllEmbedings ($Templ) {
		$b=0;
		$Again = true;
		while ($Again === true) {
			if (isset ($Continue) === true)
				$data = "action=query&list=embeddedin&format=php&eititle=" . urlencode($Templ) . "&einamespace=0&eicontinue=" . urlencode($Continue) . "&eidir=ascending&eilimit=5000&rawcontinue=";
			else
				$data = "action=query&list=embeddedin&format=php&eititle=" . urlencode($Templ) . "&einamespace=0&eidir=ascending&eilimit=5000&rawcontinue=";
			try {
				$result = $this->httpRequest($data, $this->job, 'GET');
			} catch (Exception $e) {
				throw $e;
			}
			$answer = unserialize($result); 
			$a=0;
			if (isset ($answer ["query-continue"]["embeddedin"]["eicontinue"]) === true) {
				$Continue = $answer ["query-continue"]["embeddedin"]["eicontinue"];
				$Again = true;
			}
			else
				$Again = false;
			if (isset ($answer["query"]['embeddedin'][$a]['title']) === true) {
				while (isset ($answer["query"]['embeddedin'][$a]['title']) === true) {
					$Site [$b] = $answer["query"]['embeddedin'][$a]['title'];
					$b++;
					$a++;
				}
			} else
				return false;
		}
		return (serialize ($Site));
	}
	/** getAllPages
	* Liest alle Seiten eines Namensraumes aus
	* Erfordert Botflag, da Limit auf 5000 gesetzt (geht zwar sonst auch, aber nur mit Warnung)
	* @author: Luke081515
	* @param: Nummer des Namensraumes, von dem die Seiten ausgelesen werden
	* @return: false, falls keine Seiten im Namensraum vorhanden, ansonsten serialisiertes Array mit Seitentiteln
	* @version: V2.1
	* Tasks: T247
	*/
	public function getAllPages ($Namespace) {
		$b=0;
		$Again = true;
		while ($Again === true) {
			if (isset ($Continue) === true)
				$data = "action=query&list=allpages&format=php&apcontinue=" . $Continue . "&apnamespace=" . $Namespace . "&aplimit=5000&apdir=ascending&rawcontinue=";
			else
				$data = "action=query&list=allpages&format=php&apnamespace=" . $Namespace . "&aplimit=5000&apdir=ascending&rawcontinue=";
			try {
				$result = $this->httpRequest($data, $this->job, 'GET');
			} catch (Exception $e) {
				throw $e;
			}
			$answer = unserialize($result); 
			$a=0;
			if (isset ($answer ["query-continue"]["allpages"]["apcontinue"]) === true) {
				$Continue = $answer ["query-continue"]["allpages"]["apcontinue"];
				$Again = true;
			} else
				$Again = false;
			if (isset ($answer["query"]['allpages'][$a]['title']) === true) {
				while (isset ($answer["query"]['allpages'][$a]['title']) === true) {
					$Site [$b] = $answer["query"]['allpages'][$a]['title'];
					$b++;
					$a++;
				}
			} else
				return false;
		}
		return (serialize ($Site));
	}
	/** getPageID
	* Gibt zu der angegebenen Seite die ID an
	* @author: Luke081515
	* @param: Name der Seite
	* @return: int: PageID, bool: false falls Seite nicht vorhanden
	* @version: V1.0
	*/
	public function GetPageID ($PageName) {
		$data = "action=query&format=php&maxlag=5&prop=info&titles=" . urlencode ($PageName);
		try {
			$result = $this->httpRequest($data, $this->job, 'GET');
		} catch (Exception $e) {
			throw $e;
		}
		if (strstr ($result, "missing") !== false)
			return false;
		$answer = unserialize($result); 
		$a=0;
		while (isset ($answer ["query"]["pages"][$a]) === false)
			$a++;
		return $a;
	}
	
	/** getLinks
	* Gibt aus, welche Wikilinks sich auf einer Seite befinden
	* @author Luke081515
	* @param $Site - Seite die analysiert wird
	* @returns Array mit Ergebnissen
	*/
	public function getLinks ($Site) {
		$data = "action=query&prop=links&format=xml&pllimit=5000&pldir=ascending&plnamespace=0&rawcontinue=&titles=" . urlencode($Site);
		try {
			$website = $this->httpRequest($data, $this->job, 'GET');
		} catch (Exception $e) {
			throw $e;
		}
		$Answer = explode ("\"", $website);
		$b=13;
		$q=0;
		while (isset($Answer [$b]) === true) {
			$Result [$q] = $Answer [$b];
			$b = $b + 4;
			$q++;
		}
		return $Result;
	}
}
?>

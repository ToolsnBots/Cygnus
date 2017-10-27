<?php
require_once __DIR__ . '/BotCore.php';
/** Debug.php
* This is a script for Cygnus which allows to execute single functions interactivly
* @Author Luke081515 <luke081515@tools.wmflabs.org>
* @Version 0.1
* @Status Alpha
*/
class Debug extends Core {
	public function __construct() {
		$this->setSite($this->askRequired('Enter the domain to use:'));
		$this->setUsername($this->askRequired('Enter the username to use:'));
		$this->setPassword($this->askRequired('Enter the password to use:'));
		$this->initcurlArgs('Debug', true);
		$this->login();
		do {
			$this->debug($this->askRequired("Name of the function you want to debug:"));
			$answer = $this->askRequired("Do you want to debug another function? [y/N]");
		} while (strtolower($answer) !== 'n');
	}
	/** debug
	* This is the debug engine, here you need to define all methods the script can use
	*/
	private function debug($MethodName) {
		switch ($MethodName) {
			// Reading functions
			case 'readPage':
				$required = array("title");
				$Param = $this->getParams($required);
				$this->echoNotice('Starting the function call of readPage...');
				$starttime = microtime (true);
				try {
					$ret = $this->readPage($Param[0]);
					$endtime = microtime (true);
					$this->processFunction($ret, $starttime, $endtime);
				} catch (Exception $e) {
					$endtime = microtime (true);
					$this->processError($e, $starttime, $endtime);
				}
				break;
			case 'readPageID':
				$required = array("pageID");
				$Param = $this->getParams($required);
				$this->echoNotice('Starting the function call of readPageID...');
				$starttime = microtime (true);
				try {
					$ret = $this->readPageID($Param[0]);
					$endtime = microtime (true);
					$this->processFunction($ret, $starttime, $endtime);
				} catch (Exception $e) {
					$endtime = microtime (true);
					$this->processError($e, $starttime, $endtime);
				}
				break;
			case 'readPageJs':
				$required = array("title");
				$Param = $this->getParams($required);
				$this->echoNotice('Starting the function call of readPageJs...');
				$starttime = microtime (true);
				try {
					$ret = $this->readPageJs($Param[0]);
					$endtime = microtime (true);
					$this->processFunction($ret, $starttime, $endtime);
				} catch (Exception $e) {
					$endtime = microtime (true);
					$this->processError($e, $starttime, $endtime);
				}
				break;
			case 'readPageCss':
				$required = array("title");
				$Param = $this->getParams($required);
				$this->echoNotice('Starting the function call of readPageCss...');
				$starttime = microtime (true);
				try {
					$ret = $this->readPageCss($Param[0]);
					$endtime = microtime (true);
					$this->processFunction($ret, $starttime, $endtime);
				} catch (Exception $e) {
					$endtime = microtime (true);
					$this->processError($e, $starttime, $endtime);
				}
				break;
			case 'readSection':
				$required = array("title", "section");
				$Param = $this->getParams($required);
				$this->echoNotice('Starting the function call of readSection...');
				$starttime = microtime (true);
				try {
					$ret = $this->readSection($Param[0], $Param[1]);
					$endtime = microtime (true);
					$this->processFunction($ret, $starttime, $endtime);
				} catch (Exception $e) {
					$endtime = microtime (true);
					$this->processError($e, $starttime, $endtime);
				}
				break;
			case 'getTableOfContents':
				$required = array("title");
				$Param = $this->getParams($required);
				$this->echoNotice('Starting the function call of getTableOfContents...');
				$starttime = microtime (true);
				try {
					$ret = $this->getTableOfContents($Param[0]);
					$endtime = microtime (true);
					$this->processFunction($ret, $starttime, $endtime);
				} catch (Exception $e) {
					$endtime = microtime (true);
					$this->processError($e, $starttime, $endtime);
				}
				break;
			// editPage functions
			case 'editPage':
				$required = array("title", "content", "summary");
				$optional = array("noCreate", "overrideNobots");
				$optvalues = array(1, false);
				$Param = $this->getParams($required, $optional, $optvalues);
				$this->echoNotice('Starting the function call of editPage...');
				$starttime = microtime (true);
				try {
					$ret = $this->editPage($Param[0], $Param[1], $Param[2], intval($Param[3]), boolval($Param[4]));
					$endtime = microtime (true);
					$this->processFunction($ret, $starttime, $endtime);
				} catch (Exception $e) {
					$endtime = microtime (true);
					$this->processError($e, $starttime, $endtime);
				}
				break;
			case 'editPageMinor':
				$required = array("title", "content", "summary");
				$optional = array("noCreate", "overrideNobots");
				$optvalues = array(1, false);
				$Param = $this->getParams($required, $optional, $optvalues);
				$this->echoNotice('Starting the function call of editPageMinor...');
				$starttime = microtime (true);
				try {
					$ret = $this->editPageMinor($Param[0], $Param[1], $Param[2], intval($Param[3]), boolval($Param[4]));
					$endtime = microtime (true);
					$this->processFunction($ret, $starttime, $endtime);
				} catch (Exception $e) {
					$endtime = microtime (true);
					$this->processError($e, $starttime, $endtime);
				}
				break;
			case 'editPageD':
				$required = array("title", "content", "summary", "botflag", "minorflag");
				$optional = array("noCreate", "overrideNobots");
				$optvalues = array(1, false);
				$Param = $this->getParams($required, $optional, $optvalues);
				$this->echoNotice('Starting the function call of editPageD...');
				$starttime = microtime (true);
				try {
					$ret = $this->editPageD($Param[0], $Param[1], $Param[2], $Param[3], $Param[4], intval($Param[5]), boolval($Param[6]));
					$endtime = microtime (true);
					$this->processFunction($ret, $starttime, $endtime);
				} catch (Exception $e) {
					$endtime = microtime (true);
					$this->processError($e, $starttime, $endtime);
				}
				break;
			case 'editSection':
				$required = array("title", "content", "summary", "sectionnumber");
				$optional = array("noCreate", "overrideNobots");
				$optvalues = array(1, false);
				$Param = $this->getParams($required, $optional, $optvalues);
				$this->echoNotice('Starting the function call of editSection...');
				$starttime = microtime (true);
				try {
					$ret = $this->editSection($Param[0], $Param[1], $Param[2], $Param[3], intval($Param[4]), boolval($Param[5]));
					$endtime = microtime (true);
					$this->processFunction($ret, $starttime, $endtime);
				} catch (Exception $e) {
					$endtime = microtime (true);
					$this->processError($e, $starttime, $endtime);
				}
				break;
			case 'editSectionMinor':
				$required = array("title", "content", "summary", "sectionnumber");
				$optional = array("noCreate", "overrideNobots");
				$optvalues = array(1, false);
				$Param = $this->getParams($required, $optional, $optvalues);
				$this->echoNotice('Starting the function call of editSectionMinor...');
				$starttime = microtime (true);
				try {
					$ret = $this->editSectionMinor($Param[0], $Param[1], $Param[2], $Param[3], intval($Param[4]), boolval($Param[5]));
					$endtime = microtime (true);
					$this->processFunction($ret, $starttime, $endtime);
				} catch (Exception $e) {
					$endtime = microtime (true);
					$this->processError($e, $starttime, $endtime);
				}
				break;
			case 'editSectionD':
				$required = array("title", "content", "summary", "sectionnumber", "botflag", "minorflag");
				$optional = array("noCreate", "overrideNobots");
				$optvalues = array(1, false);
				$Param = $this->getParams($required, $optional, $optvalues);
				$this->echoNotice('Starting the function call of editSectionD...');
				$starttime = microtime (true);
				try {
					$ret = $this->editSectionD($Param[0], $Param[1], $Param[2], $Param[3], $Param[4], $Param[5], intval($Param[6]), boolval($Param[7]));
					$endtime = microtime (true);
					$this->processFunction($ret, $starttime, $endtime);
				} catch (Exception $e) {
					$endtime = microtime (true);
					$this->processError($e, $starttime, $endtime);
				}
				break;
			case 'movePage':
				$required = array("oldTitle", "newTitle", "reason");
				$optional = array("bot", "movetalk", "noredirect");
				$optvalues = array(0, 1, 1);
				$Param = $this->getParams($required, $optional, $optvalues);
				$this->echoNotice('Starting the function call of movePage...');
				$starttime = microtime (true);
				try {
					$ret = $this->movePage($Param[0], $Param[1], $Param[2], intval($Param[3]), intval($Param[4]), intval($Param[5]));
					$endtime = microtime (true);
					$this->processFunction($ret, $starttime, $endtime);
				} catch (Exception $e) {
					$endtime = microtime (true);
					$this->processError($e, $starttime, $endtime);
				}
				break;
			case 'watch':
				$required = array("title");
				$optional = array("unwatch");
				$optvalues = array(0);
				$Param = $this->getParams($required, $optional, $optvalues);
				$this->echoNotice('Starting the function call of watch...');
				$starttime = microtime (true);
				try {
					$ret = $this->watch($Param[0], intval($Param[2]));
					$endtime = microtime (true);
					$this->processFunction($ret, $endtime);
				} catch (Exception $e) {
					$endtime = microtime (true);
					$this->processError($e, $endtime);
				}
				break;
			case 'review':
				$required = array("revid", "comment", "reason");
				$optional = array("unapprove");
				$optvalues = array(0);
				$Param = $this->getParams($required, $optional, $optvalues);
				$this->echoNotice('Starting the function call of review...');
				$starttime = microtime (true);
				try {
					$ret = $this->review($Param[0], $Param[1], $Param[2], intval($Param[3]));
					$endtime = microtime (true);
					$this->processFunction($ret, $starttime, $endtime);
				} catch (Exception $e) {
					$endtime = microtime (true);
					$this->processError($e, $starttime, $endtime);
				}
				break;
			// User information functions
			case 'getUserEditcount':
				$required = array("username");
				$Param = $this->getParams($required);
				$this->echoNotice('Starting the function call of getUserEditcount...');
				$starttime = microtime(true);
				try {
					$ret = $this->getUserEditcount($Param[0]);
					$endtime = microtime(true);
					$this->processFunction($ret, $endtime);
				} catch (Exception $e) {
					$endtime = microtime(true);
					$this->processError($e, $endtime);
				}
				break;
			// Query functions
			case 'getCatMembers':
				$required = array("kat");
				$optional = array("onlySubCats", "excludeWls");
				$optvalues = array(false, false);
				$Param = $this->getParams($required, $optional, $optvalues);
				$this->echoNotice('Starting the function call of getCatMembers...');
				$starttime = microtime (true);
				try {
					$ret = $this->getCatMembers($Param[0], boolval($Param[1]), boolval($Param[2]));
					$endtime = microtime (true);
					$this->processFunction($ret, $starttime, $endtime);
				} catch (Exception $e) {
					$endtime = microtime (true);
					$this->processError($e, $starttime, $endtime);
				}
				break;
			case 'getPageCats':
				$required = array("title");
				$Param = $this->getParams($required);
				$this->echoNotice('Starting the function call of getPageCats...');
				$starttime = microtime (true);
				try {
					$ret = $this->getPageCats($Param[0]);
					$endtime = microtime (true);
					$this->processFunction($ret, $starttime, $endtime);
				} catch (Exception $e) {
					$endtime = microtime (true);
					$this->processError($e, $starttime, $endtime);
				}
				break;
			case 'getAllEmbedings':
				$required = array("templ");
				$Param = $this->getParams($required);
				$this->echoNotice('Starting the function call of getAllEmbedings...');
				$starttime = microtime (true);
				try {
					$ret = $this->getAllEmbedings($Param[0]);
					$endtime = microtime (true);
					$this->processFunction($ret, $starttime, $endtime);
				} catch (Exception $e) {
					$endtime = microtime (true);
					$this->processError($e, $starttime, $endtime);
				}
				break;
			case 'getAllPages':
				$required = array("namespace");
				$Param = $this->getParams($required);
				$this->echoNotice('Starting the function call of getAllPages...');
				$starttime = microtime (true);
				try {
					$ret = $this->getAllPages($Param[0]);
					$endtime = microtime (true);
					$this->processFunction($ret, $starttime, $endtime);
				} catch (Exception $e) {
					$endtime = microtime (true);
					$this->processError($e, $starttime, $endtime);
				}
				break;
			case 'getPageID':
				$required = array("title");
				$Param = $this->getParams($required);
				$this->echoNotice('Starting the function call of getPageID...');
				$starttime = microtime (true);
				try {
					$ret = $this->getPageID($Param[0]);
					$endtime = microtime (true);
					$this->processFunction($ret, $starttime, $endtime);
				} catch (Exception $e) {
					$endtime = microtime (true);
					$this->processError($e, $starttime, $endtime);
				}
				break;
			case 'getLinks':
				$required = array("title");
				$Param = $this->getParams($required);
				$this->echoNotice('Starting the function call of getLinks...');
				$starttime = microtime (true);
				try {
					$ret = $this->getLinks($Param[0]);
					$endtime = microtime (true);
					$this->processFunction($ret, $starttime, $endtime);
				} catch (Exception $e) {
					$endtime = microtime (true);
					$this->processError($e, $starttime, $endtime);
				}
				break;
			case 'getSectionTitle':
				$required = array("title", "section");
				$Param = $this->getParams($required);
				$this->echoNotice('Starting the function call of getSectionTitle...');
				$starttime = microtime (true);
				try {
					$ret = $this->getSectionTitle($Param[0]);
					$endtime = microtime (true);
					$this->processFunction($ret, $starttime, $endtime);
				} catch (Exception $e) {
					$endtime = microtime (true);
					$this->processError($e, $starttime, $endtime);
				}
				break;
			// Admin functions
			case 'deletePage':
				$required = array("title", "reason");
				$Param = $this->getParams($required);
				$this->echoNotice('Starting the function call of deletePage...');
				$starttime = microtime (true);
				try {
					$ret = $this->deletePage($Param[0], $Param[1]);
					$endtime = microtime (true);
					$this->processFunction($ret, $starttime, $endtime);
				} catch (Exception $e) {
					$endtime = microtime (true);
					$this->processError($e, $starttime, $endtime);
				}
				break;
			case 'blockUser':
				$required = array("user", "reason", "expiry");
				$optional = array("anononly", "nocreate", "autoblock", "noemail", "hidename", "allowusertalk", "reblock");
				$optvalues = array(1, 1, 1, 0, 0, 1, 0);
				$Param = $this->getParams($required);
				$this->echoNotice('Starting the function call of blockUser...');
				$starttime = microtime (true);
				try {
					$ret = $this->blockUser($Param[0], $Param[1], $Param[2], intval($Param[3]), intval($Param[4]),
						intval($Param[5]), intval($Param[6]), intval($Param[7]), intval($Param[8]), intval($Param[9]));
					$endtime = microtime (true);
					$this->processFunction($ret, $starttime, $endtime);
				} catch (Exception $e) {
					$endtime = microtime (true);
					$this->processError($e, $starttime, $endtime);
				}
				break;
			case 'unblockUser':
				$required = array("user", "reason");
				$Param = $this->getParams($required);
				$this->echoNotice('Starting the function call of unblockUser...');
				$starttime = microtime (true);
				try {
					$ret = $this->unblockUser($Param[0], $Param[1]);
					$endtime = microtime (true);
					$this->processFunction($ret, $starttime, $endtime);
				} catch (Exception $e) {
					$endtime = microtime (true);
					$this->processError($e, $starttime, $endtime);
				}
				break;
			case 'protectPage':
				$required = array("title", "reason", "protections", "expiry", "cascade");
				$Param = $this->getParams($required);
				$this->echoNotice('Starting the function call of protectPage...');
				$starttime = microtime (true);
				try {
					$ret = $this->protectPage($Param[0], $Param[1], $Param[2], $Param[3], $Param[4]);
					$endtime = microtime (true);
					$this->processFunction($ret, $starttime, $endtime);
				} catch (Exception $e) {
					$endtime = microtime (true);
					$this->processError($e, $starttime, $endtime);
				}
				break;
			case 'stabilize':
				$required = array("title", "expiry", "reason", "default", "autoreview", "review");
				$Param = $this->getParams($required);
				$this->echoNotice('Starting the function call of stabilize...');
				$starttime = microtime (true);
				try {
					$ret = $this->stabilize($Param[0], $Param[1], $Param[2], $Param[3], $Param[4]);
					$endtime = microtime (true);
					$this->processFunction($ret, $starttime, $endtime);
				} catch (Exception $e) {
					$endtime = microtime (true);
					$this->processError($e, $starttime, $endtime);
				}
				break;
			default:
				throw new Exception("This function does not exist, or is not configured.");
		}
	}
	/** getParams
	* This method gets the params from the user
	* There are two lists, one with params, one with optional params
	*/
	private function getParams($ParamList, $ParamOptList = null, $ParamOptDefault = null) {
		$a = 0;
		while (isset($ParamList[$a])) {
			$Param[$a] = $this->askRequired('Enter the value for the param ' . $ParamList[$a] . ' to use:');
			$a++;
		}
		$b = 0;
		while (isset($ParamOptList[$b])) {
			$res = $this->askOptional('Enter the value for the optional param ' . $ParamOptList[$b] . ' (Leave blank to use default):');
			if ($res === "")
				$Param[$a] = $ParamOptDefault[$b];
			$a++;
			$b++;
		}
		return $Param;
	}
	/** echoCritical
	* Writes out a critical message
	* Use this only for critical messages
	* Prints out the message in red
	*/
	private function echoCritical($msg) {
		echo "\n\033[01;31m" . $msg . "\033[0m";
	}
	/** echoSuccessful
	* Use this only when a task was successful
	* Prints out the message in green
	*/
	private function echoSuccessful($msg) {
		echo "\n\033[01;32m" . $msg . "\033[0m";
	}
	/** echoWarning
	* Writes out a warning
	* Use this only for non-critical warnings
	* Prints the message out in yellow
	*/
	private function echoWarning($msg) {
		echo "\n\033[01;33m" . $msg . "\033[0m";
	}
	/** echoNotice
	* Writes out a notice
	* Use this only for notices, no output, no warnings/errors
	* Prints the message out in blue
	*/
	private function echoNotice($msg) {
		echo "\n\033[01;34m" . $msg . "\033[0m";
	}
	/** echoOutput
	* Writes out the output of a function
	* Use this only for function output
	* Prints the message out in purple
	*/
	private function echoOutput($msg) {
		echo "\n\033[01;35m" . $msg . "\033[0m";
	}
	/** askRequired
	* Asks for something
	* Use this only if the answer is required
	* Prints the answer in yellow
	*/
	private function askRequired($msg) {
		return $this->askOperator("\n\033[01;33m" . $msg . "\033[0m");
	}
	/** askOptional
	* Asks for something
	* Use this only if the answer is optional (additional param for example)
	* Prints the answer in cyan
	*/
	private function askOptional($msg) {
		return $this->askOperator("\n\033[01;36m" . $msg . "\033[0m");
	}
	/** processFunction
	* Internal, used for successful calls
	*/
	private function processFunction($ret, $starttime, $endtime) {
		$total = $endtime - $starttime;
		$this->echoSuccessful('Function call succeeded');
		$this->echoNotice('Performance: ' . $total . ' seconds');
		$answer = $this->askRequired('Display the result now? [y/N]');
		if (strtolower($answer) !== 'n')
			$this->echoOutput($ret);
	}
	/** processError
	* Internal, used for errors
	*/
	private function processError ($err, $starttime, $endtime) {
		$total = $endtime - $starttime;
		$this->echoCritical('Function call failed after ' . $total . ' seconds');
		$answer = $this->askRequired('Display the error now? [y/N]');
		if (strtolower($answer) !== 'n')
			$this->echoWarning($err);
	}
}
$Bot = new Debug();
?>
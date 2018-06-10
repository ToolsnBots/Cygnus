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
		$this->setSite($this->ask('Enter the domain to use:', 'required'));
		$this->setUsername($this->ask('Enter the username to use:', 'required'));
		$this->setPassword($this->ask('Enter the password to use:', 'required'));
		$patchAnswer = $this->ask('Use a non default target? [y/N]', 'required');
		if (strtolower($patchAnswer) !== 'n') {
			$this->setTarget($this->ask('Enter the value for the target:', 'required'));
		} else {
			$this->setTarget("w/api.php");
		}
		$assert = $this->ask('Enter the value for assert:', 'required');
		$debug = $this->ask('Use verbose debug mode? [y/N]', 'required');
		if (strtolower($debug) !== 'n') {
			$debug = true;
		} else {
			$debug = false;
		}
		$this->initcurlArgs('Debug', true, $assert, false, $debug);
		$this->login();
		do {
			$this->debug($this->ask("Name of the function you want to debug:", "required"));
			$answer = $this->ask("Do you want to debug another function? [y/N]", "required");
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
				$this->echoMsg("Starting the function call of readPage...", "notice");
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
				$this->echoMsg("Starting the function call of readPageID...", "notice");
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
				$this->echoMsg("Starting the function call of readPageJs...", "notice");
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
				$this->echoMsg("Starting the function call of readPageCss...", "notice");
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
				$this->echoMsg("Starting the function call of readSection...", "notice");
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
				$this->echoMsg("Starting the function call of getTableOfContents...", "notice");
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
				$this->echoMsg("Starting the function call of editPage...", "notice");
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
				$this->echoMsg("Starting the function call of editPageMinor...", "notice");
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
				$this->echoMsg("Starting the function call of editPageD...", "notice");
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
				$this->echoMsg("Starting the function call of editSection...", "notice");
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
				$this->echoMsg("Starting the function call of editSectionMinor...", "notice");
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
				$this->echoMsg("Starting the function call of editSectionD...", "notice");
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
				$this->echoMsg("Starting the function call of movePage...", "notice");
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
			case 'rollback':
				$required = array("title", "user");
				$optional = array("summary", "markbot");
				$optvalues = array("", 0);
				$Param = $this->getParams($required, $optional, $optvalues);
				$this->echoMsg("Starting the function call of rollback...", "notice");
				$starttime = microtime (true);
				try {
					$ret = $this->rollback($Param[0], $Param[1], $Param[2], intval($Param[3]));
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
				$this->echoMsg("Starting the function call of watch...", "notice");
				$starttime = microtime (true);
				try {
					$ret = $this->watch($Param[0], intval($Param[1]));
					$endtime = microtime (true);
					$this->processFunction($ret, $starttime, $endtime);
				} catch (Exception $e) {
					$endtime = microtime (true);
					$this->processError($e, $starttime, $endtime);
				}
				break;
			case 'purge':
				$required = array("title");
				$optional = array("forcelinkupdate", "forcerecursivelinkupdate");
				$optvalues = array(0, 0);
				$Param = $this->getParams($required, $optional, $optvalues);
				$this->echoMsg("Starting the function call of purge...", "notice");
				$starttime = microtime (true);
				try {
					$ret = $this->purge($Param[0], intval($Param[1]), intval($Param[2]));
					$endtime = microtime (true);
					$this->processFunction($ret, $starttime, $endtime);
				} catch (Exception $e) {
					$endtime = microtime (true);
					$this->processError($e, $starttime, $endtime);
				}
				break;
			case 'patrol':
				$required = array("id");
				$optional = array("revid");
				$optvalues = array(true);
				$Param = $this->getParams($required, $optional, $optvalues);
				$this->echoMsg("Starting the function call of patrol...", "notice");
				$starttime = microtime (true);
				try {
					$ret = $this->patrol($Param[0], boolval($Param[1]));
					$endtime = microtime (true);
					$this->processFunction($ret, $starttime, $endtime);
				} catch (Exception $e) {
					$endtime = microtime (true);
					$this->processError($e, $starttime, $endtime);
				}
				break;
			case 'review':
				$required = array("revid", "comment", "reason");
				$optional = array("unapprove");
				$optvalues = array(0);
				$Param = $this->getParams($required, $optional, $optvalues);
				$this->echoMsg("Starting the function call of review...", "notice");
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
			case 'checkUserExistence':
				$required = array("username");
				$Param = $this->getParams($required);
				$this->echoMsg("Starting the function call of checkUserExistence...", "notice");
				$starttime = microtime(true);
				try {
					$ret = $this->checkUserExistence($Param[0]);
					$endtime = microtime(true);
					$this->processFunction($ret, $starttime, $endtime);
				} catch (Exception $e) {
					$endtime = microtime (true);
					$this->processError($e, $starttime, $endtime);
				}
				break;
			case 'getUserEditcount':
				$required = array("username");
				$Param = $this->getParams($required);
				$this->echoMsg("Starting the function call of getUserEditcount...", "notice");
				$starttime = microtime(true);
				try {
					$ret = $this->getUserEditcount($Param[0]);
					$endtime = microtime(true);
					$this->processFunction($ret, $starttime, $endtime);
				} catch (Exception $e) {
					$endtime = microtime (true);
					$this->processError($e, $starttime, $endtime);
				}
				break;
			case 'checkUserBlock':
				$required = array("username");
				$Param = $this->getParams($required);
				$this->echoMsg("Starting the function call of checkUserBlock...", "notice");
				$starttime = microtime(true);
				try {
					$ret = $this->checkUserBlock($Param[0]);
					$endtime = microtime(true);
					$this->processFunction($ret, $starttime, $endtime);
				} catch (Exception $e) {
					$endtime = microtime (true);
					$this->processError($e, $starttime, $endtime);
				}
				break;
			case 'checkUserMail':
				$required = array("username");
				$Param = $this->getParams($required);
				$this->echoMsg("Starting the function call of checkUserMail...", "notice");
				$starttime = microtime(true);
				try {
					$ret = $this->checkUserMail($Param[0]);
					$endtime = microtime(true);
					$this->processFunction($ret, $starttime, $endtime);
				} catch (Exception $e) {
					$endtime = microtime (true);
					$this->processError($e, $starttime, $endtime);
				}
				break;
			case 'getUserGroups':
				$required = array("username");
				$Param = $this->getParams($required);
				$this->echoMsg("Starting the function call of getUserGroups...", "notice");
				$starttime = microtime(true);
				try {
					$ret = $this->getUserGroups($Param[0]);
					$endtime = microtime(true);
					$this->processFunction($ret, $starttime, $endtime);
				} catch (Exception $e) {
					$endtime = microtime(true);
					$this->processError($e, $endtime);
				}
				break;
			case 'getUserGender':
				$required = array("username");
				$Param = $this->getParams($required);
				$this->echoMsg("Starting the function call of getUserGender...", "notice");
				$starttime = microtime(true);
				try {
					$ret = $this->getUserGender($Param[0]);
					$endtime = microtime(true);
					$this->processFunction($ret, $starttime, $endtime);
				} catch (Exception $e) {
					$endtime = microtime (true);
					$this->processError($e, $starttime, $endtime);
				}
				break;
			// Query functions
			case 'checkTemplate':
				$required = array("page", "template");
				$Param = $this->getParams($required);
				$this->echoMsg("Starting the function call of checkTemplate...", "notice");
				$starttime = microtime (true);
				try {
					$ret = $this->checkTemplate($Param[0], $Param[1]);
					$endtime = microtime (true);
					$this->processFunction($ret, $starttime, $endtime);
				} catch (Exception $e) {
					$endtime = microtime (true);
					$this->processError($e, $starttime, $endtime);
				}
				break;
			case 'getCatMembers':
				$required = array("kat");
				$optional = array("onlySubCats", "excludeWls");
				$optvalues = array(false, false);
				$Param = $this->getParams($required, $optional, $optvalues);
				$this->echoMsg("Starting the function call of getCatMembers...", "notice");
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
				$this->echoMsg("Starting the function call of getPageCats...", "notice");
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
			case 'getAllEmbeddings':
				$required = array("templ");
				$Param = $this->getParams($required);
				$this->echoMsg("Starting the function call of getAllEmbeddings...", "notice");
				$starttime = microtime (true);
				try {
					$ret = $this->getAllEmbeddings($Param[0]);
					$endtime = microtime (true);
					$this->processFunction($ret, $starttime, $endtime);
				} catch (Exception $e) {
					$endtime = microtime (true);
					$this->processError($e, $starttime, $endtime);
				}
				break;
			case 'getMissingLinks':
				$required = array("page");
				$Param = $this->getParams($required);
				$this->echoNotice('Starting the function call of getMissingLinks...');
				$starttime = microtime(true);
				try {
					$ret = $this->getMissingLinks($Param[0]);
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
				$this->echoMsg("Starting the function call of getAllPages...", "notice");
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
				$this->echoMsg("Starting the function call of getPageID...", "notice");
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
				$this->echoMsg("Starting the function call of getLinks...", "notice");
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
				$this->echoMsg("Starting the function call of getSectionTitle...", "notice");
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
			case 'search':
				$required = array("pattern");
				$optional = array("ns", "prop", "limit", "offset", "what");
				$optvalues = array(0, "size|wordcount|timestamp|snippet", 50, 0, "text");
				$Param = $this->getParams($required, $optional, $optvalues);
				$this->echoNotice('Starting the function call of search...');
				$starttime = microtime (true);
				try {
					$ret = $this->search($Param[0], $Param[1], $Param[2], $Param[3], $Param[4], $Param[5]);
					$endtime = microtime (true);
					$this->processFunction($ret, $starttime, $endtime, true);
				} catch (Exception $e) {
					$endtime = microtime (true);
					$this->processError($e, $starttime, $endtime);
				}
				break;
			// Admin functions
			case 'deletePage':
				$required = array("title", "reason");
				$Param = $this->getParams($required);
				$this->echoMsg("Starting the function call of deletePage...", "notice");
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
				$Param = $this->getParams($required, $optional, $optvalues);
				$this->echoMsg("Starting the function call of blockUser...", "notice");
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
				$this->echoMsg("Starting the function call of unblockUser...", "notice");
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
			case 'blockGlobal':
				$required = array("user", "reason", "expiry");
				$optional = array("unblock", "anononly", "modify");
				$optvalues = array(0, 1, 0);
				$Param = $this->getParams($required, $optional, $optvalues);
				$this->echoMsg("Starting the function call of blockGlobal...", "notice");
				$starttime = microtime (true);
				try {
					$ret = $this->blockGlobal($Param[0], $Param[1], $Param[2], intval($Param[3]), intval($Param[4]),
						intval($Param[5]));
					$endtime = microtime (true);
					$this->processFunction($ret, $starttime, $endtime);
				} catch (Exception $e) {
					$endtime = microtime (true);
					$this->processError($e, $starttime, $endtime);
				}
				break;
			case 'lockGlobal':
				$required = array("user", "lock", "suppress", "reason");
				$Param = $this->getParams($required);
				$this->echoMsg("Starting the function call of lockGlobal...", "notice");
				$starttime = microtime (true);
				try {
					$ret = $this->lockGlobal($Param[0], $Param[1], $Param[2], $Param[3], $Param[4]);
					$endtime = microtime (true);
					$this->processFunction($ret, $starttime, $endtime);
				} catch (Exception $e) {
					$endtime = microtime (true);
					$this->processError($e, $starttime, $endtime);
				}
				break;
			case 'changeUserrights':
				$required = array("username", "groupAdd", "groupRemove", "reason");
				$optional = array("expiry");
				$optvalues = array("infinite");
				$Param = $this->getParams($required, $optional, $optvalues);
				$this->echoMsg("Starting the function call of changeUserrights...", "notice");
				$starttime = microtime (true);
				try {
					$ret = $this->changeUserrights($Param[0], $Param[1], $Param[2], $Param[3], $Param[4]);
					$endtime = microtime (true);
					$this->processFunction($ret, $starttime, $endtime);
				} catch (Exception $e) {
					$endtime = microtime (true);
					$this->processError($e, $starttime, $endtime);
				}
				break;
			case 'changeGlobalUserrights':
				$required = array("username", "groupAdd", "groupRemove", "reason");
				$optional = array("expiry");
				$optvalues = array("infinite");
				$Param = $this->getParams($required, $optional, $optvalues);
				$this->echoMsg("Starting the function call of changeGlobalUserrights...", "notice");
				$starttime = microtime (true);
				try {
					$ret = $this->changeGlobalUserrights($Param[0], $Param[1], $Param[2], $Param[3], $Param[4]);
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
				$this->echoMsg("Starting the function call of protectPage...", "notice");
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
				$this->echoMsg("Starting the function call of stabilize...", "notice");
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
			case 'writeToFile':
				$required = array("filename", "text");
				$Param = $this->getParams($required);
				$this->echoMsg("Starting the function call of writeToFile...", "notice");
				$starttime = microtime (true);
				try {
					$ret = $this->writeToFile($Param[0], $Param[1]);
					$endtime = microtime (true);
					$this->processFunction($ret, $starttime, $endtime);
				} catch (Exception $e) {
					$endtime = microtime (true);
					$this->processError($e, $starttime, $endtime);
				}
				break;
			default:
				echo "This function does not exist, or is not configured.";
		}
	}
	/** getParams
	* This method gets the params from the user
	* There are two lists, one with params, one with optional params
	*/
	private function getParams($ParamList, $ParamOptList = null, $ParamOptDefault = null) {
		$a = 0;
		while (isset($ParamList[$a])) {
			$Param[$a] = $this->ask("Enter the value for the param " . $ParamList[$a] . " to use:", "required");
			$a++;
		}
		$b = 0;
		while (isset($ParamOptList[$b])) {
			$res = $this->ask("Enter the value for the optional param " . $ParamOptList[$b] . " (Leave blank to use default):", "optional");
			if ($res === "") {
				$Param[$a] = $ParamOptDefault[$b];
			} else {
				$Param[$a] = $res;
			}
			$a++;
			$b++;
		}
		return $Param;
	}
	/** processFunction
	* Internal, used for successful calls
	*/
	private function processFunction($ret, $starttime, $endtime) {
		$total = $endtime - $starttime;
		$this->echoMsg("Function call succeeded", "success");
		$this->echoMsg("Performance: " . $total . " seconds", "notice");
		$answer = $this->ask("Display the result now? [y/N]", "required");
		if (strtolower($answer) !== 'n') {
			if (is_array($ret)) {
				var_dump($ret);
			} else {
				$this->echoMsg($ret, "output");
			}
		}
	}
	/** processError
	* Internal, used for errors
	*/
	private function processError ($err, $starttime, $endtime) {
		$total = $endtime - $starttime;
		$this->echoMsg("Function call failed after " . $total . " seconds", "error");
		$answer = $this->ask("Display the error now? [y/N]", "required");
		if (strtolower($answer) !== 'n') {
			$this->echoMsg($err, "warning");
		}
	}
}
$Bot = new Debug();
?>

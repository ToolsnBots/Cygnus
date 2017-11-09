<?php
require_once __DIR__ . '/BotCore.php';
/** TestCore.php
* Allows using Cygnus for testing
* @Author Luke081515 <luke081515@tools.wmflabs.org>
* @Version 1.0
* @Status Beta
*/
class TestCore extends Core {
	/** __construct
	* @param $loginData (Array)
	* $loginData[1] - the site to use
	* $loginData[2] - the username to use
	* $loginData[3] - the password to use
	* $loginData[0] is ignored, since this program is mostly for args
	** args[0] is the program name, so useless here
	*/
	public function __construct($loginData) {
		$this->setSite($loginData[1]);
		$this->setUsername($loginData[2]);
		$this->setPassword($loginData[3]);
		$this->setTarget("w/api.php");
		$this->initcurlArgs('TestCore', true, "bot", true);
		$this->login();
	}
	/** execute
	* @param - $functionData Array
	** $functionData[0] is the name of the function to execute
	** $functionData - all following values are the params
	** Takes up to 10 parameters
	* @Author Luke081515
	* @returns the return value of the Cygnus function
	*/
	public function execute($functionData) {
		$functionName = $functionData[0];
		switch(count($functionData)) {
			case 1:
				return $this->$functionName();
			case 2:
				return $this->$functionName($functionData[1]);
			case 3:
				return $this->$functionName($functionData[1], $functionData[2]);
			case 4:
				return $this->$functionName($functionData[1], $functionData[2], $functionData[3]);
			case 5:
				return $this->$functionName($functionData[1], $functionData[2], $functionData[3], $functionData[4]);
			case 6:
				return $this->$functionName($functionData[1], $functionData[2], $functionData[3], $functionData[4],
					$functionData[5]);
			case 7:
				return $this->$functionName($functionData[1], $functionData[2], $functionData[3], $functionData[4],
					$functionData[5], $functionData[6]);
			case 8:
				return $this->$functionName($functionData[1], $functionData[2], $functionData[3], $functionData[4],
					$functionData[5], $functionData[6], $functionData[7]);
			case 9:
				return $this->$functionName($functionData[1], $functionData[2], $functionData[3], $functionData[4],
					$functionData[5], $functionData[6], $functionData[7], $functionData[8]);
			case 10:
				return $this->$functionName($functionData[1], $functionData[2], $functionData[3], $functionData[4],
					$functionData[5], $functionData[6], $functionData[7], $functionData[8], $functionData[9]);
			case 11:
				return $this->$functionName($functionData[1], $functionData[2], $functionData[3], $functionData[4],
					$functionData[5], $functionData[6], $functionData[7], $functionData[8], $functionData[9],
					$functionData[10]);
			default:
				return null;
		}
	}
}
?>
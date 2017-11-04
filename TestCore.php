<?php
require_once __DIR__ . '/BotCore.php';
/** Debug.php
* * @Author Luke081515 <luke081515@tools.wmflabs.org>
* @Version 0.1
* @Status Alpha
*/
class TestCore extends Core {
	public function __construct($loginData) {
		$this->setSite($loginData[1]);
		$this->setUsername($loginData[2]);
		$this->setPassword($loginData[3]);
		$this->initcurlArgs('TestCore', true);
		$this->login();
	}
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
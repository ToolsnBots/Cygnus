<?php
require_once __DIR__ . '/../TestCore.php';
use PHPUnit\Framework\TestCase;

/**
* @author Luke081515
*/
final class BotCoreBasicTest extends TestCase {
	public $params;
	public $Core;

	private function createLogin() {
		global $argv;
		$i = 0;
		for ($j = 1; isset($argv[$j]); $j++) {
			$params[$i] = $argv[$j];
			$i++;
		}
		return new TestCore($params);
	}
	/**
	* @covers BotCore::requireToken
	* checks that login works as well, otherwise csrf token is +\\
	*/
	public function testRequireValidToken() {
		$Core = $this->createLogin();
		$notExpected = "+\\";
		$actually = $Core->execute(array("requireToken", "csrf"));
		$this->assertNotEquals($notExpected, $actually);
	}
	// ToDo: Add allowBots
	// ToDo: Add curlRequest
}
?>

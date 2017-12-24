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
	/**
	* @covers BotCore::allowBots
	*/
	public function testNoBotsNoBots() {
		$Core = $this->createLogin();
		$text = $Core->readPage("AllowBots1");
		$result = $Core->allowBots($text);
		$this->assertFalse($result);
	}
	/**
	* @covers BotCore::allowBots
	*/
	public function testNoBotsBots() {
		$Core = $this->createLogin();
		$text = $Core->readPage("AllowBots2");
		$result = $Core->allowBots($text);
		$this->assertTrue($result);
	}
	/**
	* @covers BotCore::allowBots
	*/
	public function testNoBotsAllowMe() {
		$Core = $this->createLogin();
		$text = $Core->readPage("AllowBots3");
		$result = $Core->allowBots($text);
		$this->assertTrue($result);
	}
	/**
	* @covers BotCore::allowBots
	*/
	public function testNoBotsDenyMe() {
		$Core = $this->createLogin();
		$text = $Core->readPage("AllowBots4");
		$result = $Core->allowBots($text);
		$this->assertFalse($result);
	}
	/**
	* @covers BotCore::allowBots
	*/
	public function testNoBotsAllowMeList() {
		$Core = $this->createLogin();
		$text = $Core->readPage("AllowBots5");
		$result = $Core->allowBots($text);
		$this->assertTrue($result);
	}
	/**
	* @covers BotCore::allowBots
	*/
	public function testNoBotsDenyMeList() {
		$Core = $this->createLogin();
		$text = $Core->readPage("AllowBots6");
		$result = $Core->allowBots($text);
		$this->assertFalse($result);
	}
	/**
	* @covers BotCore::allowBots
	*/
	public function testNoBotsAllowAll() {
		$Core = $this->createLogin();
		$text = $Core->readPage("AllowBots7");
		$result = $Core->allowBots($text);
		$this->assertTrue($result);
	}
	/**
	* @covers BotCore::allowBots
	*/
	public function testNoBotsAllowNone() {
		$Core = $this->createLogin();
		$text = $Core->readPage("AllowBots8");
		$result = $Core->allowBots($text);
		$this->assertFalse($result);
	}
	/**
	* @covers BotCore::allowBots
	*/
	public function testNoBotsDenyAll() {
		$Core = $this->createLogin();
		$text = $Core->readPage("AllowBots9");
		$result = $Core->allowBots($text);
		$this->assertFalse($result);
	}
	/**
	* @covers BotCore::allowBots
	*/
	public function testNoBotsDenyNone() {
		$Core = $this->createLogin();
		$text = $Core->readPage("AllowBots10");
		$result = $Core->allowBots($text);
		$this->assertTrue($result);
	}
	// ToDo: Add curlRequest
}
?>

<?php
require_once __DIR__ . '/../TestCore.php';
use PHPUnit\Framework\TestCase;

/**
* @covers BotCore
* Tests for all read function
*/
final class BotCoreUserPropertyTest extends TestCase {
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
	* @covers BotCore::checkUserExistence
	*/
	public function testUserExistsSuccessful() {
		$Core = $this->createLogin();
		$actually = $Core->execute(array("checkUserExistence", "Luke081515"));
		$this->assertTrue($actually);
	}
	/**
	* @covers BotCore::checkUserExistence
	*/
	// Currently failing, see #26
	/*public function testUserDoesNotExists() {
		$Core = $this->createLogin();
		$actually = $Core->execute(array("checkUserExistence", "LukeO81515"));
		$this->assertFalse($actually);
	}*/
	/**
	* @covers BotCore::getUserEditcount
	*/
	public function testUserEditcountSuccessful() {
		$Core = $this->createLogin();
		$expected = 1;
		$actually = $Core->execute(array("getUserEditcount", "JoanuznropfzlbBolt"));
		$this->assertEquals($expected, $actually);
	}
	/**
	* @covers BotCore::getUserEditcount
	*/
	// Currently failing, see #26
	/*public function testUserEditcountDoesNotExists() {
		$Core = $this->createLogin();
		$actually = $Core->execute(array("getUserEditcount", "LukeO81515"));
		$this->assertFalse($actually);
	}*/
	/**
	* @covers BotCore::checkUserBlock
	*/
	public function testUserBlockBlocked() {
		$Core = $this->createLogin();
		$actually = $Core->execute(array("checkUserBlock", "MonikaGoshorn79"));
		$this->assertTrue($actually);
	}
	/**
	* @covers BotCore::checkUserBlock
	*/
	public function testUserBlockNotBlocked() {
		$Core = $this->createLogin();
		$actually = $Core->execute(array("checkUserBlock", "Luke081515"));
		$this->assertFalse($actually);
	}
	/**
	* @covers BotCore::checkUserMail
	*/
	public function testUserMailSuccessful() {
		$Core = $this->createLogin();
		$actually = $Core->execute(array("checkUserMail", "Luke081515"));
		$this->assertTrue($actually);
	}
	/**
	* @covers BotCore::checkUserMail
	*/
	public function testUserMailMissing() {
		$Core = $this->createLogin();
		$actually = $Core->execute(array("checkUserMail", "Luke081515.2"));
		$this->assertFalse($actually);
	}
	/**
	* @covers BotCore::checkUserMail
	*/
	public function testUserMailUserMissing() {
		$Core = $this->createLogin();
		$actually = $Core->execute(array("checkUserMail", "LukeO81515"));
		$this->assertFalse($actually);
	}
}
?>

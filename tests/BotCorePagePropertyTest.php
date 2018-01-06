<?php
require_once __DIR__ . '/../TestCore.php';
use PHPUnit\Framework\TestCase;

/**
* @covers BotCore
* Tests for all read function
*/
final class BotCorePagePropertyTest extends TestCase {
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
	public function testGetLinksSuccessful() {
		$Core = $this->createLogin();
		$expected = "Hauptseite";
		$actually = $Core->execute(array("getLinks", "Benutzer:Luke081515Bot/Bluelink"));
		$this->assertEquals($expected, $actually[0]);
	}
	public function testgetLinksFail() {
		$Core = $this->createLogin();
		$expected = false;
		$actually = $Core->execute(array("getLinks", "Benutzer:Luke081515Bot/NoLink"));
		$this->assertEquals($expected, $actually);
	}
}
?>

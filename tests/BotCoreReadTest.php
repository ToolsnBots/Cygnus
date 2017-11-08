<?php
require_once __DIR__ . '/../TestCore.php';
use PHPUnit\Framework\TestCase;

/**
* @covers BotCore
* Tests for all read function
*/
final class BotCoreReadTest extends TestCase {
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
	public function testReadPageSuccessful() {
		$Core = $this->createLogin();
		$expected = "#WEITERLEITUNG [[Wikipedia:Hauptseite]]";
		$actually = $Core->execute(array("readPage", "Hauptseite"));
		$this->assertEquals($expected, $actually);
	}
	public function testReadPageJsSuccessful() {
		$Core = $this->createLogin();
		$expected = "UTTest";
		$actually = $Core->execute(array("readPageJs", "Benutzer:Luke081515Bot/Testpage.js"));
		$this->assertEquals($expected, $actually);
	}
	public function testReadPageCssSuccessful() {
		$Core = $this->createLogin();
		$expected = "UTTest";
		$actually = $Core->execute(array("readPageCss", "Benutzer:Luke081515Bot/Testpage.css"));
		$this->assertEquals($expected, $actually);
	}
}
?>

<?php
require_once __DIR__ . '/../TestCore.php';
use PHPUnit\Framework\TestCase;

/**
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
	/**
	* @covers BotCore::readPage
	*/
	public function testReadPageSuccessful() {
		$Core = $this->createLogin();
		$expected = "#WEITERLEITUNG [[Wikipedia:Hauptseite]]";
		$actually = $Core->execute(array("readPage", "Hauptseite"));
		$this->assertEquals($expected, $actually);
	}
	/**
	* @covers BotCore::readPage
	*/
	public function testReadPageMissing() {
		$Core = $this->createLogin();
		$expected = null;
		$actually = $Core->execute(array("readPage", "Sfdfsdf"));
		$this->assertEquals($expected, $actually);
	}
	/**
	* @covers BotCore::readPageJs
	*/
	public function testReadPageJsSuccessful() {
		$Core = $this->createLogin();
		$expected = "UTTest";
		$actually = $Core->execute(array("readPageJs", "Benutzer:Luke081515Bot/Testpage.js"));
		$this->assertEquals($expected, $actually);
	}
	/**
	* @covers BotCore::readPageCss
	*/
	public function testReadPageCssSuccessful() {
		$Core = $this->createLogin();
		$expected = "UTTest";
		$actually = $Core->execute(array("readPageCss", "Benutzer:Luke081515Bot/Testpage.css"));
		$this->assertEquals($expected, $actually);
	}
	/**
	* @covers BotCore::readSection
	*/
	public function testReadSection0Successful() {
		$Core = $this->createLogin();
		$expected = "text";
		$actually = $Core->execute(array("readSection", "Benutzer:Luke081515Bot/SectionTest"));
		$this->assertEquals($expected, $actually);
	}
	/**
	* @covers BotCore::readSection
	*/
	public function testReadSection1Successful() {
		$Core = $this->createLogin();
		$expected = "== Section 1 ==\ntext 2";
		$actually = $Core->execute(array("readSection", "Benutzer:Luke081515Bot/SectionTest"));
		$this->assertEquals($expected, $actually);
	}
}
?>

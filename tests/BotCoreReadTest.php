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
		$i = 1;
		while ($argv[$j] === "CS") {
			$i++;
		}
		while (isset($argv[$i])) {
			$params[] = $argv[$i];
			$i++;
		}
		return new TestCore($params);
	}
	/**
	* @covers BotCore::readPage
	*/
	public function testReadPageSuccessful() {
		$Core = $this->createLogin();
		$expected = "DummyContent";
		$actually = $Core->execute(array("readPage", "DummyPage"));
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
		$actually = $Core->execute(array("readPageJs", "User:Luke081515Bot/Testpage.js"));
		$this->assertEquals($expected, $actually);
	}
	/**
	* @covers BotCore::readPageCss
	*/
	public function testReadPageCssSuccessful() {
		$Core = $this->createLogin();
		$expected = "UTTest";
		$actually = $Core->execute(array("readPageCss", "User:Luke081515Bot/Testpage.css"));
		$this->assertEquals($expected, $actually);
	}
	/**
	* @covers BotCore::readSection
	*/
	public function testReadSection0Successful() {
		$Core = $this->createLogin();
		$expected = "text";
		$actually = $Core->execute(array("readSection", "User:Luke081515Bot/SectionTest", 0));
		$this->assertEquals($expected, $actually);
	}
	/**
	* @covers BotCore::readSection
	*/
	public function testReadSection1Successful() {
		$Core = $this->createLogin();
		$expected = "== Section 1 ==\ntext 2";
		$actually = $Core->execute(array("readSection", "User:Luke081515Bot/SectionTest", 1));
		$this->assertEquals($expected, $actually);
	}
	/**
	* @covers BotCore::getPageID
	*/
	public function testgetPageIDSuccessful() {
		$Core = $this->createLogin();
		$expected = "9";
		$actually = $Core->execute(array("getPageID", "DummyPage"));
		$this->assertEquals($expected, $actually);
	}
	/**
	* @covers BotCore::getPageID
	*/
	public function testgetPageIDMissing() {
		$Core = $this->createLogin();
		$actually = $Core->execute(array("getPageID", "Sfdfsdf"));
		$this->assertFalse($actually);
	}
}
?>

<?php
require_once __DIR__ . '/../TestCore.php';
use PHPUnit\Framework\TestCase;

/**
* @covers BotCore::readPage
* Tests for all read function
*/
final class BotCoreReadAssertFailTest extends TestCase {
	public function testReadPageAssertFail() {
		global $argv;
		$i = 0;
		for ($j = 1; isset($argv[$j]); $j++) {
			$params[$i] = $argv[$j];
			$i++;
		}
		$Core = new TestCore($params);
		$this->setExpectedException('Exception');
		$Core->execute(array("readPage", "Hauptseite"));
	}
}
?>
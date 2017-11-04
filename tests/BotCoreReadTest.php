<?php
require_once __DIR__ . '/../TestCore.php';
use PHPUnit\Framework\TestCase;

/**
* @covers BotCore
* Tests for all read function
*/
final class BotCoreReadTest extends TestCase {
	public function testReadPageSuccessful() {
		global $argv;
		$i = 0;
		for ($j = 1; isset($argv[$j]); $j++) {
			$params[$i] = $argv[$j];
			$i++;
		}
		$Core = new TestCore($params);
		$expected = "#WEITERLEITUNG [[Wikipedia:Hauptseite]]";
		$actually = $Core->execute(array("readPage", "Hauptseite"));
		$this->assertEquals($expected, $actually);
	}
}
?>
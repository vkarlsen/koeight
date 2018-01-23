<?php

/**
 * Tests the Minion library
 *
 * @group kohana
 * @group kohana.core
 * @group kohana.core.config
 *
 * @package    Koseven
 * @category   Tests
 * @author     Koseven Team
 * @author     Piotr Gołasz <pgolasz@gmail.com>
 * @copyright  (c) Kohana Team
 * @license    https://koseven.ga/LICENSE.md
 */
class TaskTest extends Unittest_TestCase
{

	/**
	 * Tests that Minion Task Help works assuming all other tasks work aswell
	 */
	public function test_minion_runnable()
	{
		$output = '';
		$path = DOCROOT . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'index.php --task=help';
		echo $path.PHP_EOL;
		exec('php ' . $path, $output);
		var_dump($output);
		$this->assertContains('Minion is a cli tool for performing tasks', $output);
	}

}

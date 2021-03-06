<?php
App::uses('AllTestsBase', 'Test/Lib');

class AllTwitterTestsTest extends AllTestsBase {

/**
 * Suite define the tests for this suite
 *
 * @return void
 */
	public static function suite() {
		$suite = new CakeTestSuite('All Twitter test');

		$path = CakePlugin::path('Twitter') . 'Test' . DS . 'Case' . DS;
		$suite->addTestDirectoryRecursive($path);

		return $suite;
	}
}

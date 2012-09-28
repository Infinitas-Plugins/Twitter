<?php
App::uses('Connect', 'Twitter.Model');

/**
 * Connect Test Case
 *
 */
class ConnectTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.twitter.connect'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Connect = ClassRegistry::init('Twitter.Connect');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Connect);

		parent::tearDown();
	}

/**
 * testFormatQueryString method
 *
 * @return void
 */
	public function testFormatQueryString() {
	}

}

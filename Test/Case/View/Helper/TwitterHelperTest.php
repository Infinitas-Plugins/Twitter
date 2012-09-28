<?php
App::uses('View', 'View');
App::uses('Helper', 'View');
App::uses('TwitterHelper', 'Twitter.View/Helper');

/**
 * TwitterHelper Test Case
 *
 */
class TwitterHelperTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$View = new View();
		$this->Twitter = new TwitterHelper($View);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Twitter);

		parent::tearDown();
	}

/**
 * testLogin method
 *
 * @return void
 */
	public function testLogin() {
	}

/**
 * testLogout method
 *
 * @return void
 */
	public function testLogout() {
	}

/**
 * testAvitar method
 *
 * @return void
 */
	public function testAvitar() {
	}

/**
 * testTweetButton method
 *
 * @return void
 */
	public function testTweetButton() {
	}

/**
 * testFollowMe method
 *
 * @return void
 */
	public function testFollowMe() {
	}

}

<?php
	/*
	 * twitter controller.
	 *
	 * Handles login for twitter.
	 * 
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * 
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package twitter
	 * @subpackage twitter.twitter_controller
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.1
	 * 
	 * @author dogmatic69
	 * 
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	class ConnectsController extends TwitterAppController {
		/**
		 * class name
		 *
		 * @var string
		 */
		public $name = 'Connects';

		/**
		 * use the model for conecting and authenticating
		 */
		public $uses = array(
			'Twitter.Connect'
		);

		/**
		 * Components to load
		 *
		 * @var array
		 */
		public $components = array(
			'Session'
		);

		/**
		 * need to figure out what this is about.
		 */
		public function beforeFilter(){
			parent::beforeFilter();
			$this->Connect->enabled = null;
		}

		/**
		 * connect to twitter
		 */
		public function connect() {
			if($this->Session->read('Twitter')){
				$this->notice(
					__('Your twitter account is already linked', true),
					array(
						'redirect' => true
					)
				);
			}
			
			$this->Session->write('Twitter.referer', $this->referer());

			$connection = $this->Connect->find(
				'first',
				array(
					'conditions' => array(
						'Connect.type' => 'request'
					)
				)
			);
			
			$connection = $this->Connect->formatQueryString($connection);	
			$url = sprintf($this->Connect->authorizeUrl, $connection['Connect']['oauth_token']);
			$this->redirect($url);
		}

		/**
		 * callback for twitter to give the app a key
		 */
		public function callback() {
			if(!$this->Session->read('Twitter.referer')){
				$this->notice(
					__('Something went wrong, please try again', true),
					array(
						'redirect' => '/'
					)
				);
			}

			$connection = $this->Connect->find(
				'first',
				array(
					'conditions' => array(
						'Connect.type' => 'access',
						'Connect.oauth_token' => $this->params['url']['oauth_token'],
						'Connect.oauth_verifier' => $this->params['url']['oauth_verifier']
					)
				)
			);

			$connection = $this->Connect->formatQueryString($connection);

			if(!isset($connection['Connect']) || empty($connection['Connect'])){
				$this->notice(
					__('There was an error authenticating you, please try agian', true),
					array(
						'redirect' => '/'
					)
				);
			}
			
			$this->Session->write('Twitter', $connection['Connect']);
			$this->__linkOrCreateAccount($connection['Connect']);
		}

		/**
		 * log them out the twitter profile by deleting the session and then
		 * head to infinitas logout
		 *
		 * @param array $options options for the logout
		 * redirect - where to send the user
		 *
		 * http://dev.twitter.com/doc/post/account/end_session
		 */
		public function logout(){
			$_options['redirect'] = $this->referer();
			$options = array_merge($_options, (array)Configure::read('Twitter.logout'));
			$this->Session->delete('Twitter');
			$this->redirect($options['redirect']);
		}

		/**
		 * when someone wants to remove their account
		 */
		public function unlink(){
			$id = $this->Session->read('Auth.User.id');
			if(!$id){
				$this->notice(
					__('You are not allowed to do that', true),
					array(
						'redirect' => true
					)
				);
			}

			ClassRegistry::init('Users.User')->save(array('User' => array('User.id' => $id, 'User.twitter_id' => 0)));
		}

		/**
		 * figure out what someone needs when they log in.
		 */
		private function __linkOrCreateAccount($user = null){
			$redirect = $this->Session->read('Twitter.redirect');
			$User = ClassRegistry::init('Users.User');
			$id = $this->Session->read('Auth.User.id');
			
			$linked = $User->find(
				'first',
				array(
					'conditions' => array(
						'User.twitter_id' => $user['user_id']
					)
				)
			);

			var_dump(!$linked && $id);

			// not linked and logged in
			if(!$linked && $id){
				$User->id = $id;
				$User->saveField('twitter_id', $user['user_id']);
				
				$this->Session->write('Twitter.new_user', true);
				$this->redirect($redirect);
			}

			// linked but not logged in
			else if(!empty($linked) && !$id){
				$this->Session->write('Auth.User', $existing['User']);
				$this->redirect($redirect);
			}

			// completely new user
			else{
				$User->create();
				$User->save(
					array(
						'User' => array(
							'User.username' => $user['screen_name'],
							'User.twitter_id' => $user['user_id'],
							'User.password' => sha1($user['user_id'])
						)
					),
					array(
						'validate' => false
					)
				);
				
				$this->Session->write('Twitter.new_user', true);
				$this->redirect($redirect);
			}
		}
	}

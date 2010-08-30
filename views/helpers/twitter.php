<?php
	/* 
	 * Short Description / title.
	 * 
	 * Overview of what the file does. About a paragraph or two
	 * 
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * 
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package {see_below}
	 * @subpackage {see_below}
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since {check_current_milestone_in_lighthouse}
	 * 
	 * @author {your_name}
	 * 
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	class TwitterHelper extends AppHelper{
		public $helpers = array(
			'Text', 'Html'
		);

		public function  __construct() {
			$this->__settings = Configure::read('Twitter');
		}

		private $__avitar = array(
			 'url' => 'http://api.twitter.com/1/users/profile_image/%s.json?size=%s',
			 'sizes' => array(
				 'small'  => 'mini',   // 24 x 24
				 'medium' => 'normal', // 48 x 48
				 'large'  => 'bigger'  // 73 x 73
			 )
		);

		private $__profile = 'http://twitter.com/%s';
		 
		/**
		 * login button for twitter
		 */
		public function login(){
			return $this->Html->image(
				'/twitter/img/login.png',
				array(
					'url' => array(
						'plugin' => 'twitter',
						'controller' => 'connects',
						'action' => 'connect'
					),
					'alt' => 'Twitter login'
				)
			);
		}

		/**
		 * logout button
		 */
		public function logout(){
			return $this->Html->image(
				'/twitter/img/login.png',
				array(
					'url' => array(
						'plugin' => 'twitter',
						'controller' => 'connects',
						'action' => 'logout'
					),
					array(
						'alt' => 'Twitter logout'
					)
				)
			);
		}

		/**
		 * users icon
		 */
		public function avitar($size = 'medium', $user = null){
			if(!$user){
				$user = $this->Session->read('Twitter.screen_name');
			}

			if(!in_array($size, array_keys($this->__avitar['sizes']))){
				$size = 'medium';
			}
						
			return $this->Html->link(
				$this->Html->image(
					sprintf($this->__avitar['url'], $user, $this->__avitar['sizes'][$size]),
					array('alt' => $user)
				),
				sprintf($this->__profile, $user),
				array(
					'target' => '_blank',
					'escape' => false
				)
			);
		}

		/**
		 * create a tweet button.
		 *
		 * Generates a button according to the tweet api
		 * http://dev.twitter.com/pages/tweet_button
		 *
		 * uses the defalts in config if you dont pass anything.
		 */
		public function tweetButton($options = array()){
			$options = array_merge($this->__settings['tweetButton'], (array)$options);

			if(!$options['text']){
				$options['text'] = $this->Text->truncate(strip_tags(Configure::read('Website.description')), 100);
			}

			// if not url get current url from the infinitas shortener
			
			return $this->Html->link(
				__('Tweet', true),
				sprintf('http://twitter.com/share?%s', http_build_query(array_filter($options))),
				array(
					'class' => 'twitter-share-button'
				)
			);
		}
	}
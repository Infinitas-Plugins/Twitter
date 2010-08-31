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
			'Text', 'Html', 'Session'
		);

		public function  __construct() {
			$this->__settings = Configure::read('Twitter');
		}

		private $__followImages = array(
			1 => array(
				1 => 'http://twitter-badges.s3.amazonaws.com/follow_me-a.png',
				2 => 'http://twitter-badges.s3.amazonaws.com/follow_bird-a.png',
				3 => 'http://twitter-badges.s3.amazonaws.com/twitter-a.png',
				4 => 'http://twitter-badges.s3.amazonaws.com/t_logo-a.png',
				5 => 'http://twitter-badges.s3.amazonaws.com/t_small-a.png',
				6 => 'http://twitter-badges.s3.amazonaws.com/t_mini-a.png'
			),
			2 => array(
				1 => 'http://twitter-badges.s3.amazonaws.com/follow_me-b.png',
				2 => 'http://twitter-badges.s3.amazonaws.com/follow_bird-b.png',
				3 => 'http://twitter-badges.s3.amazonaws.com/twitter-b.png',
				4 => 'http://twitter-badges.s3.amazonaws.com/t_logo-b.png',
				5 => 'http://twitter-badges.s3.amazonaws.com/t_small-b.png',
				6 => 'http://twitter-badges.s3.amazonaws.com/t_mini-b.png'
			),
			3 => array(
				1 => 'http://twitter-badges.s3.amazonaws.com/follow_me-c.png',
				2 => 'http://twitter-badges.s3.amazonaws.com/follow_bird-c.png',
				3 => 'http://twitter-badges.s3.amazonaws.com/twitter-c.png',
				4 => 'http://twitter-badges.s3.amazonaws.com/t_logo-c.png',
				5 => 'http://twitter-badges.s3.amazonaws.com/t_small-c.png',
				6 => 'http://twitter-badges.s3.amazonaws.com/t_mini-c.png'
			)
		);

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
		public function avitar($size = 'medium', $user = null, $returnPath = false){
			if(!$user){
				$user = $this->Session->read('Twitter.screen_name');
			}

			if(!in_array($size, array_keys($this->__avitar['sizes']))){
				$size = 'medium';
			}

			$imageUrl = sprintf($this->__avitar['url'], $user, $this->__avitar['sizes'][$size]);
			if($returnPath){
				return $imageUrl;
			}
						
			return $this->Html->link(
				$this->Html->image(
					$imageUrl,
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

		/**
		 * create a follow me button.
		 *
		 * if no options are passed the options from the config file will be used.
		 * You can use any full url or a cakephp image url for the button. if you
		 * pass 'avitar' as the image the avitar for the account that is setup will
		 * show
		 *
		 * @param $image string url, relative path or 'avitar'
		 * @param $options array
		 *  + image like $image
		 *  + size - for default images the size of the image
		 *  + color - for default images the color of the image
		 */
		public function followMe($image = null, $options = array()){
			$options = array_merge($this->__settings['followMe'], $options);

			if(!$image){
				$image = $this->__settings['followMe']['image'];
				if(!$image){
					$image = $this->__followImages[1][1]; // set a default
					
					$show =
						isset($options['color']) &&
						isset($options['size']) &&
						isset($this->__followImages[$options['color']][$options['size']]);

					if($show){
						$image = $this->__followImages[$options['color']][$options['size']];
					}
				}
			}

			if($image == 'avitar'){
				$image = $this->avitar(null, $this->__settings['username'], true);
			}

			return $this->Html->image(
				$image,				
				array(
					'url' => sprintf('http://www.twitter.com/', $this->__settings['username']),
					'alt' => sprintf(__('Follow %s on Twitter', true), $this->__settings['username']),
					'title' => sprintf(__('Follow %s on Twitter', true), $this->__settings['username'])
				)
			);			
		}
	}
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

	 class TwitterEvents extends AppEvents{
		private function __getConfig() {
			$config = Configure::read('Twitter');

			if(empty($config)) {
				Configure::load('Twitter.twitter');
				$config = Configure::read('Twitter');
			}

			return $config;
		}

		public function onSetupCache(){
			return array(
				'name' => 'twitter',
				'config' => array(
					'duration' => 3600,
					'probability' => 100,
					'prefix' => 'twitter.',
					'lock' => false,
					'serialize' => true
				)
			);
		}

		public function onSetupConfig(){
			return Configure::load('twitter.config');
		}

		public function onRequireComponentsToLoad(){
			Configure::load('Twitter.config');
			return array(
				//'Twitter.Twitter'
			);
		}

		public function onRequireHelpersToLoad(){
			return array(
				'Twitter.Twitter'
			);
		}

		public function onRequireCssToLoad(){
			return array();
		}

		public function onRequireJavascriptToLoad(&$event){
			return array(
				'http://platform.twitter.com/widgets.js'
			);
		}
		
		/**
		 * Called before cms content is echo'ed
		 */
		public function onCmsBeforeContentRender(&$event, $data) {
			$config = $this->__getConfig();
			if(isset($config['onCmsBeforeContentRender']) && in_array('tweet', $config['onCmsBeforeContentRender'])) {
				$link = $data['_this']->Event->trigger('cms.slugUrl', array('type' => 'contents', 'data' => $data['content']));
				return $data['_this']->Twitter->tweetButton(
					array(
						'url' => Router::url(current($link['slugUrl']), true),
						'text' => $data['content']['Content']['title']
					)
				);
			}
		}
		
		/**
		 * Called after cms content is echo'ed
		 */
		public function onCmsAfterContentRender(&$event, $data) {
			$config = $this->__getConfig();
			if(isset($config['onCmsAfterContentRender']) && in_array('tweet', $config['onCmsAfterContentRender'])) {
				$link = $data['_this']->Event->trigger('cms.slugUrl', array('type' => 'contents', 'data' => $data['content']));
				return $data['_this']->Twitter->tweetButton(
					array(
						'url' => Router::url(current($link['slugUrl']), true),
						'text' => $data['content']['Content']['title']
					)
				);
			}
		}

		/**
		 * Called before blog post is echo'ed
		 */
		public function onBlogBeforeContentRender(&$event, $data) {
			$config = $this->__getConfig();
			if(isset($config['onBlogBeforeContentRender']) && in_array('tweet', $config['onBlogBeforeContentRender'])) {
				$link = $data['_this']->Event->trigger('blog.slugUrl', array('type' => 'posts', 'data' => $data['post']));
				return $data['_this']->Twitter->tweetButton(
					array(
						'url' => Router::url(current($link['slugUrl']), true),
						'text' => $data['post']['Post']['title']
					)
				);
			}
		}

		/**
		 * Called after blog post is echo'ed
		 */
		public function onBlogAfterContentRender(&$event, $data) {
			$config = $this->__getConfig();
			if(isset($config['onBlogAfterContentRender']) && in_array('tweet', $config['onBlogAfterContentRender'])) {
				$link = $data['_this']->Event->trigger('blog.slugUrl', array('type' => 'posts', 'data' => $data['post']));
				return $data['_this']->Twitter->tweetButton(
					array(
						'url' => Router::url(current($link['slugUrl']), true),
						'text' => $data['post']['Post']['title']
					)
				);
			}
		}

		public function onUserProfile(&$event){
			return array(
				'element' => 'profile'
			);
		}
	 }
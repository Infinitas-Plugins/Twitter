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

	 class TwitterEvents extends AppEvents {
		private function __getConfig() {
			$config = Configure::read('Twitter');

			if(empty($config)) {
				return false;
			}

			return $config;
		}

		public function onSetupCache(Event $Event) {
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

		public function onRequireHelpersToLoad(Event $Event) {
			return array(
				'Twitter.Twitter'
			);
		}

		public function onRequireJavascriptToLoad(Event $Event) {
			return array(
				'http://platform.twitter.com/widgets.js'
			);
		}

		public function onSetupRoutes(Event $Event) {
			InfinitasRouter::connect('/twitter-callback', array(
				'plugin' => 'twitter',
				'controller' => 'connects',
				'action' => 'callback'
			));
		}

		public function onRequireDatabaseConfigs(Event $Event) {
			return array(
				'twitter' => array(
					'datasource' => 'Libs.JsonSource'
				)
			);
		}

		/**
		 * Called before cms content is echo'ed
		 *
		 * @deprecated
		 */
		public function onCmsBeforeContentRender(Event $Event, $data) {
			if(isset($config['onCmsBeforeContentRender']) && in_array('tweet', $config['onCmsBeforeContentRender'])) {
				$link = $data['_this']->Event->trigger('Cms.slugUrl', array('type' => 'contents', 'data' => $data['content']));
				return $data['_this']->Twitter->tweetButton(
					array(
						'url' => Router::url(current($link['slugUrl']), true),
						'text' => $data['content']['CmsContent']['title']
					)
				);
			}
		}

		/**
		 * Called after cms content is echo'ed
		 *
		 * @deprecated
		 */
		public function onCmsAfterContentRender(Event $Event, $data) {
			$config = $this->__getConfig();
			if(isset($config['onCmsAfterContentRender']) && in_array('tweet', $config['onCmsAfterContentRender'])) {
				$link = $data['_this']->Event->trigger('Cms.slugUrl', array('type' => 'contents', 'data' => $data['content']));
				return $data['_this']->Twitter->tweetButton(
					array(
						'url' => Router::url(current($link['slugUrl']), true),
						'text' => $data['content']['CmsContent']['title']
					)
				);
			}
		}

		/**
		 * Called before blog post is echo'ed
		 *
		 * @deprecated
		 */
		public function onBlogBeforeContentRender(Event $Event, $data) {
			$config = $this->__getConfig();
			if(isset($config['onBlogBeforeContentRender']) && in_array('tweet', $config['onBlogBeforeContentRender'])) {
				$link = $data['_this']->Event->trigger('Blog.slugUrl', array('type' => 'posts', 'data' => $data['post']));
				return $data['_this']->Twitter->tweetButton(
					array(
						'url' => Router::url(current($link['slugUrl']), true),
						'text' => $data['post']['BlogPost']['title']
					)
				);
			}
		}

		/**
		 * Called after blog post is echo'ed
		 *
		 * @deprecated
		 */
		public function onBlogAfterContentRender(Event $Event, $data) {
			$config = $this->__getConfig();
			if(isset($config['onBlogAfterContentRender']) && in_array('tweet', $config['onBlogAfterContentRender'])) {
				$link = $data['_this']->Event->trigger('Blog.slugUrl', array('type' => 'posts', 'data' => $data['post']));
				return $data['_this']->Twitter->tweetButton(
					array(
						'url' => Router::url(current($link['slugUrl']), true),
						'text' => $data['post']['BlogPost']['title']
					)
				);
			}
		}

		/**
		 * Called before content is rendered
		 *
		 * @deprecated
		 */
		public function onBeforeContentRender(Event $Event, $data) {
			$config = Configure::read(sprintf('%s.beforeContentRender', $Event->Handler->plugin));
			if(is_array($config) && in_array('tweet', $config)) {
				$record = current(current($data));
				if(empty($record['url'])) {
					$record['url'] = $Event->Handler->trigger(
						sprintf('%s.slugUrl', $Event->Handler->plugin),
						array('type' => current(array_keys($data)), 'data' => current($data))
					);

					$record['url'] = InfinitasRouter::url(current($eventData['url']['slugUrl']));
				}

				$title = !empty($record['title']) ? $record['title'] : null;
				if((empty($title) && !empty($record['name']))) {
					$title = $record['name'];
				}
				if(empty($title)) {
					$title = $record[ClassRegistry::init(implode('.', current($Event->Handler->request->params['models'])))->displayField];
				}

				$short = $Event->Handler->trigger('ShortUrls.getShortUrl', array('url' => $record['url']));
				return $Event->Handler->_View->Twitter->tweetButton(
					array(
						'url' => InfinitasRouter::url(current($short['getShortUrl'])),
						'text' => $title
					)
				);
			}
		}

		public function onUserProfile(Event $Event) {
			return array(
				'element' => 'profile'
			);
		}
	}
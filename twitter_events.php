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
	 }
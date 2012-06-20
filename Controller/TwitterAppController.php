<?php
	/* 
	 * AppController for twitter
	 * 
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * 
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package twitter
	 * @subpackage twitter.app_controller
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.1
	 * 
	 * @author dogmatic69
	 * 
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	 class TwitterAppController extends AppController {
		 public function beforeFilter() {
			 parent::beforeFilter();

			 Configure::load('twitter.config');
			return true;
		 }
	 }
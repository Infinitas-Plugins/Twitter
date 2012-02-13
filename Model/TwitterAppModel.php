<?php
	/**
	 * The twitter app model
	 * 
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * 
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package twitter
	 * @subpackage twitter.app_model
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.1
	 * 
	 * @author dogmatic69
	 * 
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	 class TwitterAppModel extends Model {
		/**
		 * database configuration to use
		 *
		 * @var string
		 */
		public $useDbConfig = 'twitter';

		/**
		 * Behaviors to attach
		 *
		 * @var mixed
		 */
		public $actsAs = false;

		/**
		 * database table to use
		 *
		 * @var string
		 */
		public $useTable = false;
	 }
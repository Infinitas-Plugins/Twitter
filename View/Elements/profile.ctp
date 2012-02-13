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
?>
<h3>Twitter</h3>
<?php
	if($this->Session->read('Twitter') && $user['User']['twitter_id']){
		echo sprintf('<p>%s</p>', __('Your are currently logged-in with your Twitter account, If you would like to log out click the button below'));
		echo $this->Twitter->logout();
	}

	else if(!$this->Session->read('Twitter')&& $user['User']['twitter_id']){
		echo sprintf('<p>%s</p>', __('Your Twitter account is currently linked, but you are not logged-in to Twitter, click below to login now.'));
		echo $this->Twitter->login();
	}
	
	else if($user['User']['twitter_id'] == 0){
		echo sprintf('<p>%s</p>', __('Your Twitter account is not currently linked, you can do so by clicking the button below.'));
		echo $this->Twitter->login();
	}
?>
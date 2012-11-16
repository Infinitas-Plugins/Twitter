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

	class Connect extends TwitterAppModel {
		public $schema = array(
			'title' => array(
				'type' => 'string',
				'null' => true,
				'key' => 'primary',
				'length' => 255
			),
			'link' => array(
				'type' => 'string',
				'null' => true,
				'key' => 'primary',
				'length' => 255
			),
			'description' => array(
				'type' => 'text',
				'null' => true,
				'key' => 'primary',
				'length' => null
			),
			'publisher' => array(
				'type' => 'string',
				'null' => true,
				'key' => 'primary',
				'length' => 255
			),
			'creator' => array(
				'type' => 'string',
				'null' => true,
				'key' => 'primary',
				'length' => 255
			),
			'date' => array(
				'type' => 'string',
				'null' => true,
				'key' => 'primary',
				'length' => 255
			)
		);

		public $request = array(
			'uri' => array(
				'host' => 'api.twitter.com',
				'path' => '',
			),
			'method' => 'GET',
			'auth' => array(
				'method' => 'OAuth',
				'oauth_callback' => true,
				'oauth_consumer_key' => true,
				'oauth_consumer_secret' => true,
				'oauth_token' => null,
				'oauth_verifier' => null
			)
		);

		/**
		 * twitter oauth url
		 *
		 * @var string
		 */
		public $authorizeUrl = 'http://api.twitter.com/oauth/authorize?oauth_token=%s';


		public $map = array(
			'count'  => 'Rss.Channel.totalResults',
			'limit'  => 'Rss.Channel.itemsPerPage',
			'page'   => 'Rss.Channel.startIndex',
		);

		public $requestTypes = array(
			'request' => '/oauth/request_token',
			'access' => '/oauth/access_token'
		);

		public function __construct($id = false, $table = null, $ds = null) {
			parent::__construct($id, $table, $ds);

			$config = Configure::read('Twitter');

			if(isset($this->request['auth']['oauth_callback'])) {
				$this->request['auth']['oauth_callback'] = InfinitasRouter::url($config['callback_url']);
			}

			$this->request['auth']['oauth_consumer_key'] = $config['consumer_key'];
			$this->request['auth']['oauth_consumer_secret'] = $config['consumer_secret'];
		}

		/**
		 * set the query up as per the find conditions.
		 */
		public function beforeFind($queryData) {
			$this->request['uri']['path'] = $this->requestTypes['request'];
			if(isset($this->requestTypes[$queryData['conditions']['Connect.type']])) {
				$this->request['uri']['path'] = $this->requestTypes[$queryData['conditions']['Connect.type']];
			}

			if(isset($queryData['conditions']['Connect.oauth_token']) && isset($queryData['conditions']['Connect.oauth_verifier'])) {
				$this->request['auth']['oauth_token'] = $queryData['conditions']['Connect.oauth_token'];
				$this->request['auth']['oauth_verifier'] = $queryData['conditions']['Connect.oauth_verifier'];
			}

			$queryData['conditions'] = 'raw';
			return $queryData;
		}

		/**
		 * does not want to work ffs
		 */
		public function afterFind($results, $primary = false) {
			return $results;
			if(is_string($results[0]['Connect'])) {
				return $this->formatQueryString($results[0]['Connect']);
			}
		}

		/**
		 * does not want to work in afterSave()
		 */
		public function formatQueryString($results) {
			$results = current($results);
			parse_str($results, $results);
			$return[$this->name] = $results;
			return $return;
		}
	}
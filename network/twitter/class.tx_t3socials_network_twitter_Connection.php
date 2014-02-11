<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012 Rene Nitzsche (rene@system25.de)
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

require_once 'twitteroauth/twitteroauth.php';
require_once(t3lib_extMgm::extPath('rn_base') . 'class.tx_rnbase.php');

tx_rnbase::load('tx_t3socials_network_IConnection');
tx_rnbase::load('tx_rnbase_util_Logger');


/**
 *
 */
class tx_t3socials_network_twitter_Connection implements tx_t3socials_network_IConnection {
	public function __construct() {
	}

	public function setNetwork(tx_t3socials_models_Network $network) {
		$this->network = $network;
	}
	/**
	 * Returns the network account
	 *
	 * @return tx_t3socials_models_Network
	 */
	public function getNetwork() {
		return $this->network;
	}

	public function sendMessage(tx_t3socials_models_Message $message) {
		// Diese generische Nachricht muss nun in eine Twittermeldung umgesetzt werden.
		// Das sollte ein MessageBuilder übernehmen. Der muss aber austauschbar sein, damit für
		// spezielle Nachrichten andere Builder konfiguriert werden können.
		$builder = $this->getBuilder($message->getMessageType());
		try {
			$twitterMessage = $builder->build($message, $this->getNetwork(), 'twitter.'.$message->getMessageType().'.');
			if($twitterMessage)
				$this->sendTweet($twitterMessage);
			else
				tx_rnbase_util_Logger::warn('Tweet is empty!', 't3socials', array('message' => (array)$message, 'Builder Class' => get_class($builder)));
		}
		catch(Exception $e) {
			// Die Message anpassen
			$data = $message->getData();
			if(is_object($data) && isset($data->record))
				$message->setData($data->record);
			tx_rnbase_util_Logger::fatal('Error sending Tweet ('.$message->getMessageType().')!', 't3socials', array('Tweet'=>$twitterMessage, message => (array)$message, 'Builder Class' => get_class($builder), 'Exception'=> $e->getMessage()));
			$message->setData($data);
		}
	}
	protected function getBuilder($messageType) {
		 $network = $this->getNetwork();
		 $builderClass = $network->getConfigData('twitter.'.$messageType.'.builder');
		 $builderClass = $builderClass ? $builderClass : 'tx_t3socials_network_twitter_MessageBuilder';
		 return tx_rnbase::makeInstance($builderClass);
	}

	/**
	 * Prüft das Result nach Fehlern.
	 *
	 * @param stdClass $result
	 * @throws Exception
	 */
	protected function handleErrorsFromResult(stdClass $result) {
		$errors = $result->errors ? $result->errors : array();
		if (!empty($result->error)) {
			$errors[] = $result->error;
		}
		if(!empty($errors)) {
			$errMsg = array();
			foreach($errors As $error) {
				$errMsg[] = is_object($error)
					? $error->message . ' (Code ' .$error->code.')'
					: $error
				;
			}
			throw new Exception(implode("\n", $errMsg));
		}
	}

	/**
	 * Post data on Twitter using Curl.
	 *
	 * @param	string		$twitter_data: Data to post on twitter.
	 * @return	void
	 */
	public function sendTweet($message) {
		$connection = $this->getConnection();
		$result = $connection->post('statuses/update', array('status' => $message));

		$this->handleErrorsFromResult($result);

		tx_rnbase_util_Logger::info('Tweet was posted to Twitter!', 't3socials',
				array('Tweet' => $message, 'Account'=> $this->getNetwork()->getName()));
		return $result;
	}

	public function getHomeTimeline() {
		$connection = $this->getConnection();
		$result = $connection->get('statuses/home_timeline');
		if($result->error)
			throw new Exception($result->error);
		return $result;
	}


	public function verify() {
		// TODO!
		return true;
	}
	public function verifyConnection() {
		$connection = $this->getConnection();
		return $connection->get('account/verify_credentials');
	}

	public static function sendTweetSimple($message, $consumerKey, $consumerSecret, $oauthToken, $oauthSecret) {
		$connection = new TwitterOAuth($consumerKey, $consumerSecret, $oauthToken, $oauthSecret);
		return $connection->post('statuses/update', array('status' => $message));
	}

	private function getConnection() {
		if(!is_object($this->connection)) {
			$cred = $this->getCredentials($this->network);
			$this->connection = new TwitterOAuth($cred['CONSUMER_KEY'], $cred['CONSUMER_SECRET'], $cred['OAUTH_TOKEN'], $cred['OAUTH_SECRET']);
		}
		return $this->connection;
	}

	private function getCredentials(tx_t3socials_models_Network $network) {
		$data = $network->getConfigData('twitter.');
		if(empty($data))
			throw new Exception('No credentials for twitter found! UID: ' . $network->getUid());
		return $data;
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/t3socials/network/twitter/class.tx_t3socials_network_twitter_Connection.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/t3socials/network/twitter/class.tx_t3socials_network_twitter_Connection.php']);
}

?>
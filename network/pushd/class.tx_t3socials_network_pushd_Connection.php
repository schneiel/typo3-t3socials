<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Rene Nitzsche (rene@system25.de)
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

require_once(t3lib_extMgm::extPath('rn_base') . 'class.tx_rnbase.php');

tx_rnbase::load('tx_t3socials_network_IConnection');
tx_rnbase::load('tx_rnbase_util_Logger');


/**
 *
 */
class tx_t3socials_network_pushd_Connection implements tx_t3socials_network_IConnection {
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

		$builder = $this->getBuilder($message->getMessageType());
		$data = $builder->build($message, $this->getNetwork(), 'pushd.'.$message->getMessageType().'.');
		if(!$data) // Der Builder entscheidet, ob etwas verschickt wird.
			return;

		// Der Builder kann einen anderen EventType festlegen
		$event = $message->getMessageType();
		if(isset($data['event']) && $data['event']) {
			$event = $data['event'];
			unset($data['event']);
		}
		// Beim Push beachten wir nur Titel und Message
		$url = $this->getNetwork()->getConfigData('pushd.url');
		$url .= 'event/'.$event;

		// use key 'http' even if you send the request to https://...
		$options = array(
				'http' => array(
						'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
						'method'  => 'POST',
						'content' => http_build_query($data),
				),
		);
		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		if($result === false) {
			tx_rnbase_util_Logger::fatal('Error sending pushd-message!', 't3socials', array('message' => (array)$message, 'options'=>$options, 'url'=>$url));
			throw new Exception('Message could not be send!');
		}
	}

	protected function getBuilder($messageType) {
		$network = $this->getNetwork();
		$builderClass = $network->getConfigData('pushd.'.$messageType.'.builder');
		$builderClass = $builderClass ? $builderClass : 'tx_t3socials_network_pushd_MessageBuilder';
		return tx_rnbase::makeInstance($builderClass);
	}

	public function verify() {
		return true;
	}

	/**
	 * Liefert statistische Infos zu einem Event
	 * Leider nur die Anzahl der Nachrichten..
	 * @param string $event
	 */
	public function getEventStatus($event) {
		$url = $this->getNetwork()->getConfigData('pushd.url');
		$url .= 'event/'.$event;
		$result = file_get_contents($url);
		if($result === false) {
			return true;
		}
	}

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/t3socials/network/twitter/class.tx_t3socials_network_twitter_Connection.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/t3socials/network/twitter/class.tx_t3socials_network_twitter_Connection.php']);
}

?>
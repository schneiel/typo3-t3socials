<?php
/***************************************************************
*  Copyright notice
*
 * (c) 2014 DMK E-BUSINESS GmbH <kontakt@dmk-ebusiness.de>
 * All rights reserved
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
require_once t3lib_extMgm::extPath('rn_base', 'class.tx_rnbase.php');
tx_rnbase::load('tx_t3socials_models_IMessage');

/**
 * A generic message class
 *
 * @package tx_t3socials
 * @subpackage tx_t3socials_models
 * @author Rene Nitzsche <rene@system25.de>
 * @author Michael Wagner <michael.wagner@dmk-ebusiness.de>
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class tx_t3socials_models_Message
	extends tx_t3socials_models_Base
		implements tx_t3socials_models_IMessage {

	/**
	 *
	 * @param array|string $messageType
	 *
	 * @return tx_t3socials_models_Message
	 */
	public static function getInstance($messageType = 'manually') {
		return tx_rnbase::makeInstance('tx_t3socials_models_Message', $messageType);
	}

	/**
	 * @param string|array $rowOrUid message type or array with message data
	 * 		array can contain (message_type, headline, intro, message, url, data)
	 */
	function init($rowOrUid) {
		if (is_array($rowOrUid)) {
			$this->uid = $rowOrUid['message_type'];
			$this->record = $rowOrUid;
		}
		elseif (is_string($rowOrUid)) {
			$this->setMessageType($rowOrUid);
		}

		$messageType = $this->getMessageType();
		if (empty($messageType)) {
			throw new Exception('tx_t3socials_models_Message requires an message type.');
		}
	}


	/**
	 * @return string
	 */
	public function getMessageType() {
		return $this->getProperty('message_type');
	}
	/**
	 * @param string $value
	 */
	public function setMessageType($value) {
		return $this->setProperty('message_type', $value);
	}

	/**
	 * @return string
	 */
	public function getHeadline() {
		return $this->getProperty('headline');
	}
	/**
	 * @param string $value
	 */
	public function setHeadline($value) {
		return $this->setProperty('headline', $value);
	}


	/**
	 * @return string
	 */
	public function getIntro() {
		return $this->getProperty('intro');
	}

	/**
	 * @param string $value
	 */
	public function setIntro($value) {
		return $this->setProperty('intro', $value);
	}


	/**
	 * @return string
	 */
	public function getMessage() {
		return $this->getProperty('message');
	}

	/**
	 * @param string $value
	 */
	public function setMessage($value) {
		return $this->setProperty('message', $value);
	}


	/**
	 * @return string
	 */
	public function getUrl() {
		return $this->getProperty('url');
	}

	/**
	 * @param string $value
	 */
	public function setUrl($value) {
		return $this->setProperty('url', $value);
	}


	/**
	 * @return mixed
	 */
	public function getData() {
		return $this->getProperty('data');
	}

	/**
	 * @param string $value
	 */
	public function setData($value) {
		return $this->setProperty('data', $value);
	}

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/t3socials/models/class.tx_t3socials_models_Message.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/t3socials/models/class.tx_t3socials_models_Message.php']);
}

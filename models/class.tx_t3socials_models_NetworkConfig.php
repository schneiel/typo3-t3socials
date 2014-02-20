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
tx_rnbase::load('tx_t3socials_models_Base');

/**
 * Model einer netzwerk Konfiguration
 *
 * @package tx_t3socials
 * @subpackage tx_t3socials_models
 * @author Michael Wagner <michael.wagner@dmk-ebusiness.de>
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class tx_t3socials_models_NetworkConfig
	extends tx_t3socials_models_Base {

	/**
	 * Inits the model instance either with uid or a complete data record.
	 * As the result the instance should be completly loaded.
	 *
	 * @param mixed $rowOrUid
	 */
	function init($rowOrUid) {
		if(is_array($rowOrUid)) {
			$this->uid = isset($rowOrUid['uid']) ? $rowOrUid['uid'] : $rowOrUid['provider_id'];
			$this->record = $rowOrUid;
		}
		else {
			$this->uid = $rowOrUid;
			$this->record = array();
		}
	}

	/**
	 * @return string
	 */
	public function getProviderId() {
		return $this->uid;
	}
	/**
	 * @return string
	 */
	public function getProviderTitle() {
		return tx_t3socials_network_Config::translateNetwork($this->getProviderId());
	}
	/**
	 * @return string
	 */
	public function getHybridAuthProviderName() {
		return $this->getProperty('hybridauth_provider');
	}

	/**
	 * @return string
	 */
	public function getDescription() {
		return $this->getProperty('description');
	}
	/**
	 * @return string
	 */
	public function getDefaultConfiguration() {
		return $this->getProperty('default_configuration');
	}
	/**
	 * @return string
	 */
	public function getConnectorClass() {
		return $this->getProperty('connector');
	}
	/**
	 * @return string
	 */
	public function getComunicatorClass() {
		return $this->getProperty('comunicator');
	}

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/t3socials/models/class.tx_t3socials_models_NetworkConfig.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/t3socials/models/class.tx_t3socials_models_NetworkConfig.php']);
}

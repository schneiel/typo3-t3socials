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

require_once(t3lib_extMgm::extPath('rn_base') . 'class.tx_rnbase.php');

tx_rnbase::load('tx_rnbase_model_base');


/**
 */
class tx_t3socials_models_Network extends tx_rnbase_model_base {
	public function __construct($rowOrUid) {
		parent::__construct($rowOrUid);
		$this->initConfig();
	}
	/**
	 * Extract data from config
	 *
	 */
	protected function initConfig() {
		$ts = $this->record['config'];
		// This handles ts setup from flexform
		$tsParser = t3lib_div::makeInstance('t3lib_TSparser');
//		$tsParser->setup = $this->_dataStore->getArrayCopy();
		$tsParser->parse($ts);
		$configArr = $tsParser->setup;
		tx_rnbase::load('tx_rnbase_configurations');
		$this->config = new tx_rnbase_configurations();
		$this->config->init($configArr, false, '', '');

	}
	/**
	 * Returns the network identifier
	 * @return string
	 */
	public function getNetwork() {
		return $this->record['network'];
	}
	/**
	 * Returns the network label
	 * @return string
	 */
	public function getName() {
		return $this->record['name'];
	}
	/**
   * Returns configured data
   * @param string $confId
   */
	public function getConfigData($confId) {
		return $this->config->get($confId);
	}
	/**
	 * Returns the configuration for this account
	 *
	 * @return tx_rnbase_configurations
	 */
	public function getConfigurations() {
		return $this->config;
	}
  function getTableName(){return 'tx_t3socials_networks';}

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/t3socials/models/class.tx_t3socials_models_Network.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/t3socials/models/class.tx_t3socials_models_Network.php']);
}

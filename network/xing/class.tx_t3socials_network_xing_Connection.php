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
tx_rnbase::load('tx_t3socials_network_hybridauth_Connection');


/**
 *
 * @package tx_t3socials
 * @subpackage tx_t3socials_network
 * @author Michael Wagner <michael.wagner@dmk-ebusiness.de>
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class tx_t3socials_network_xing_Connection
	extends tx_t3socials_network_hybridauth_Connection {

	/**
	 * Liefert den Klassennamen der Message Builder Klasse
	 * @return string
	 */
	protected function getBuilderClass() {
		return 'tx_t3socials_network_xing_MessageBuilder';
	}

	/**
	 * @return string
	 */
	protected function getHybridAuthProviderId() {
		return 'XING';
	}

	/**
	 * @param array $config
	 *
	 * @return tx_t3socials_models_NetworkConfig
	 */
	public function getNetworkConfig(array $config = array()) {
		$config['provider_id'] = strtolower($this->getHybridAuthProviderId());
		$config['hybridauth_provider'] = $this->getHybridAuthProviderId();
		$config['connector'] = 'tx_t3socials_network_xing_Connection';
		$config['comunicator'] = 'tx_t3socials_mod_handler_Xing';
		$config['description']
			= 'Please enter the customer key into the field "Username"'
			. ' and the customer secret into the field "Password".' . PHP_EOL
			. '###MORE###' . PHP_EOL
			. ' To authenticate with a specific account, you has to '
			. ' put the customer token in the fields "access_token" and'
			. ' "access_token_secret" of the Configuration.' . PHP_EOL
			. ' You can go to the T3Socials User Tools to autehtificate.' . PHP_EOL
			. ' a customer end get the tokens from there.' . PHP_EOL;
		$config['default_configuration']
			= 'xing {'. PHP_EOL
			. '	access_token =' . PHP_EOL
			. '	access_token_secret =' . PHP_EOL
			. '}' ;
		return parent::getNetworkConfig($config);
	}

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/t3socials/network/xing/class.tx_t3socials_network_xing_Connection.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/t3socials/network/xing/class.tx_t3socials_network_xing_Connection.php']);
}

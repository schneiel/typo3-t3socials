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
tx_rnbase::load('tx_rnbase_util_Misc');

/**
 * Access a service instance
 *
 * @package tx_t3socials
 * @subpackage tx_t3socials_network
 * @author Rene Nitzsche <rene@system25.de>
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class tx_t3socials_srv_ServiceRegistry {

	/**
	 * Liefert den Network-Service
	 * @return tx_t3socials_srv_Network
	 */
	public static function getNetworkService() {
		return tx_rnbase_util_Misc::getService('t3socials', 'network');
	}
	/**
	 * Liefert den News-Service. Beispielservice für Verwendung der API
	 * @return tx_t3socials_srv_News
	 */
	public static function getNewsService() {
		return tx_rnbase_util_Misc::getService('t3socials', 'news');
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/t3socials/srv/class.tx_t3socials_srv_ServiceRegistry.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/t3socials/srv/class.tx_t3socials_srv_ServiceRegistry.php']);
}

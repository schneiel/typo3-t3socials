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

/**
 * Basis handler für HybridAuth
 *
 * @package tx_t3socials
 * @subpackage tx_t3socials_mod
 * @author Michael Wagner <michael.wagner@dmk-ebusiness.de>
 * @license http://www.gnu.org/licenses/lgpl.html
 *          GNU Lesser General Public License, version 3 or later
 */
class tx_t3socials_mod_util_Message {


	/**
	 * Erzeugt eine Flash Message
	 *
	 * @param string|array $message
	 * @return void
	 */
	public static function showMessage($message) {
		$msg = '';
		$title = '';
		$severity = t3lib_FlashMessage::OK;
		$store = FALSE;
		// wir haben eine erweiterte konfiguration
		if (is_array($message)) {
			$msg = $message['message'];
			$title = $message['title'];
			$severity = $message['severity'];
			$store = boolean($message['storeinsession']);
		}
		// wir haben nur eine meldung
		else {
			$msg = $message;
			$title = 'Message';
		}
		$this->getModule()->addMessage($msg, $title, $severity, $store);
	}

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/t3socials/mod/util/class.tx_t3socials_mod_util_Template.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/t3socials/mod/util/class.tx_t3socials_mod_util_Template.php']);
}
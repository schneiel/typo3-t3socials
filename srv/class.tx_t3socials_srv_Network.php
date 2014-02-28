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
tx_rnbase::load('tx_rnbase_sv1_Base');
tx_rnbase::load('tx_rnbase_util_DB');


/**
 * Service for accessing network account information
 *
 * @package tx_t3socials
 * @subpackage tx_t3socials_network
 * @author Rene Nitzsche <rene@system25.de>
 * @license http://www.gnu.org/licenses/lgpl.html
 *          GNU Lesser General Public License, version 3 or later
 */
class tx_t3socials_srv_Network
	extends tx_rnbase_sv1_Base {


	/**
	 * Return name of search class
	 *
	 * @return string
	 */
	public function getSearchClass() {
		return 'tx_t3socials_search_Network';
	}

	/**
	 * Send a message to all accounts assigned to given trigger
	 *
	 * @param tx_t3socials_models_IMessage $message
	 * @param string $trigger single trigger value
	 * @param array $options
	 * @return void
	 */
	public function sendMessage(tx_t3socials_models_IMessage $message, $trigger, array $options = array()) {
		$accounts = $this->findAccounts($trigger);
		if (empty($accounts)) {
			return;
		}

		foreach ($accounts As $account) {
			// Für den Account die Connectionklasse laden
			/* @var tx_t3socials_network_IConnection $connection */
			$connection = $this->getConnection($account);
			$connection->setNetwork($account);
			// wir haben einen url builder
			if (isset($options['urlbuilder'])) {
				// Eine Möglichkeit die URL extern zu setzen
				$message->setUrl(call_user_func($options['urlbuilder'], $message, $account));
			}
			// @TODO: liveticker.message??? wir haben doch news oder eine andere generische message!?
			else {
				$message->setUrl($account->getConfigData($account->getNetwork() . '.liveticker.message.url'));
			}
			try {
				$connection->sendMessage($message);
			} catch (Exception $e) {
				tx_rnbase_util_Logger::fatal(
					'Error sending message! (' . $trigger . ')', 't3socials',
					array(
						'exception' => (string) $e,
						'account' => (string) $account->getName(),
						'message' => (string) $message,
						'network' => (string) $account->getNetwork(),
					)
				);
			}
		}

	}
	/**
	 * Get the connection instance for this account
	 *
	 * @param tx_t3socials_models_Network $account
	 * @deprecated tx_t3socials_network_Config::getNetworkConnection($account)
	 * @return tx_t3socials_network_IConnection
	 */
	public function getConnection($account) {
		return tx_t3socials_network_Config::getNetworkConnection($account);
	}

	/**
	 * liefert alle Netzwerke für einen trigger.
	 *
	 * @param string $action
	 * @return array
	 */
	public function findAccounts($action) {
		// FIXME: OP_INSET
		$fields['NETWORK.ACTIONS'][OP_LIKE] = $action;
		$options = array();
		return $this->search($fields, $options);
	}

	/**
	 * Liefert alle Accounts eines Typs
	 *
	 * @param string $types
	 * @return array
	 */
	public function findAccountsByType($types) {
		// FIXME: OP_INSET
		$fields['NETWORK.NETWORK'][OP_LIKE] = $types;
		$options = array();
		return $this->search($fields, $options);
	}

	/**
	 * Search database for networks
	 *
	 * @param array $fields
	 * @param array $options
	 * @return array of tx_t3socials_models_Network
	 */
	public function search($fields, $options) {
		tx_rnbase::load('tx_rnbase_util_SearchBase');
		$searcher = tx_rnbase_util_SearchBase::getInstance('tx_t3socials_search_Network');
		return $searcher->search($fields, $options);
	}

	/**
	 * Check if a record was send to networks before
	 *
	 * @param int $uid
	 * @param string $tablename
	 * @TODO: auf count umstellen!?
	 * @return boolean
	 */
	public function hasSent($uid, $tablename) {
		$options['enablefieldsoff'] = 1;
		$options['where']
			= 'recid=' . intval($uid) . ' AND tablename=\'' .
				$GLOBALS['TYPO3_DB']->quoteStr($tablename, 'tx_t3socials_autosends') . '\'';
		$rows = tx_rnbase_util_DB::doSelect('*', 'tx_t3socials_autosends', $options);
		return !empty($rows);
	}

	/**
	 * Markiert einen Datensatz als versendet.
	 *
	 * @param int $uid
	 * @param string $tablename
	 * @return int  UID of created record
	 */
	public function setSent($uid, $tablename) {
		if ($this->hasSent($uid, $tablename)) {
			return 0;
		}
		$values = array(
			'recid' => $uid,
			'tablename' => $tablename,
		);
		return tx_rnbase_util_DB::doInsert('tx_t3socials_autosends', $values);
	}
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/t3socials/srv/class.tx_t3socials_srv_Network.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/t3socials/srv/class.tx_t3socials_srv_Network.php']);
}


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
require_once(PATH_t3lib.'class.t3lib_svbase.php');
tx_rnbase::load('tx_rnbase_util_DB');


/**
 * Service for accessing network account information
 *
 * @author Rene Nitzsche
 */
class tx_t3socials_srv_Network extends t3lib_svbase {

	/**
	 * Get the connection instance for this account
	 *
	 * @param tx_t3socials_models_Network $account
	 */
	public function getConnection($account) {
		$connectionClass = $account->getConfigData($account->getNetwork().'.connection');
		if($connectionClass)
			return tx_rnbase::makeInstance($connectionClass);
		// Load defaults
		switch ($account->getNetwork()) {
			case 'twitter':
				return tx_rnbase::makeInstance('tx_t3socials_network_twitter_Connection');
			break;
			case 'pushd':
				return tx_rnbase::makeInstance('tx_t3socials_network_pushd_Connection');
			break;

			default:
				throw new Exception('Unknown network type: ' . $account->getNetwork());
			break;
		}
	}
	public function findAccounts($action) {
		$fields['NETWORK.ACTIONS'][OP_LIKE] = $action; // FIXME: OP_INSET
		$options = array();
		return $this->search($fields, $options);
	}

	public function findAccountsByType($types) {
		$fields['NETWORK.NETWORK'][OP_LIKE] = $types; // FIXME: OP_INSET
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
	 * @param string $tablname
	 * @return boolean
	 */
	public function hasSent($uid, $tablename) {
		$options['enablefieldsoff'] = 1;
		$options['where'] = 'recid='.intval($uid) . ' AND tablename=\'' . $GLOBALS['TYPO3_DB']->quoteStr($tablename,'tx_t3socials_autosends') . '\'';
		$rows = tx_rnbase_util_DB::doSelect('*', 'tx_t3socials_autosends', $options);
		return !empty($rows);
	}
	public function setSent($uid, $tablename) {
		if($this->hasSent($uid, $tablename)) return;
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


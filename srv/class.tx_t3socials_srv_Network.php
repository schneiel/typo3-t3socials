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
 * @author Michael Wagner <michael.wagner@dmk-ebusiness.de>
 * @license http://www.gnu.org/licenses/lgpl.html
 *          GNU Lesser General Public License, version 3 or later
 */
class tx_t3socials_srv_Network
	extends tx_rnbase_sv1_Base {

	const TABLE_AUTOSEND = 'tx_t3socials_autosends';

	/**
	 * Return name of search class
	 *
	 * @return string
	 */
	public function getSearchClass() {
		return 'tx_t3socials_search_Network';
	}

	/**
	 * Sendet automatisch einen Datensatz an die Netzwerke.
	 *
	 * @param string $table
	 * @param int $uid
	 * @return array[tx_t3socials_models_State]
	 *         Enthält eine statusmeldung für jedes netzwerk
	 */
	public function exeuteAutoSend($table, $uid) {
		if ($this->hasSent($uid, $table)) {
			return array();
		}

		$states = array();

		$hasSend = FALSE;

		// alle trigger zur tabelle holen
		$triggers = tx_t3socials_trigger_Config::getTriggerConfigsForTable($table);

		/* @var tx_t3socials_models_TriggerConfig $trigger */
		foreach ($triggers as $trigger) {
			// the resolver creates the record!
			$resolver = tx_t3socials_trigger_Config::getResolver($trigger);
			$record = $resolver->getRecord($table, $uid);

			// wenn gelöscht oder versteckt, nicht publizieren!
			if ($record->isDeleted() || $record->isHidden()) {
				continue;
			}

			// the builder generates the generic message
			$builder = tx_t3socials_trigger_Config::getMessageBuilder($trigger);
			$message = $builder->buildGenericMessage($record);

			$accounts = $this->findAccountsByTriggers($trigger->getTrigerId(), TRUE);
			/* @var tx_t3socials_models_Network $network */
			foreach ($accounts as $network) {
				/* @var $state tx_t3socials_models_State */
				$state = tx_rnbase::makeInstance(
					'tx_t3socials_models_State', 0
				);
				$state->setTriggerConfig($trigger);
				$state->setMessageModel($message);
				$state->setNetworkModel($network);

				// spezielle netzwerk abhängige dinge durchführen.
				$builder->prepareMessageForNetwork(
					$message, $network, $trigger
				);
				// verbindung aufbauen
				$connection = tx_t3socials_network_Config::getNetworkConnection($network);

				$msgId = '[' . $table . ':' . $uid . '->' . $trigger->getTrigerId() . '.' . $network->getNetwork() . ']';
				// nachricht senden
				try {
					$error = $connection->sendMessage($message);
					if ($error) {
						tx_rnbase_util_Logger::warn(
							'Error sending message! ' . $msgId,
							array(
								'error' => (string) $error,
								'account' => (string) $network->getName(),
								'message' => (string) $message,
								'network' => (string) $network->getNetwork(),
								'trigger' => (string) $trigger->getTrigerId(),
							)
						);
						$state->setState(
							tx_t3socials_models_State::STATE_WARNING,
							'Error sending message! ' . $msgId .
							'. See DevLog for more Informations.'
						);
					}
					// versand erfolgreich
					else {
						$hasSend = TRUE;
						$state->setState(
							tx_t3socials_models_State::STATE_OK,
							'Message successfully send to network! ' . $msgId
						);
					}
				} catch (Exception $e) {
					tx_rnbase_util_Logger::fatal(
						'Error sending message! ' . $msgId,
						't3socials',
						array(
							'exception' => (string) $e,
							'account' => (string) $network->getName(),
							'message' => (string) $message,
							'network' => (string) $network->getNetwork(),
							'trigger' => (string) $trigger->getTrigerId(),
						)
					);
					$state->setState(
						tx_t3socials_models_State::STATE_ERROR,
							'Error sending message! ' . $msgId .
							'. See DevLog for more Informations.'
					);
				}
				$states[] = $state;
			}
		}

		// als versendet markieren
		if ($hasSend) {
			$this->setSent($uid, $table);
		}

		return $states;
	}

	/**
	 * Liefert alle Netzwerke für einen trigger.
	 * Eine Netzwerkkonfiguration kann mehrere Trigger haben!
	 *
	 * @param string|array $triggers
	 * @return array
	 */
	public function findAccounts($triggers) {
		$triggers = is_array($triggers) ? implode(',', $triggers) : $triggers;
		$fields = $options = array();
		// @TODO: das funktioniert nur durch einen Bug in rn_base.
		// Bei OP_INSET_INT wird aktuell keine Typumwandlung zum Integer gemacht.
		$fields['NETWORK.actions'][OP_INSET_INT] = $triggers;
		return $this->search($fields, $options);
	}
	/**
	 * Liefert alle Netzwerke für einen trigger, welche das Autosend-Flag haben.
	 *
	 * @param string|array $triggers
	 * @param null|bool $autosave
	 * @return array
	 */
	public function findAccountsByTriggers($triggers, $autosave = NULL) {
		$triggers = is_array($triggers) ? implode(',', $triggers) : $triggers;
		$fields = $options = array();
		// @TODO: das funktioniert nur durch einen Bug in rn_base.
		// Bei OP_INSET_INT wird aktuell keine Typumwandlung zum Integer gemacht.
		$fields['NETWORK.actions'][OP_INSET_INT] = $triggers;
		if ($autosave !== NULL) {
			$fields['NETWORK.autosend'][OP_EQ_INT] = $autosave ? 1 : 0;
		}
		return $this->search($fields, $options);
	}

	/**
	 * Liefert alle Accounts eines Typs
	 *
	 * @param string $types
	 * @return array
	 */
	public function findAccountsByType($types) {
		$fields = $options = array();
		// FIXME: OP_INSET
		$fields['NETWORK.network'][OP_LIKE] = $types;
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
	 * @param string $table
	 * @return boolean
	 */
	public function hasSent($uid, $table) {
		// enablefields gibts nicht für die tabelle
		$options['enablefieldsoff'] = 1;
		$table = $GLOBALS['TYPO3_DB']->fullQuoteStr($table, 'tx_t3socials_autosends');
		$options['where'] = 'recid = ' . (int) $uid . ' AND tablename = ' . $table;
		$result = tx_rnbase_util_DB::doSelect('count(uid) as cnt', self::TABLE_AUTOSEND, $options);
		return (int) $result[0]['cnt'] > 0;
	}

	/**
	 * Markiert einen Datensatz als versendet.
	 *
	 * @param int $uid
	 * @param string $table
	 * @return int  UID of created record
	 */
	public function setSent($uid, $table) {
		if ($this->hasSent($uid, $table)) {
			return 0;
		}
		$values = array(
			'recid' => $uid,
			'tablename' => $table,
		);
		return tx_rnbase_util_DB::doInsert(self::TABLE_AUTOSEND, $values);
	}

}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/t3socials/srv/class.tx_t3socials_srv_Network.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/t3socials/srv/class.tx_t3socials_srv_Network.php']);
}


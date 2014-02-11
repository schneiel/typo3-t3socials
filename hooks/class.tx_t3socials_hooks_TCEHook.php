<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012-2013 Rene Nitzsche <rene@system25.de>
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


class tx_t3socials_hooks_TCEHook {

	/**
	 * Wir müssen dafür sorgen, daß die neuen IDs der Teams im Wettbewerb und Spielen
	 * verwendet werden.
	 */
//	public function processDatamap_preProcessFieldArray(&$incomingFieldArray, $table, $id, &$tcemain)  {
//	}

	/**
	 * Nachbearbeitungen, unmittelbar BEVOR die Daten gespeichert werden. Das POST bezieht sich
	 * auf die Arbeit der TCE und nicht auf die Speicherung in der DB.
	 *
	 * @param string $status new oder update
	 * @param string $table Name der Tabelle
	 * @param int $id UID des Datensatzes
	 * @param array $fieldArray Felder des Datensatzes, die sich ändern
	 * @param tce_main $tcemain
	 */
//	public function processDatamap_postProcessFieldArray($status, $table, $id, &$fieldArray, &$tce) {
//	}

	/**
	 * Nachbearbeitungen, unmittelbar NACHDEM die Daten gespeichert wurden.
	 *
	 * @param string $status new oder update
	 * @param string $table Name der Tabelle
	 * @param int $id UID des Datensatzes
	 * @param array $fieldArray Felder des Datensatzes, die sich ändern
	 * @param tce_main $tcemain
	 */
	public function processDatamap_afterDatabaseOperations($status, $table, $id, $fieldArray, &$tcemain) {
		if($table == 'tt_news' && ($status=='new' || $status=='update')) {
			if($status=='new')
				$id = $tcemain->substNEWwithIDs[$id];
			$srv = tx_t3socials_srv_ServiceRegistry::getNewsService();
			$news = $srv->makeInstance($id);
			if(!$news || !$news->isValid() || $news->getHidden() > 0) return;
			$srv->sendNews($news);
		}
	}

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/more4t3sports/hooks/class.tx_more4t3sports_hooks_TCEHook.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/more4t3sports/hooks/class.tx_more4t3sports_hooks_TCEHook.php']);
}


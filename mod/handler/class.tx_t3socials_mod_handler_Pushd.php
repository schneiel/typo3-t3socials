<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Rene Nitzsche (rene@system25.de)
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
tx_rnbase::load('tx_rnbase_mod_IModHandler');
tx_rnbase::load('tx_rnbase_util_DB');

class tx_t3socials_mod_handler_Pushd implements tx_rnbase_mod_IModHandler {

	private $data = array();
	private $warnings = array();

	public function getSubID() {
		return 'pushd';
	}
	public function getSubLabel() {
		return '###LABEL_TAB_PUSHD###';
	}

	/**
	 * @return tx_t3socials_mod_handler_Pushd
	 */
	public static function getInstance() {
		return tx_rnbase::makeInstance('tx_t3socials_mod_handler_Pushd');
	}
	/**
	 * Send titel and message
	 * @param tx_rnbase_mod_IModule $mod
	 */
	public function handleRequest(tx_rnbase_mod_IModule $mod) {
		$submitted = t3lib_div::_GP('sendpushd');
		if(!$submitted) return '';

		$this->data = t3lib_div::_GP('data');
		$msg = trim($this->data['msg']);
		$title = trim($this->data['title']);
		$SET = t3lib_div::_GP('SET');
		if(strlen($msg) == 0) {
			$info = 'Bitte einen Text eingeben.<br />';
			$mod->addMessage($info, 'Hinweis', 1);
			return;
		}
		$message = tx_rnbase::makeInstance('tx_t3socials_models_Message', $SET['event']);
		$message->setHeadline($title);
		$message->setMessage($msg);
		$account = tx_rnbase::makeInstance('tx_t3socials_models_Network', $SET['pushd']);

		try {
	  	$conn = tx_rnbase::makeInstance('tx_t3socials_network_pushd_Connection');
	  	$conn->setNetwork($account);
	  	$conn->sendMessage($message);
			$mod->addMessage('###LABEL_MESSAGE_SENT###', 'Hinweis', 0);
		}
		catch(Exception $e) {
			$mod->addMessage($e->getMessage(), 'Fehler', 2);
		}
	}


	public function showScreen($template, tx_rnbase_mod_IModule $mod, $options) {

		$formTool = $mod->getFormTool();
		$options = array();

		$markerArr = array();
		$subpartArr = array();
		$wrappedSubpartArr = array();
		// Auswahlbox mit den vorhandenen Accounts
		$accounts = tx_t3socials_srv_ServiceRegistry::getNetworkService()->findAccountsByType('pushd');
		if(empty($accounts)) {
			$mod->addMessage('Es wurde kein Pushd-Account gefunden.', 'Hinweis', 0);
			$subpartArr['###SEND_FORM###'] = '';
		}
		else {
			$accMenu = $this->getAccountSelector($mod, $accounts);

			$account = tx_rnbase::makeInstance('tx_t3socials_models_Network', $accMenu['value']);
			$eventMenu = $this->getEventSelector($mod, $account);

			// Letzte Tweets
//	  	$twitter = tx_rnbase::makeInstance('tx_t3socials_network_twitter_Connection');
//	  	$twitter->setNetwork($account);
//	  	$timeline = $twitter->getHomeTimeline();
//			t3lib_div::debug($accMenu['value'], 'class.tx_t3socials_mod_handler_Twitter.php'); // TODO: remove me

			$markerArr['###ACCOUNT_SEL###'] = $accMenu['menu'];
			$markerArr['###ACCOUNT_EDITLINK###'] = $formTool->createEditLink('tx_t3socials_networks', $accMenu['value']);
			$markerArr['###EVENT_SEL###'] = $eventMenu === false ? '<strong>###LABEL_PUSHD_NOEVENTS###</strong>' : $eventMenu['menu'];
			$markerArr['###INPUT_MESSAGE###'] = $formTool->createTextArea('data[msg]', $this->data['msg']);
			$markerArr['###INPUT_TITLE###'] = $formTool->createTxtInput('data[title]', $this->data['title'], 50);
			$markerArr['###BTN_SEND###'] = $formTool->createSubmit('sendpushd', '###LABEL_SUBMIT###');
			$wrappedSubpartArr['###SEND_FORM###'] = '';

		}
//t3lib_div::debug($accMenu, 'class.tx_t3socials_mod_handler_Twitter.php'); // TODO: remove me

		//wenn die Liste leer ist, zeigen wir nur eine Meldung
//		$out .= (empty($list['totalsize'])) ? "<br/><b>".$GLOBALS['LANG']->getLL('msg_empty_areas') :
//											'<br/>'.$list['totalsize'].' '.$GLOBALS['LANG']->getLL('msg_areas_found').$list['pager']."</b>\n".$list['table'];

		$out = tx_rnbase_util_Templates::substituteMarkerArrayCached($template, $markerArr, $subpartArr, $wrappedSubpartArr);

		return $out;
	}

	/**
	 * Returns all accounts
	 *
	 * @param tx_rnbase_util_FormTool $formtool
	 * @param int $pid
	 * @param array $accounts
	 * @return array
	 */
	private function getAccountSelector($mod, $accounts){
		$entries = Array ();
		foreach($accounts As $account) {
			$entries[$account->uid] = $account->getName();
		}
		return $mod->getFormTool()->showMenu($mod->getPid(), 'pushd', $mod->getName(), $entries);

	}

	private function getEventSelector($mod, $account){
		$entries = Array ();
		$confId = 'pushd.events.';

// 		$conn = tx_rnbase::makeInstance('tx_t3socials_network_pushd_Connection');
// 		$conn->setNetwork($account);

		$events = $account->getConfigurations()->getKeyNames($confId);
 		foreach($events As $event) {
// 			$data = $conn->getEventStatus($event);

 			$entries[$event] = $account->getConfigData($confId.$event.'.label');
 			$entries[$event] = $entries[$event] ? $entries[$event] : $event;
 		}
 		if(empty($entries)) {
 			return false;
 		}
		return $mod->getFormTool()->showMenu($mod->getPid(), 'event', $mod->getName(), $entries);
	}

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/t3socials/mod/handler/class.tx_t3socials_mod_handler_Pushd.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/t3socials/mod/handler/class.tx_t3socials_mod_handler_Pushd.php']);
}
?>
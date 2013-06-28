<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012-2013 Rene Nitzsche (rene@system25.de)
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

class tx_t3socials_mod_handler_Twitter implements tx_rnbase_mod_IModHandler {

	private $data = array();
	private $warnings = array();

	public function getSubID() {
		return 'twitter';
	}
	public function getSubLabel() {
		return '###LABEL_TAB_TWITTER###';
	}

	/**
	 * @return tx_t3socials_mod_handler_MemberUpload
	 */
	public static function getInstance() {
		return tx_rnbase::makeInstance('tx_t3socials_mod_handler_Twitter');
	}
	/**
	 * Maximal 120 Zeichen plus $url
	 * Ohne URL maximal 140 Zeichen
	 * @param tx_rnbase_mod_IModule $mod
	 */
	public function handleRequest(tx_rnbase_mod_IModule $mod) {
		$submitted = t3lib_div::_GP('sendmsg');
		if(!$submitted) return '';

		$this->data = t3lib_div::_GP('data');
		$msg = trim($this->data['msg']);
		$url = trim($this->data['link']);
		$urlLen = strlen($url) ? 20 : 0;
		$SET = t3lib_div::_GP('SET');
		if(strlen($msg) + $urlLen > 140) {
			$info = 'Meldung zu lang. Maximal 140 Zeichen versenden.<br />';
			if($urlLen)
				$info .= ' Aktuell '.(strlen($msg) + $urlLen).' Zeichen (inkl. URL).';
			else
				$info .= ' Aktuell '.strlen($msg).' Zeichen.';
			$mod->addMessage($info, 'Hinweis', 1);
			return;
		}
		$message = $msg . ' ' . $url;
		$account = tx_rnbase::makeInstance('tx_t3socials_models_Network', $SET['twitter']);
		try {
	  	$twitter = tx_rnbase::makeInstance('tx_t3socials_network_twitter_Connection');
	  	$twitter->setNetwork($account);
	  	$twitter->sendTweet($message);
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
		// Auswahlbox mit den vorhandenen Twitter-Accounts
		$accounts = tx_t3socials_srv_ServiceRegistry::getNetworkService()->findAccountsByType('twitter');
		if(empty($accounts)) {
			$mod->addMessage('Es wurde kein Twitter-Account gefunden.', 'Hinweis', 0);
			$subpartArr['###SEND_FORM###'] = '';
		}
		else {
			$accMenu = $this->getAccountSelector($mod, $accounts);

			// Letzte Tweets
//			$account = tx_rnbase::makeInstance('tx_t3socials_models_Network', $accMenu['value']);
//	  	$twitter = tx_rnbase::makeInstance('tx_t3socials_network_twitter_Connection');
//	  	$twitter->setNetwork($account);
//	  	$timeline = $twitter->getHomeTimeline();
//			t3lib_div::debug($timeline[0], 'class.tx_t3socials_mod_handler_Twitter.php'); // TODO: remove me
	  	
			$markerArr['###ACCOUNT_SEL###'] = $accMenu['menu'];
			$markerArr['###ACCOUNT_EDITLINK###'] = $formTool->createEditLink('tx_t3socials_networks', $accMenu['value']);
			$markerArr['###INPUT_MESSAGE###'] = $formTool->createTextArea('data[msg]', $this->data['msg']);
			$markerArr['###INPUT_LINK###'] = $formTool->createTxtInput('data[link]', $this->data['link'], 40);
			$markerArr['###BTN_SEND###'] = $formTool->createSubmit('sendmsg', '###LABEL_SUBMIT###');
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
	 * Returns all rounds of current bet game
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
		return $mod->getFormTool()->showMenu($pid, 'twitter', $mod->getName(), $entries);

	}

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/t3socials/mod/handler/class.tx_t3socials_mod_handler_Twitter.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/t3socials/mod/handler/class.tx_t3socials_mod_handler_Twitter.php']);
}
?>
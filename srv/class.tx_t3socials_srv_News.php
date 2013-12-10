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
tx_rnbase::load('tx_rnbase_util_Logger');
tx_rnbase::load('tx_rnbase_util_DB');



/**
 * This is a demonstration service on how to use T3socials API.
 * 
 * @author Rene Nitzsche
 */
class tx_t3socials_srv_News extends t3lib_svbase {

	/**
	 * Verbreitung einer News an soziale Netzwerke
	 *
	 * @param tx_rnbase_model_base $news
	 */
	public function sendNews(tx_rnbase_model_base $news) {
		// Prüfen, ob die News schon versendet wurde
		$srv = tx_t3socials_srv_ServiceRegistry::getNetworkService();
		if($srv->hasSent($news->getUid(), 'tt_news')) return;

		$networkSrv = tx_t3socials_srv_ServiceRegistry::getNetworkService();

		// Die generische Message bauen
		$message = $this->buildGenericMessage($news);

		// Absender der Meldung an alle registrierten Accounts
		$networkSrv->sendMessage($message, 'news', array('urlbuilder'=>array($this, 'cbBuildUrl')));
		$srv->setSent($news->getUid(), 'tt_news');
	}
	protected function buildGenericMessage($news) {
		/**
		 * @var tx_t3socials_models_Message
		 */
		$message = tx_rnbase::makeInstance('tx_t3socials_models_Message', 'news');
		$message->setHeadline($news->getTitle());
		$message->setIntro($news->getShort());
		$message->setMessage($news->getBodytext());
		$message->setData($news);
		return $message;
	}
	/**
	 * URL auf News-Detailseite bauen
	 *
	 * @param tx_t3socials_model_Message $message
	 * @param tx_t3socials_models_Network $account
	 * @return string
	 */
	public function cbBuildUrl($message, $account) {
		$news = $message->getData();
		$config = $account->getConfigurations();
		tx_rnbase::load('tx_rnbase_util_Misc');
		tx_rnbase_util_Misc::prepareTSFE();
		$link = $config->createLink();
		$link->designatorString = 'tx_ttnews'; // tx_ttnews[tt_news]
		$link->initByTS($config, $account->getNetwork().'.news.link.show.', array('tt_news'=>$news->getUid()));
		$url = $link->makeUrl(false);
		return $url;
	}

	/**
	 * Load a news instance
	 *
	 * @param int $uid
	 * @return tx_rnbase_model_base or null
	 */
	public function makeInstance($uid) {
		$options['wrapperclass'] = 'tx_rnbase_model_base';
		$options['where'] = 'uid='.intval($uid);
		$rows = tx_rnbase_util_DB::doSelect('*', 'tt_news', $options);
		return !empty($rows) ? $rows[0] : null;
	}
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/t3socials/srv/class.tx_t3socials_srv_News.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/t3socials/srv/class.tx_t3socials_srv_News.php']);
}


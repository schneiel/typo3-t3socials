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
tx_rnbase::load('tx_t3socials_network_twitter_Connection');

class tx_t3socials_tests_network_twitter_Connection_testcase extends tx_phpunit_testcase {

	public function test_simpleConnection() {
		$accounts = tx_t3socials_srv_ServiceRegistry::getNetworkService()->findAccounts('twittertest');
		if(empty($accounts)) return;
		$account = $accounts[0];

		$url = 'http://www.chemnitzerfc.de/nlz/inhalt/aktuell/news/einzelansicht/article/160/d-junioren-hallen-r.html?cHash=7f8cac16508822dab54fb9ef52476fbb';
		$msg = 'Hello Twitter: '.date(DATE_RFC822).' #date #time '.$url;
		$result = tx_t3socials_network_twitter_Connection::sendTweet($msg,$account);
//		t3lib_div::debug($result, 'tx_t3socials_tests_network_twitter_Connection_testcase: '.__LINE__);
	}

	public function test_verifyConnection() {
		$accounts = tx_t3socials_srv_ServiceRegistry::getNetworkService()->findAccounts('twittertest');
		if(empty($accounts)) return;
		$account = $accounts[0];

		$result = tx_t3socials_network_twitter_Connection::verifyConnection($account);
		if($result->error)
			$this->fail($result->error);
		$this->assertTrue(true);
//		t3lib_div::debug($result, 'tx_t3socials_tests_network_twitter_Connection_testcase: '.__LINE__);
	}

	public function test_getCredentialsFromModel() {
		$cfg = 'twitter {
		 CONSUMER_KEY = L66
		 CONSUMER_SECRET = Lge9
		 OAUTH_TOKEN = 4739-hp2
		 OAUTH_SECRET = soXF
		}';
		$network = tx_rnbase::makeInstance('tx_t3socials_models_Network',(
			array('uid'=>2,'name'=>'twitter','network'=>'twitter','config'=>$cfg))
		);

		$cred = tx_t3socials_network_twitter_Connection::getCredentials($network);
		$this->assertEquals('L66', $cred['CONSUMER_KEY']);
		$this->assertEquals('Lge9', $cred['CONSUMER_SECRET']);
		$this->assertEquals('4739-hp2', $cred['OAUTH_TOKEN']);
		$this->assertEquals('soXF', $cred['OAUTH_SECRET']);
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/t3socials/tests/network/twitter/class.tx_t3socials_tests_network_twitter_Connection_testcase.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/t3socials/tests/network/twitter/class.tx_t3socials_tests_network_twitter_Connection_testcase.php']);
}

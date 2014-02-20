<?php
defined('TYPO3_MODE') || die('Access denied.');

require_once t3lib_extMgm::extPath('rn_base', 'class.tx_rnbase.php');
require_once t3lib_extMgm::extPath('t3socials', 'srv/ext_localconf.php');
require_once t3lib_extMgm::extPath('t3socials', 'hooks/ext_localconf.php');
tx_rnbase::load('tx_t3socials_network_Config');

tx_t3socials_network_Config::registerNetwork(
	'tx_t3socials_network_pushd_Connection'
);
tx_t3socials_network_Config::registerNetwork(
	'tx_t3socials_network_twitter_Connection'
);
tx_t3socials_network_Config::registerNetwork(
	'tx_t3socials_network_xing_Connection'
);

// eid für die hybridauth im Frontend
// $GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['t3socials-hybridauth']
// 	= t3lib_extMgm::extPath('t3socials') . 'network/hybridauth/class.tx_t3socials_network_hybridauth_OAuthCall.php';
// ajax id für die hybridauth im Backend
$GLOBALS['TYPO3_CONF_VARS']['BE']['AJAX']['t3socials-hybridauth']
	= t3lib_extMgm::extPath('t3socials') . 'network/hybridauth/class.tx_t3socials_network_hybridauth_OAuthCall.php'
		. ':tx_t3socials_network_hybridauth_OAuthCall->ajaxId';
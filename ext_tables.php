<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}


/* *** **************** *** *
 * *** BE Module Config *** *
 * *** **************** *** */
if (TYPO3_MODE == 'BE') {
	// Einbindung einer PageTSConfig
	tx_rnbase_util_Extensions::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:' . 't3socials' . '/mod/pageTSconfig.txt">');

	// communicator
	tx_rnbase_util_Extensions::addModule('user', 'txt3socialsM1', '', tx_rnbase_util_Extensions::extPath('t3socials') . 'mod/');
	tx_rnbase_util_Extensions::insertModuleFunction(
		'user_txt3socialsM1', 'tx_t3socials_mod_Communicator',
		tx_rnbase_util_Extensions::extPath('t3socials', 'mod/class.tx_t3socials_mod_Communicator.php'),
		'LLL:EXT:t3socials/mod/locallang.xml:label_t3socials_connector'
	);
	// trigger
	tx_rnbase_util_Extensions::insertModuleFunction(
		'user_txt3socialsM1', 'tx_t3socials_mod_Trigger',
		tx_rnbase_util_Extensions::extPath('t3socials', 'mod/class.tx_t3socials_mod_Trigger.php'),
		'LLL:EXT:t3socials/mod/locallang.xml:label_t3socials_trigger'
	);

}

/* *** *************** *** *
 * *** TCA definitions *** *
 * *** *************** *** */
$TCA['tx_t3socials_networks'] = array (
	'ctrl' => array (
		'title' => 'LLL:EXT:t3socials/Resources/Private/Language/locallang_db.xml:tx_t3socials_networks',
		'label' => 'name',
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,
		'default_sortby' => 'ORDER BY name asc',
		'delete' => 'deleted',
		'enablecolumns' => array (
			'disabled' => 'hidden',
		),
		'requestUpdate' => 'network',
		'dynamicConfigFile' => tx_rnbase_util_Extensions::extPath('t3socials', 'Configuration/TCA/Network.php'),
		'iconfile'          => 'EXT:t3socials/ext_icon.gif',
	),
	'feInterface' => array (
		'fe_admin_fieldList' => 'name,username,password,config',
	)
);
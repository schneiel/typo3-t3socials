<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}
if (TYPO3_MODE == 'BE') {
	// Einbindung einer PageTSConfig
	t3lib_extMgm::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:' . $_EXTKEY . '/mod/pageTSconfig.txt">');

	t3lib_extMgm::addModule('user', 'txt3socialsM1', '', t3lib_extMgm::extPath($_EXTKEY) . 'mod/');
	t3lib_extMgm::insertModuleFunction(
		'user_txt3socialsM1', 'tx_t3socials_mod_Communicator',
		t3lib_extMgm::extPath($_EXTKEY, 'mod/class.tx_t3socials_mod_Communicator.php'),
		'LLL:EXT:t3socials/mod/locallang.xml:label_t3socials_modname'
	);

}

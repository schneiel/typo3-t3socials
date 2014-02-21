<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

// include tca
require t3lib_extMgm::extPath($_EXTKEY, 'Configuration/TCA/ext_tables.php');

if (TYPO3_MODE == 'BE') {
	// $TBE_MODULES_EXT['xMOD_db_new_content_el']['addElClasses']['tx_mkkvbb_util_Wizicon'] = t3lib_extMgm::extPath($_EXTKEY).'util/class.tx_mkkvbb_util_Wizicon.php';
	require_once t3lib_extMgm::extPath($_EXTKEY, 'mod/ext_tables.php');

}

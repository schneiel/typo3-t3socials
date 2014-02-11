<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');


// Die TCE-Hooks
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = 'EXT:' . $_EXTKEY . '/hooks/class.tx_t3socials_hooks_TCEHook.php:tx_t3socials_hooks_TCEHook';




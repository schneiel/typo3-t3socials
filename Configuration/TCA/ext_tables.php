<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$TCA['tx_t3socials_networks'] = array (
	'ctrl' => array (
		'title' => 'LLL:EXT:t3socials/Ressources/Lang/locallang_db.xml:tx_t3socials_networks',
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
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'Configuration/TCA/Network.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'ext_icon.gif',
	),
	'feInterface' => array (
		'fe_admin_fieldList' => 'name,username,password,config',
	)
);
 
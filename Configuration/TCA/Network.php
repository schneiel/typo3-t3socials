 <?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$TCA['tx_t3socials_networks'] = array (
	'ctrl' => $TCA['tx_t3socials_networks']['ctrl'],
	'interface' => array (
		'showRecordFieldList' => 'hidden,name1,name2'
	),
	'feInterface' => $TCA['tx_t3socials_networks']['feInterface'],
	'columns' => array (
		'hidden' => array (
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array (
				'type'    => 'check',
				'default' => '0'
			)
		),
		'network' => Array (
			'exclude' => 1,
			'label' => 'LLL:EXT:t3socials/Resources/Private/Language/locallang_db.xml:tx_t3socials_networks_network',
			'config' => Array (
				'type' => 'select',
				'items' => array(array('','')),
				'itemsProcFunc' => 'EXT:t3socials/util/class.tx_t3socials_util_TCA.php:tx_t3socials_util_TCA->getNetworks',
				'size' => '1',
				'maxitems' => '1',
			)
		),
		'name' => Array (
			'exclude' => 1,
			'label' => 'LLL:EXT:t3socials/Resources/Private/Language/locallang_db.xml:tx_t3socials_networks_name',
			'config' => Array (
				'type' => 'input',
				'size' => '30',
				'eval' => 'trim,required',
			)
		),
		'username' => Array (
			'exclude' => 1,
			'label' => 'LLL:EXT:t3socials/Resources/Private/Language/locallang_db.xml:tx_t3socials_networks_username',
			'config' => Array (
				'type' => 'input',
				'size' => '30',
				'eval' => 'trim',
			)
		),
		'password' => Array (
			'exclude' => 1,
			'label' => 'LLL:EXT:t3socials/Resources/Private/Language/locallang_db.xml:tx_t3socials_networks_password',
			'config' => Array (
				'type' => 'input',
				'size' => '30',
				'eval' => 'trim',
			)
		),
		'actions' => Array (
			'exclude' => 1,
			'label' => 'LLL:EXT:t3socials/Resources/Private/Language/locallang_db.xml:tx_t3socials_networks_actions',
			'config' => Array (
				'type' => 'text',
				'cols' => '30',
				'rows' => '5',
				'eval' => 'trim',
			)
		),
		'config' => Array (
			'exclude' => 1,
			'label' => 'LLL:EXT:t3socials/Resources/Private/Language/locallang_db.xml:tx_t3socials_networks_config',
			'displayCond' => 'FIELD:network:REQ:TRUE', // Show only, if an Network was Set!
			'config' => Array (
				'type' => 'text',
				'cols' => '30',
				'rows' => '5',
				'eval' => 'trim',
				'wizards' => array(
					'appendDefaultTSConfig' => array(
						'type'   => 'userFunc',
						'notNewRecords' => 1,
						'userFunc' => 'EXT:t3socials/util/class.tx_t3socials_util_TCA.php:tx_t3socials_util_TCA->insertNetworkDefaultConfig',
						'params' => array(
							'insertBetween' => array('>', "</textarea"),
							'onMatchOnly' => '/^\s*$/',
						),
					),
				)
			)
		),
		'description' => Array (
			'exclude' => 0,
			'label' => '',
			'displayCond' => 'FIELD:network:REQ:TRUE', // Show only, if an Network was Set!
			'config' => Array (
				'type' => 'user',
				'userFunc' => 'EXT:t3socials/util/class.tx_t3socials_util_TCA.php:tx_t3socials_util_TCA->insertNetworkDescription',
			),
		),
	),
	'types' => array (
		'0' => array('showitem' => 'hidden;;1;;1-1-1,network;;network,name,username,password,actions,config')
	),
	'palettes' => array (
		'1' => array('showitem' => ''),
		'network' => array('showitem' => '--linebreak--,description'),
	)
);

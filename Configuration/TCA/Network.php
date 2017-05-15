<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

$TCA['tx_t3socials_networks'] = array(
    'ctrl' => $TCA['tx_t3socials_networks']['ctrl'],
    'interface' => array(
        'showRecordFieldList' => 'hidden,name,username,autosend'
    ),
    'feInterface' => $TCA['tx_t3socials_networks']['feInterface'],
    'columns' => array(
        'hidden' => array(
            'exclude' => 1,
            'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
            'config'  => array(
                'type'    => 'check',
                'default' => '0'
            )
        ),
        'network' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:t3socials/Resources/Private/Language/locallang_db.xml:tx_t3socials_networks_network',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => array(array('','')),
                'itemsProcFunc' => 'EXT:t3socials/util/class.tx_t3socials_util_TCA.php:tx_t3socials_util_TCA->getNetworks',
                'size' => '1',
                'maxitems' => '1',
            )
        ),
        'name' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:t3socials/Resources/Private/Language/locallang_db.xml:tx_t3socials_networks_name',
            'config' => array(
                'type' => 'input',
                'size' => '30',
                'eval' => 'trim,required',
            )
        ),
        'username' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:t3socials/Resources/Private/Language/locallang_db.xml:tx_t3socials_networks_username',
            'config' => array(
                'type' => 'input',
                'size' => '30',
                'eval' => 'trim',
            )
        ),
        'password' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:t3socials/Resources/Private/Language/locallang_db.xml:tx_t3socials_networks_password',
            'config' => array(
                'type' => 'input',
                'size' => '30',
                'eval' => 'trim',
            )
        ),
        'actions' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:t3socials/Resources/Private/Language/locallang_db.xml:tx_t3socials_networks_actions',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectSingle',
                'itemsProcFunc' => 'EXT:t3socials/util/class.tx_t3socials_util_TCA.php:tx_t3socials_util_TCA->getTriggers',
                'size' => '5',
                'maxitems' => '999',
            ),
        ),
        'autosend' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:t3socials/Resources/Private/Language/locallang_db.xml:tx_t3socials_networks_autosend',
            'config'  => array(
                'type'    => 'check',
                'default' => '0'
            ),
        ),
        'config' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:t3socials/Resources/Private/Language/locallang_db.xml:tx_t3socials_networks_config',
            // Show only, if an Network was Set!
            'displayCond' => 'FIELD:network:REQ:TRUE',
            'config' => array(
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
                            'insertBetween' => array('>', '</textarea'),
                            'onMatchOnly' => '/^\s*$/',
                        ),
                    ),
                )
            )
        ),
        'description' => array(
            'exclude' => 0,
            'label' => '',
            // Show only, if an Network was Set!
            'displayCond' => 'FIELD:network:REQ:TRUE',
            'config' => array(
                'type' => 'user',
                'userFunc' => 'EXT:t3socials/util/class.tx_t3socials_util_TCA.php:tx_t3socials_util_TCA->insertNetworkDescription',
            ),
        ),
    ),
    'types' => array(
        '0' => array('showitem' => 'hidden;;1;;1-1-1,network;;network,name,username,password,actions,autosend,config')
    ),
    'palettes' => array(
        '1' => array('showitem' => ''),
        'network' => array('showitem' => '--linebreak--,description'),
    )
);

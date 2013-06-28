<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

require_once(t3lib_extMgm::extPath('rn_base') . 'class.tx_rnbase.php');
tx_rnbase::load('tx_t3socials_srv_ServiceRegistry');
tx_rnbase::load('tx_rnbase_util_SearchBase');

t3lib_extMgm::addService($_EXTKEY,  't3socials' /* sv type */,  'tx_t3socials_srv_Network' /* sv key */,
  array(
    'title' => 'Social network accounts', 'description' => 'Handles accounts of social networks', 'subtype' => 'network',
    'available' => TRUE, 'priority' => 50, 'quality' => 50,
    'os' => '', 'exec' => '',
    'classFile' => t3lib_extMgm::extPath($_EXTKEY).'srv/class.tx_t3socials_srv_Network.php',
    'className' => 'tx_t3socials_srv_Network',
  )
);

t3lib_extMgm::addService($_EXTKEY,  't3socials' /* sv type */,  'tx_t3socials_srv_News' /* sv key */,
  array(
    'title' => 'News for social networks', 'description' => 'Send news messages to social networks', 'subtype' => 'news',
    'available' => TRUE, 'priority' => 50, 'quality' => 50,
    'os' => '', 'exec' => '',
    'classFile' => t3lib_extMgm::extPath($_EXTKEY).'srv/class.tx_t3socials_srv_News.php',
    'className' => 'tx_t3socials_srv_News',
  )
);


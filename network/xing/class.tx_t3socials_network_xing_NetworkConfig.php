<?php
/***************************************************************
*  Copyright notice
*
 * (c) 2014 DMK E-BUSINESS GmbH <dev@dmk-ebusiness.de>
 * All rights reserved
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

tx_rnbase::load('tx_t3socials_models_NetworkConfig');


/**
 * XING Configuration
 *
 * @package tx_t3socials
 * @subpackage tx_t3socials_network
 * @author Michael Wagner <dev@dmk-ebusiness.de>
 * @license http://www.gnu.org/licenses/lgpl.html
 *          GNU Lesser General Public License, version 3 or later
 */
class tx_t3socials_network_xing_NetworkConfig extends tx_t3socials_models_NetworkConfig
{

    /**
     * Initialisiert die Konfiguration für das Netzwerk.
     *
     * @return void
     */
    protected function initConfig()
    {
        parent::initConfig();
        $this->setProperty('provider_id', $this->uid = 'xing');
        $this->setProperty('hybridauth_provider', 'XING');
        $this->setProperty('connector', 'tx_t3socials_network_xing_Connection');
        $this->setProperty('communicator', 'tx_t3socials_mod_handler_Xing');
    }
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/t3socials/network/xing/class.tx_t3socials_network_xing_Connection.php']) {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/t3socials/network/xing/class.tx_t3socials_network_xing_Connection.php']);
}

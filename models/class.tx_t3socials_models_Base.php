<?php
/***************************************************************
*  Copyright notice
*
 * (c) 2014 DMK E-BUSINESS GmbH <kontakt@dmk-ebusiness.de>
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
require_once t3lib_extMgm::extPath('rn_base', 'class.tx_rnbase.php');
tx_rnbase::load('tx_rnbase_model_base');

/**
 * Basismodel
 *
 * @package tx_t3socials
 * @subpackage tx_t3socials_models
 * @author Michael Wagner <michael.wagner@dmk-ebusiness.de>
 * @license http://www.gnu.org/licenses/lgpl.html
 *          GNU Lesser General Public License, version 3 or later
 */
class tx_t3socials_models_Base
	extends tx_rnbase_model_base {

	/**
	 * Setzt einen Wert oder ersetzt alle Werte
	 *
	 * @param string|array $property
	 * @param mixed $value
	 * @return tx_t3socials_models_Base
	 */
	public function setProperty($property, $value = NULL) {
		// wir überschreiben den kompletten record
		if (is_array($property)) {
			$this->init($property);
		}
		// wir setzen einen bestimmten wert
		else {
			$this->record[$property] = $value;
		}
		return $this;
	}
	/**
	 * Liefert einen bestimmten Wert oder alle.
	 *
	 * @param string $property
	 * @return string
	 */
	protected function getProperty($property = NULL) {
		if (is_null($property)) {
			return $this->record;
		}
		return isset($this->record[$property])
			? $this->record[$property]
			: NULL;
	}

	/**
	 * Converts field names for setters and geters
	 *
	 * @param string $string
	 * @return string
	 */
	protected function underscore($string) {
		return tx_rnbase_util_Misc::camelCaseToLowerCaseUnderscored($string);
		// return strtolower(preg_replace('/(.)([A-Z])/', "$1_$2", $string));
	}

	/**
	 * Set/Get attribute wrapper
	 *
	 * @param string $method
	 * @param array $args
	 * @throws Exception
	 * @return mixed
	 */
	public function __call($method, $args) {
		switch (substr($method, 0, 3)) {
			// getColumnValue
			case 'get':
				$key = $this->underscore(substr($method, 3));
				return $this->getProperty($key);
			// setColumnValue
			case 'set':
				$key = $this->underscore(substr($method, 3));
				$result = $this->setProperty($key, isset($args[0]) ? $args[0] : NULL);
			// unsetColumnValue
			case 'uns':
				$key = $this->underscore(substr($method, 3));
				unset($this->record[$key]);
				return $result;
			// hasColumnValue
			case 'has':
				$key = $this->underscore(substr($method, 3));
				return isset($this->record[$key]);
			default:
		}
		throw new Exception(
			'Sorry, Invalid method ' . get_class($this) . '::' . $method .
				'(' . print_r($args, 1) . ').',
			1370258960
		);
	}

	/**
	 * Wandelt das Model in einen String um
	 *
	 * @return string
	 */
	public function __toString() {
		$data = $this->getRecord();
		$out  = get_class($this) . ' (' . CRLF;
		foreach ($data as $key => $value) {
			$type = gettype($value);
			$value = is_object($value) ? (string) $value : $value;
			$value = is_string($value) ? '"' . $value . '"' : $value;
			$value = is_bool($value) ? (int) $value : $value;
			$out .= TAB . $key . ' (' . $type . ')';
			$out .= ': ' . $value . CRLF;
		}
		return $out . ');';
	}

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/t3socials/models/class.tx_t3socials_models_Base.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/t3socials/models/class.tx_t3socials_models_Base.php']);
}

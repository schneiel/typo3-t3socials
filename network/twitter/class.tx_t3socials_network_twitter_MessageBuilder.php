<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2012 Rene Nitzsche (rene@system25.de)
*  All rights reserved
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

require_once(t3lib_extMgm::extPath('rn_base') . 'class.tx_rnbase.php');

tx_rnbase::load('tx_rnbase_util_Logger');


/**
 * 
 */
class tx_t3socials_network_twitter_MessageBuilder {
	/**
	 * Creates a tweet from generic message
	 *
	 * @param tx_t3socials_models_IMessage $message
	 * @param tx_t3socials_models_Network $account
	 * @param string $confId
	 */
	public function build($message, $account, $confId) {
		// Wir müssen in erster Linie auf die Länge achten
		$url = $message->getUrl();
		// Für Twitter alle Tags entfernen
		// Wenn ein Intro vorhanden ist, wird dieses bevorzugt.
		$msg = htmlspecialchars_decode(strip_tags(trim($message->getIntro() ? $message->getIntro() : $message->getMessage())),ENT_QUOTES);
		$title = htmlspecialchars_decode(strip_tags(trim($message->getHeadline())),ENT_QUOTES);

		$tweet = '';
		// Jetzt die Länge berechnen
		// 140 Gesamt, 20 Url
		$charsAvailable = $url ? 120 : 140;

		// Zuerst der Title
		$charsAvailable = $charsAvailable - strlen($title);
		if($charsAvailable < 0) {
			// Titel ist schon zu lang
			$tweet .= self::cropText($title, $charsAvailable, '...', true);
		}
		elseif($title)
			$tweet .= $title;

		if($charsAvailable > 10 && $msg) {
			// Es ist noch Platz für die Nachricht. Wir erwarten hier mal mindestens 10 Zeichen
			$charsAvailable = $charsAvailable -2; // Doppelpunkt und Leerzeichen rausrechnen
			$charsAvailable = $charsAvailable - strlen($msg);

			if($charsAvailable < 0) {
				// Titel ist schon zu lang
				$tweet .= ': '.self::cropText($msg, $charsAvailable, '...', true);
			}
			else
				$tweet .= ': '.$msg;
		}

		return $tweet . ($url ? ' '.$url : '');
	}

	/**
	 * Crop text. This method is taken from TYPO3 stdWrap
	 *
	 * @param string $text
	 * @param int $chars maximum length of string
	 * @param string $afterstring Something like "..."
	 * @param boolean $crop2space crop on last space character
	 * @return string
	 */
	public static function cropText($text, $chars, $afterstring, $crop2space) {
		if(strlen($text) < $chars) {
			return $text;
		}
		// Kürzen
		$text = substr($text,0,($chars-strlen($afterstring)));
		$trunc_at = strrpos($text, ' ');
		$text = ($trunc_at&&$crop2space) ? substr($text, 0, $trunc_at).$afterstring : $text.$afterstring;
		return $text;
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/t3socials/network/twitter/class.tx_t3socials_network_twitter_MessageBuilder.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/t3socials/network/twitter/class.tx_t3socials_network_twitter_MessageBuilder.php']);
}

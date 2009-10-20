<?php

/***************************************************************
*  Copyright notice
*
*  (c) 2005 - 2009 Dimitri König <dk@cabag.ch>
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

require_once(PATH_t3lib.'class.t3lib_div.php');

class tx_yellowpay2commerce_hooks {

	/**
	 *
	 * This method gets the fe_user orderID set in the yellowpay2commerce method 'finishingFunction'
	 * @param 	string	$orderId from Parent Page
	 * @param 	array	$basket	current Basket
	 * @param 	array	$reference The frontend plugin (in this case pi3)
	 * @return	string	HTML-Content: orderID
	 */
	function generateOrderId($orderId, $basket, $reference) {
		$orderID = t3lib_div::_GP('orderID');
		
		if(empty($orderID)) {
			$orderID = $GLOBALS['TSFE']->fe_user->getKey('ses', 'orderID');
		}
		if(empty($orderID)) {
			$orderID = $this->getLatestOrderID();
		}

		return $orderID;
	}

	function getLatestOrderID() {
		$orderID = time();

		$ext_conf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['yellowpay2commerce']);
		$startcount = $ext_conf['yellowpayOrderIDstart'];

		if(!empty($startcount)) {
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
				'uid',
				'tx_commerce_orders',
				'',
				'',
				'uid DESC',
				'1'
			);
			if(count($res) == 1) {
				$orderID = intval($startcount) + intval($res[0]['uid']) + 1;
				$orderID .= '.' . substr(time(), -3);
			}
		}
		return $orderID;
	}
	
	function preinsert(&$orderData,$reference) {
		$orderComment = $GLOBALS['TSFE']->fe_user->getKey('ses', 'orderComment');
		if(!empty($orderComment)) {
			$orderData['comment'] = urldecode($orderComment);
		}
	}
	
	function restoreSession() {
		if(!empty($GLOBALS['tx_yellowpay2commerce_sv1_tmp_ses']['ses_id'])) {
			$GLOBALS['TSFE']->fe_user->id = $GLOBALS['tx_yellowpay2commerce_sv1_tmp_ses']['ses_id'];
			$GLOBALS["TSFE"]->fe_user->sesData = $GLOBALS['tx_yellowpay2commerce_sv1_tmp_ses']['ses_content'];
		}
	}
	
}

?>

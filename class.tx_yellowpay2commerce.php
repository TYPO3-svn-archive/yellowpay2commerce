<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2005 - 2008 Cedric Spindler <cs@cabag.ch>
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

class tx_yellowpay2commerce {
	// In this var the wrong fields are stored (for future use)
	var $errorFields = array();
	
	// This var holds the errormessages (keys are the fieldnames)
	var $errorMessages = array();
	
	// The object of the frontend plugin (in this case pi3)
	var $pObj = false;
	
	/**
	 * Ask for addidtional payment data (step 3 in the order process).
	 * Not needed in this case, because all payment handling is done by yellowpay.
	 * 
	 * @param array $pObj: The object of the frontend plugin (in this case pi3)
	 * 
	 * @return boolean true or false
	 */
	function needAdditionalData($pObj) {
		if(!is_object($this->pObj)) {
			$this->pObj = $pObj;
		}
		
		return false;
	}
	
	
	/**
	 * Configure the additional fields for payment data (step 3 in the order process).
	 * Not needed in this case, because all payment handling is done by yellowpay.
	 * 
	 * @param array $pObj: The object of the frontend plugin (in this case pi3)
	 * 
	 * @return boolean true or false
	 */
	function getAdditonalFieldsConfig($pObj) {
		if(!is_object($this->pObj)) {
			$this->pObj = $pObj;
		}
		
		return NULL;
	}
	
	
	/**
	 * Validate the additional payment data (step 3 in the order process).
	 * Not needed in this case, because all payment handling is done by yellowpay.
	 * 
	 * @param array $formData: Form data to be verified
	 * @param array $pObj: The object of the frontend plugin (in this case pi3)
	 * 
	 * @return boolean true or false
	 */
	function proofData($formData,$pObj) {
		if(!is_object($this->pObj)) {
			$this->pObj = $pObj;
		}
		
		return true;
	}
	
	
	/**
	 * Check if there a special finishing form is required.
	 * Not needed in this case, because there is no special finishing.
	 * 
	 * @param array $request: POST/GET parameters
	 * @param array $pObj: The object of the frontend plugin (in this case pi3)
	 * 
	 * @return boolean true or false
	 */
	function hasSpecialFinishingForm($request,$pObj) {
		return false;
	} 
	
	
	/**
	 * Generate the special finishing form.
	 * Not needed in this case, because there is no special finishing.
	 *
	 * @param	array	$config: The configuration from the TYPO3_CONF_VARS
	 * @param	array	$session: The session array
	 * @param	array	$basket: The basket object
	 * @param	array	$pObj: The object of the frontend plugin (in this case pi3)
	 * 
	 * @return boolean true or false
	 */
	function getSpecialFinishingForm($config, $session, $basket, $pObj) {
		return false;
	}
	
	
	/**
	 * Get info for the external payment process.
	 * Finish the order process if payment was successful.
	 *
	 * @param	array	$config: The configuration from the TYPO3_CONF_VARS
	 * @param	array	$session: The session array
	 * @param	array	$basket: The basket object
	 * @param	array	$pObj: The object of the frontend plugin (in this case pi3)
	 * 
	 * @return boolean true or false
	 */
	function finishingFunction($config, $session, $basket, $pObj) {
		if(!is_object($this->pObj)) {
			$this->pObj = $pObj;
		}
		
		// redirect URL handling
		if($this->checkFromYellowpay()){
			return true;
		}
		
		// get extension manager configuration
		$ext_conf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['yellowpay2commerce']);
		
		// use test or live mode
		if(!empty($ext_conf['yellowpayPaymentURL'])) {
			$paymentURL = $ext_conf['yellowpayPaymentURL'];
		} else {
			$paymentURL = $ext_conf['yellowpayTestPaymentURL'];
		}
		
		// payment URL and PSPID are mandatory for the use of the payment gateway.
		if(!empty($paymentURL) && !empty($ext_conf['yellowpayPSPID'])) {
			
			// url to forward the user to if payment was successfull or failed
			$accepturl = 'http://'.$_SERVER['SERVER_NAME'].'?id='.$GLOBALS['TSFE']->id.'&tx_commerce_pi3[step]=finish&tx_commerce_pi3[terms]=termschecked&tx_commerce_pi3[yellowpay]=success&tx_commerce_pi3[comment]='.urlencode($pObj->piVars['comment']);
			$errorurl = 'http://'.$_SERVER['SERVER_NAME'].'?id='.$GLOBALS['TSFE']->id.'&tx_commerce_pi3[step]=payment';
			
			// remove charset suffix from locale
			$locale = explode('.',$GLOBALS['TSFE']->config['config']['locale_all']);
			
			$paymentParams = array (
				'PSPID' => $ext_conf['yellowpayPSPID'],
				'orderID' => $ext_conf['yellowpayOrderIDprefix'].time(),
				'amount' => $basket->basket_sum_gross,
				'currency' => $pObj->conf['currency'],
				'language' => $locale[0],
				'CN' => $session['billing']['surname'].' '.$session['billing']['name'],
				'EMAIL' => $session['billing']['email'],
				'owneraddress' => $session['billing']['address'],
				'ownerZIP' => $session['billing']['zip'],
				'ownertown' => $session['billing']['city'],
				'ownercty' => 'Switzerland',
				'ownertelno' => $session['billing']['phone'],
				'accepturl' => $accepturl,
				'declineurl' => $errorurl,
				'exceptionurl' => $errorurl,
				'cancelurl' => $errorurl
			);
			$paymentParams['COM'] = $ext_conf['yellowpayOrderTitle'];
			
			if(!empty($ext_conf['yellowpayTPURL'])) {
				$paymentParams['TP'] = $ext_conf['yellowpayTPURL'];
			}
			
			// Process Yellowpay payment gateway forward
			$this->sendToYellowpay($paymentURL, $paymentParams);
			
			// Return fals after forwarding the user to yellowpay so order stays open
			return false;
		} else {
			return false;
		}
	}
	
	
	/**
	 * Save the Order. Nothing special here.
	 * Use this if you want to safe any yellowpay specific data to the order.
	 *
	 * @param	array	$orderUid: UID of the payed order
	 * @param	array	$session: The session array
	 * @param	array	$pObj: The object of the frontend plugin (in this case pi3)
	 * 
	 * @return boolean true or false
	 */
	function updateOrder($orderUid, $session, $pObj) {
		if(!is_object($this->pObj)) {
			$this->pObj = $pObj;
		}
		
		return true;
	}
	
	
	/**
	 * Returns the last error message. Not used in this case.
	 *
	 * @param	boolean	$finish: 1 if called from the last step (finish). Default: 0
	 * @param	array	$pObj: The object of the frontend plugin (in this case pi3)
	 * 
	 * @return array $errorMessages: The error codes array
	 */
	function getLastError($finish = 0,$pObj) {
		if(!is_object($this->pObj)) {
			$this->pObj = $pObj;
		}
		if($finish) {
			return $this->getReadableError();
		} else {
			return $this->errorMessages[(count($this->errorMessages) -1)];
		}
	}
	
	
	/**
	 * Error Code Handling. Not used in this case.
	 * 
	 * @return string $back: The error message
	 */
	function getReadableError() {
		$back = '';
		reset($this->errorMessages);
	  while(list($k,$v) = each($this->errorMessages)) {
			$back .= $v;
		}
		
		return $back;
	}
	
	
	/**
	 * Compile the URL Send the request to yellowpay
	 * 
	 * @param	string $paymentURL: Payment URL calling the payment gateway
	 * @param	array $paymentParams: Payment parameters, sent to the payment gateway
	 * 
	 * @return string The Header forward
	 */
	function sendToYellowpay($paymentURL, $paymentParams) {
		$first = true;
		foreach($paymentParams as $key => $value) {
			$glue = ($first === true)?'?':'&';
			$paymentURL .= $glue.$key.'='.urlencode($value);
			$first = false;
		}
		
		header("Location: ".$paymentURL);
	}
	
	
	/**
	 * Evaluate the Redirect URL from yellowpay
	 * 
	 * @return boolean true or false
	 */
	function checkFromYellowpay() {
		// if yellowpay was successfull it sends this parameter back to commerce (continue to commerce order confirmation)
		if(!empty($this->pObj->piVars['yellowpay'])){
			return true;
		} else {
			return false;
		}
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']["ext/yellowpay2commerce/class.tx_yellowpay2commerce.php"])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']["ext/yellowpay2commerce/class.tx_yellowpay2commerce.php"]);
}
?>
